<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
        protected $fillable = [
        'colocation_id',
        'email',
        'token',
        'status',
    ];

    public function colocation()
    {
        return $this->belongsTo(Colocation::class);
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isAccepted()
    {
        return $this->status === 'accepted';
    }

    public function isRefused()
    {
        return $this->status === 'refused';
    }
}
