<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'reputation_score',
        'is_banned',
        'global_role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
  protected $casts = [
        'is_banned' => 'boolean',
        'email_verified_at' => 'datetime',
    ];


        public function ownedColocations()
    {
        return $this->hasMany(Colocation::class, 'owner_id');
    }

    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }
    public function activeMembership()
{
    return $this->hasOne(Membership::class)
        ->whereNull('left_at');
}

    public function expenses()
    {
        return $this->hasMany(Expense::class, 'payeur_id');
    }
    
    public function settlementsFrom()
    {
        return $this->hasMany(Settlement::class, 'from_user_id');
    }

    public function settlementsTo()
    {
        return $this->hasMany(Settlement::class, 'to_user_id');
    }

    // Helpers
    public function isGlobalAdmin()
    {
        return $this->global_role === 'admin';
    }
}
