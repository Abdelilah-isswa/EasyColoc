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
        $colocation->load([
            'owner',
            'members',
            'expenses.payeur',
            'expenses.category'
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

   
$colocations = auth()->user()->colocations()->get();
    return view('colocations.my', compact('colocations'));
}
public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255'
    ]);

    $user = auth()->user();

    // rule: only one active colocation
    if ($user->activeMembership()) {
       return back()->withErrors('You already have an active colocation');
    }

    // create colocation
    $colocation = Colocation::create([
        'name' => $request->name,
        'owner_id' => $user->id,
        'status' => 'active'
    ]);

    // create membership owner
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

    // Check if the user is a member of this colocation
    if (!$user->colocations()->where('colocation_id', $colocation->id)->exists()) {
        return back()->withErrors('You are not a member of this colocation.');
    }

    // Prevent owner from leaving
    $membership = $user->colocations()->where('colocation_id', $colocation->id)->first();
    if ($membership->pivot->role === 'owner') {
        return back()->withErrors('Owner cannot leave the colocation.');
    }

    // Detach from pivot table (delete membership)
    $user->colocations()->detach($colocation->id);

    return redirect()->route('colocations.my')->with('success', 'You have left the colocation.');
}


}