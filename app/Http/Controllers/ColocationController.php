<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
    
use Illuminate\Support\Facades\Auth;
use App\Models\Colocation;
use App\Models\Membership;

class ColocationController extends Controller
{
public function show(Colocation $colocation)
{
    $user = auth()->user();

    // Check membership and left_at
    $membership = $user->colocations()
        ->where('colocation_id', $colocation->id)
        ->first();

    if (!$membership || $membership->pivot->left_at) {
        abort(403, 'You do not have access to this colocation.');
    }

    if ($colocation->status === 'cancelled') {
        abort(403, "This colocation has been cancelled.");
    }

    // your existing logic

    $colocation->load([
        'owner',
        'members',
        'expenses.payeur',
        'expenses.category',
        'settlements'
    ]);

    return view('colocations.show', compact('colocation'));
}

public function create()
{
    return view('colocations.create');
}
public function myColocations()
{
    $user = auth()->user();

    // Only active memberships (left_at is null)
    $colocations = $user->colocations()
        ->wherePivot('left_at', null)
        ->get();

    return view('colocations.my', compact('colocations'));
}
public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255'
    ]);

    $user = auth()->user();

    // Regular users can only have one active colocation
    if ($user->global_role !== 'admin' && $user->activeMembership()) {
       return back()->withErrors('You already have an active colocation');
    }

    // create colocation
    $colocation = Colocation::create([
        'name' => $request->name,
        'owner_id' => $user->id,
        'status' => 'active'
    ]);

    // create membership for owner
    Membership::create([
        'user_id' => $user->id,
        'colocation_id' => $colocation->id,
        'role' => 'owner',
        'joined_at' => now()
    ]);

    return redirect()->route('colocations.show', $colocation);
}
public function removeMember(Colocation $colocation ,$user)
{
    $owner = auth()->user();

    if ($owner->id !== $colocation->owner_id) {
        abort(403, 'Only the owner can remove members.');
    }

    // Optional: prevent owner from removing themselves
    if ($user === $owner->id) {
        return redirect()->back()->withErrors('You cannot remove yourself.');
    }

    // Remove member
    $colocation->members()->detach($user);

    return redirect()->back()->with('success', "has been removed from the colocation.");
}

public function leave(Colocation $colocation)
{
    $user = auth()->user();

    // Get the membership row
    $membership = $user->colocations()
        ->where('colocation_id', $colocation->id)
        ->first();

    if (!$membership) {
        return back()->withErrors('You are not a member of this colocation.');
    }

    // Prevent owner from leaving
    if ($membership->pivot->role === 'owner') {
        return back()->withErrors('Owner cannot leave the colocation.');
    }

    // Soft leave: set left_at timestamp
    $user->colocations()->updateExistingPivot($colocation->id, [
        'left_at' => now()
    ]);

    return redirect()->route('colocations.my')->with('success', 'You have left the colocation.');
}
public function cancel(Colocation $colocation)
{
    $user = auth()->user();

    // Only owner can cancel
    if ($colocation->owner_id !== $user->id) {
        return back()->withErrors('Only the owner can cancel this colocation.');
    }

    // Mark as cancelled
    $colocation->update([
        'status' => 'cancelled',
    ]);

    return back()->with('success', 'Colocation cancelled successfully.');
}
public function statistics(Colocation $colocation)
{
    $colocation->load('expenses.category');

    // 1. Group expenses by category
    $expensesByCategory = $colocation->expenses
        ->groupBy(fn($expense) => $expense->category->name)
        ->map(fn($group) => $group->sum('amount'));

    // 2. Group expenses by month
    $expensesByMonth = $colocation->expenses
        ->groupBy(fn($expense) => $expense->date->format('Y-m'))
        ->map(fn($group) => $group->sum('amount'));

    return view('colocations.statistics', compact('colocation', 'expensesByCategory', 'expensesByMonth'));
}
}