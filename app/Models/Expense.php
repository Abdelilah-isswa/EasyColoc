<?php

namespace App\Models;
use Carbon\Carbon;
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
      protected $casts = [
        'date' => 'datetime',  
    ];
  public function getMonthAttribute()
{
    return $this->date ? $this->date->format('Y-m') : null;
}
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
// App\Models\Expense.php
public function settlements()
{
    return $this->hasMany(Settlement::class, 'expense_id');
}
}
