<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
    
use Illuminate\Support\Facades\Auth;
use App\Models\Colocation;
use App\Models\Membership;

class ColocationController extends Controller
{
public function show(Colocation $colocation,Request $request)
{
    $user = auth()->user();
        $month = $request->query('month', now()->format('Y-m'));

    // Filter expenses by month
   $expenses = $colocation->expenses()
    ->with(['payeur','category','settlements'])
    ->whereYear('date', substr($month, 0, 4))
    ->whereMonth('date', substr($month, 5, 2))
    ->get();
    

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

 
        $members = $colocation->members;
    if (!$members->contains($colocation->owner)) {
        $members->push($colocation->owner);
    }
     $balances = [];
    $numMembers = $members->count();
    $totalExpenses = $expenses->sum('amount');

    // Calculate each member's balance
    foreach ($members as $member) {
        $paid = $expenses->where('payeur_id', $member->id)->sum('amount');
        $share = $numMembers > 0 ? $totalExpenses / $numMembers : 0;
        $balances[$member->id] = $paid - $share;
    }
    $months = collect(range(0, 11))->map(fn($i) => now()->subMonths($i)->format('Y-m'));

    return view('colocations.show', compact('colocation', 'expenses', 'months', 'month', 'balances', 'members'));
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
     // Check unpaid debts
    $unpaid = $colocation->expenses()
        ->whereHas('settlements', function ($q) use ($user) {
            $q->where('from_user_id', $user->id)
              ->whereNull('paid_at');
        })->exists();

    // Prevent owner from leaving
    if ($membership->pivot->role === 'owner') {
        return back()->withErrors('Owner cannot leave the colocation.');
    }
     // Update reputation
    if ($unpaid) {
        $user->decrement('reputation_score'); // -1
    } else {
        $user->increment('reputation_score'); // +1
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

    if ($colocation->owner_id !== $user->id) {
        abort(403, "Only the owner can cancel the colocation.");
    }

    // Cancel the colocation
    $colocation->update(['status' => 'cancelled']);

    // Adjust members' reputation
    foreach ($colocation->members as $member) {
        if ($member->id === $user->id) continue; // skip owner

        $unpaid = $colocation->expenses()
            ->whereHas('settlements', function ($q) use ($member) {
                $q->where('from_user_id', $member->id)
                  ->whereNull('paid_at');
            })->exists();

        if ($unpaid) {
            $member->decrement('reputation_score'); // -1
        } else {
            $member->increment('reputation_score'); // +1
        }

        // Soft leave all members
        $member->colocations()->updateExistingPivot($colocation->id, [
            'left_at' => now()
        ]);
    }

    return redirect()->route('colocations.my')->with('success', 'Colocation cancelled.');
}
public function statistics(Colocation $colocation)
{
    // Fetch expenses for this colocation
    $expenses = $colocation->expenses;

    // Prepare monthly stats
    $monthlyTotals = [];
    foreach ($expenses as $expense) {
        $month = $expense->month; // uses the accessor we added
        if (!isset($monthlyTotals[$month])) {
            $monthlyTotals[$month] = 0;
        }
        $monthlyTotals[$month] += $expense->amount;
    }

    // Prepare category stats
    $categoryTotals = [];
    foreach ($expenses as $expense) {
        $category = $expense->category->name;
        if (!isset($categoryTotals[$category])) {
            $categoryTotals[$category] = 0;
        }
        $categoryTotals[$category] += $expense->amount;
    }

    return view('colocations.statistics', compact(
        'colocation', 
        'monthlyTotals', 
        'categoryTotals'
    ));
}

public function allColocations()
{
     // Optional if you have a policy
    $colocations = \App\Models\Colocation::all(); // all colocations
    return view('admin.colocations.index', compact('colocations'));
}
}