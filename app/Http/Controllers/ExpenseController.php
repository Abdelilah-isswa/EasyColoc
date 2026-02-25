<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Colocation;

class ExpenseController extends Controller
{
public function store(Request $request, Colocation $colocation)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'amount' => 'required|numeric|min:0.01',
        'category_id' => 'required|exists:categories,id',
        'date' => 'required|date',
    ]);

    // Ensure the category belongs to this colocation
    $category = $colocation->categories()->findOrFail($request->category_id);

    $colocation->expenses()->create([
        'title' => $request->title,
        'amount' => $request->amount,
        'category_id' => $category->id,
        'payeur_id' => auth()->id(),
        'date' => $request->date,
    ]);

    return back()->with('success', 'Expense added successfully.');
}
}
