<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use App\Models\Invitation;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
 protected function create(array $data)
{
    return DB::transaction(function() use ($data) {
        $isFirst = User::count() === 0;

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'global_role' => $isFirst ? 'admin' : 'user',
            'reputation_score' => 0,
            'is_banned' => false,
        ]);
    });
}

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
public function store(Request $request): RedirectResponse
{
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
        'invitation_token' => ['nullable', 'string', 'exists:invitations,token'], // add this
    ]);
    $globalRole = DB::table('users')->count() === 0 ? 'admin' : 'user';

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'global_role'=> $globalRole,
    ]);

    event(new Registered($user));

    // ✅ Handle invitation token
    if ($request->filled('invitation_token')) {
        $invitation = Invitation::where('token', $request->invitation_token)
                                ->where('status', 'pending')
                                ->first();

        if ($invitation) {
            // Prevent user from joining multiple colocations
            if (!$user->memberships()->whereNull('left_at')->exists()) {
                $invitation->colocation->members()->attach($user->id, ['role' => 'Member']);
                $invitation->update(['status' => 'accepted']);
            }
        }
    }

    Auth::login($user);

    return redirect(route('home', absolute: false));
}

    public function showRegistrationForm(?string $token = null)
{
    return view('auth.register', ['invitationToken' => $token]);
}
}
