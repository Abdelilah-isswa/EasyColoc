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
    // Only owner can invite
    if ($request->user()->id !== $colocation->owner_id) {
        abort(403, 'Only the owner can invite users.');
    }

    $request->validate(['email' => 'required|email']);
    $email = $request->email;
    $token = Str::random(32);
   
    $invitation = Invitation::create([
        'colocation_id' => $colocation->id,
        'email' => $email,
        'token' => $token,
        'expires_at' => now()->addDays(7),
    ]);
     
    // Send the email
    try {
        Mail::to($email)->send(new ColocationInvitationMail($invitation, $colocation));
    } catch (\Exception $e) {
        // If sending fails, delete the invitation to avoid dangling token
        $invitation->delete();
        return back()->withErrors('Failed to send invitation email: ' . $e->getMessage());
    }

    return back()->with('success', 'Invitation sent to ' . $email . '!');
}

    // Show accept/decline form
    public function acceptForm($token)
    {
        $invitation = Invitation::where('token', $token)->firstOrFail();

        if ( $invitation->status !== 'pending') {
            abort(403, 'Invitation invalid or expired');
        }

        return view('invitations.accept', compact('invitation'));
    }

    // Accept invitation
public function accept($token)
{
    if (!auth()->check()) {
        return redirect()->route('login')->with('info', 'Please log in first to accept the invitation.');
    }

    $invitation = Invitation::where('token', $token)->firstOrFail();
    
    $user = auth()->user();

    if ( $invitation->status !== 'pending') {
        abort(403, 'Invitation invalid or expired');
    }

    if ($user->memberships()->whereNull('left_at')->exists()) {
        return redirect('/')->withErrors('You already belong to a colocation.');
    }

    $invitation->colocation->members()->attach($user->id, ['role' => 'member', 'joined_at' => now()]);
    $invitation->update(['status' => 'accepted']);

    return redirect('/colocations/' . $invitation->colocation->id)
           ->with('success', 'You joined the colocation!');
}

    // Decline invitation
    public function decline($token)
    {
        $invitation = Invitation::where('token', $token)->firstOrFail();
        $invitation->update(['status' => 'declined']);

        return redirect('/home')->with('info', 'Invitation declined.');
    }
   
}

