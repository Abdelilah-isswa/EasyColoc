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

    // User can be owner or member
    $colocations = $user->ownedColocations()->with('members', 'expenses')->get();

    return view('colocations.my', compact('colocations'));
}
public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255'
    ]);

    $user = Auth::user();

    // rule: only one active colocation
   // if ($user->activeMembership()) {
     //   return back()->withErrors('You already have an active colocation');
    //}

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
}