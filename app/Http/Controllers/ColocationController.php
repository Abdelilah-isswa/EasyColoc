<?php

namespace App\Http\Controllers;

use App\Models\Colocation;
use Illuminate\Http\Request;

class ColocationController extends Controller
{
    public function show(Colocation $colocation)
    {
        $colocation->load([
            'owner',
            'members',
            'expenses.payeur',
            'expenses.category'
        ]);

        return view('colocations.show', compact('colocation'));
    }
}