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
        
    ]);

    // Ensure the category belongs to this colocation
    $category = $colocation->categories()->findOrFail($request->category_id);

    $colocation->expenses()->create([
        'title' => $request->title,
        'amount' => $request->amount,
        'category_id' => $request->category_id,
        'payeur_id' => auth()->id(),
        'date' => now(),
    ]);

    return redirect()->route('colocations.show',$colocation)->with('success','expence added');
}
public function create(Colocation $colocation){
$categories = $colocation->categories;
return view('expenses.create',compact('categories','colocation'));



}

}
