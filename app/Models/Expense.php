<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
        protected $fillable = [
        'colocation_id',
        'category_id',
        'title',
        'amount',
        'payeur_id',
        'date',
    ];

    public function colocation()
    {
        return $this->belongsTo(Colocation::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function payeur()
    {
        return $this->belongsTo(User::class, 'payeur_id');
    }
}
