<?php
namespace App\Http\Controllers;

use App\Models\Colocation;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\ColocationInvitationMail;

class InvitationController extends Controller
{
    // Owner sends invitation
    public function send(Request $request, Colocation $colocation)
    {   
         if ($request->user()->id !== $colocation->owner_id) {
            abort(403, 'Only the owner can invite users.');
        }
        $request->validate(['email' => 'required|email']);

        // Check user is owner
       

        $token = Str::random(32);

        $invitation = Invitation::create([
            'colocation_id' => $colocation->id,
            'email' => $request->email,
            'token' => $token,
            'expires_at' => now()->addDays(7),
        ]);

        // Send email
        Mail::to($request->email)->send(new ColocationInvitationMail($invitation));

        return back()->with('success', 'Invitation sent!');
    }

    // Show accept/decline form
    public function acceptForm($token)
    {
        $invitation = Invitation::where('token', $token)->firstOrFail();

        if ($invitation->isExpired() || $invitation->status !== 'pending') {
            abort(403, 'Invitation invalid or expired');
        }

        return view('invitations.accept', compact('invitation'));
    }

    // Accept invitation
    public function accept($token)
    {
        $invitation = Invitation::where('token', $token)->firstOrFail();
        $user = auth()->user();

        if ($invitation->isExpired() || $invitation->status !== 'pending') {
            abort(403, 'Invitation invalid or expired');
        }

        // Check user does not already have active colocation
        if ($user->memberships()->whereNull('left_at')->exists()) {
            return redirect('/dashboard')->withErrors('You already belong to a colocation.');
        }

        $invitation->colocation->members()->attach($user->id, ['role' => 'Member']);
        $invitation->update(['status' => 'accepted']);

        return redirect('/colocations/' . $invitation->colocation->id)
               ->with('success', 'You joined the colocation!');
    }

    // Decline invitation
    public function decline($token)
    {
        $invitation = Invitation::where('token', $token)->firstOrFail();
        $invitation->update(['status' => 'declined']);

        return redirect('/dashboard')->with('info', 'Invitation declined.');
    }
}

