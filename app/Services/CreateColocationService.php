<?php
namespace App\Services;

use App\Models\Colocation;
use App\Models\Membership;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CreateColocationService
{
    public function handle(array $data): Colocation
    {
        $user = Auth::user();

        // Rule: only one active colocation
        if ($user->activeMembership()->exists()) {
            throw ValidationException::withMessages([
                'colocation' => 'You already belong to an active colocation.'
            ]);
        }

        $colocation = Colocation::create([
            'name' => $data['name'],
            'owner_id' => $user->id,
            'status' => 'active',
        ]);

        Membership::create([
            'user_id' => $user->id,
            'colocation_id' => $colocation->id,
            'role' => 'owner',
        ]);

        return $colocation;
    }
}