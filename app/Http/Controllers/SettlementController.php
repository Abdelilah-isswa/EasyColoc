<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Settlement;
use Illuminate\Support\Facades\Auth;

class SettlementController extends Controller
{
    /**
     * Mark a settlement as paid.
     */
    public function pay(Request $request, Settlement $settlement)
    {
        $user = Auth::user();

        // 1️⃣ Authorization: check if user is part of the colocation
        $colocation = $settlement->colocation;
        if (!$colocation->members->contains($user->id)) {
            abort(403, "You are not part of this colocation.");
        }

        // 2️⃣ Only debtor (from_user) or owner can mark as paid
$isOwner = $colocation->owner_id === $user->id;
        if ($settlement->from_user_id !== $user->id && !$isOwner) {
            abort(403, "You are not allowed to mark this as paid.");
        }

        // 3️⃣ Check if already paid
        if ($settlement->paid_at) {
            return back()->with('status', 'This settlement is already marked as paid.');
        }

        // 4️⃣ Mark as paid
        $settlement->update([
            'paid_at' => now()
        ]);

        return back()->with('status', 'Settlement marked as paid successfully.');
    }

    /**
     * Optional: list settlements for a colocation.
     */
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

    // Only the debtor (from_user) can mark it as paid
    if ($settlement->from_user_id !== $user->id) {
        abort(403, 'You are not allowed to mark this as paid.');
    }

    $settlement->paid_at = now();
    $settlement->save();

    return redirect()->back()->with('success', 'Settlement marked as paid.');
}


}