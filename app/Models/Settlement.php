<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settlement extends Model
{
 protected $fillable = [
    'expense_id',
    'from_user_id',
    'to_user_id',
    'amount',
    'colocation_id',
    'paid_at',
];

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }

    public function colocation()
    {
        return $this->belongsTo(Colocation::class);
    }
    public function expense()
{
    return $this->belongsTo(Expense::class);
}
}

