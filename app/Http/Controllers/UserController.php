<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
  
public function index()
{
    $users = User::where('global_role', 'user')->get(); // exclude other admins
    return view('admin.users.index', compact('users'));
}

public function ban(User $user)
{
    if ($user->global_role === 'admin') {
        return back()->withErrors('You cannot ban another admin.');
    }

    $user->update(['is_banned' => true]);
    return back()->with('success', "{$user->name} has been banned.");
}

public function unban(User $user)
{
    $user->update(['is_banned' => false]);
    return back()->with('success', "{$user->name} has been unbanned.");
}
}
