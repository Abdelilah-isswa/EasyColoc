<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Colocation;
use App\Models\Category;
class CategoryController extends Controller
{
      public function index(Colocation $colocation)
    {
        // Only owner can manage
        if (auth()->id() !== $colocation->owner_id) {
            abort(403, 'Unauthorized');
        }

        $categories = $colocation->categories;

        return view('categories.index', compact('colocation', 'categories'));
    }
    public function store(Request $request, Colocation $colocation)
    {
        // Only owner can manage categories
      //  $this->authorize('manage', $colocation);
         if (auth()->id() !== $colocation->owner_id) {
        abort(403, 'Unauthorized.');
    }
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $colocation->categories()->create([
            'name' => $request->name
        ]);

        return redirect()->back()->with('success', 'Category added.');
    }

    public function destroy(Colocation $colocation, Category $category)
    {
       // $this->authorize('manage', $colocation);
               if (auth()->id() !== $colocation->owner_id) {
        abort(403, 'Unauthorized.');
    }
        $category->delete();

        return redirect()->back()->with('success', 'Category removed.');
    }



}
