<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Colocation;
use App\Models\Expense;
use App\Models\Settlement;
class ExpenseController extends Controller
{
public function store(Request $request, Colocation $colocation)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'amount' => 'required|numeric|min:0.01',
        'category_id' => 'required|exists:categories,id',
        
    ]);

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

public function history(Colocation $colocation)
{
    $user = auth()->user();

    if (!$colocation->members->contains($user)) {
        abort(403, 'You are not a member of this colocation.');
    }

    $expenses = $colocation->expenses()
        ->with('payeur', 'category')
        ->orderBy('date', 'desc')
        ->paginate(20); 

    return view('colocations.expenses.history', compact('colocation', 'expenses'));
}

public function pay(Colocation $colocation, Expense $expense)
{
    $user = auth()->user();

    if ($expense->payeur_id === $user->id || !$colocation->members->contains($user)) {
        abort(403);
    }

    $settlement = new Settlement();
    $settlement->from_user_id = $user->id;          
    $settlement->to_user_id = $expense->payeur_id;  
    $settlement->colocation_id = $colocation->id;
    $settlement->expense_id = $expense->id;        
    $settlement->amount = $expense->amount / $colocation->members->count(); 
    $settlement->paid_at = now();                   
    $settlement->save();

    return redirect()->back()->with('success', 'Expense marked as paid.');
}

}
