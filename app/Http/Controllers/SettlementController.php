<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Settlement;
use Illuminate\Support\Facades\Auth;

class SettlementController extends Controller
{
    
    public function pay(Request $request, Settlement $settlement)
    {
        $user = Auth::user();

        $colocation = $settlement->colocation;
        if (!$colocation->members->contains($user->id)) {
            abort(403, "You are not part of this colocation.");
        }

$isOwner = $colocation->owner_id === $user->id;
        if ($settlement->from_user_id !== $user->id && !$isOwner) {
            abort(403, "You are not allowed to mark this as paid.");
        }

        
        if ($settlement->paid_at) {
            return back()->with('status', 'This settlement is already marked as paid.');
        }

        
        $settlement->update([
            'paid_at' => now()
        ]);

        return back()->with('status', 'Settlement marked as paid successfully.');
    }

   
    public function index($colocationId)
    {
        $user = Auth::user();
        $colocation = \App\Models\Colocation::findOrFail($colocationId);

        // Only members of colocation
        if (!$colocation->members->contains($user->id)) {
            abort(403, "You are not part of this colocation.");
        }

        $settlements = $colocation->settlements()->with(['fromUser','toUser'])->get();

        return view('settlements.index', compact('settlements', 'colocation'));
    }
public function markAsPaid(Settlement $settlement)
{
    $user = auth()->user();

    if ($settlement->from_user_id !== $user->id) {
        abort(403, 'You are not allowed to mark this as paid.');
    }

    $settlement->paid_at = now();
    $settlement->save();

    return redirect()->back()->with('success', 'Settlement marked as paid.');
}


}