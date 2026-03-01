<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Colocation extends Model
{
    protected $fillable = [
        'name',
        'owner_id',
        'status',
    ];
        public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'memberships')
            ->withPivot('role','joined_at','left_at')
            ->withTimestamps();
    }
public function activeMembers()
{
    return $this->belongsToMany(User::class, 'memberships')
        ->withPivot('role','joined_at','left_at')
        ->wherePivotNull('left_at')
        ->withTimestamps();
}

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function settlements()
    {
        return $this->hasMany(Settlement::class);
    }

    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }
    //helper method 
    

public function recalculateBalances()
{
    $members = $this->members()->wherePivotNull('left_at')->get(); // only active members
    $balances = [];

    $totalExpenses = $this->expenses()->sum('amount');
    $memberCount = $members->count();

    if ($memberCount === 0) {
        return []; // no one to calculate
    }

    $perMemberShare = $totalExpenses / $memberCount;

    foreach ($members as $member) {
        $paid = $this->expenses()->where('payeur_id', $member->id)->sum('amount');
        $balances[$member->id] = $paid - $perMemberShare;
    }

    return $balances; // array: [user_id => balance]
}
}
