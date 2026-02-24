<?php

namespace App\Http\Controllers;

use App\Services\CreateColocationService;
use Illuminate\Http\Request;

class CreateColocationController extends Controller
{
    public function __invoke(Request $request, CreateColocationService $service)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255']
        ]);

        $colocation = $service->handle($data);

        return redirect()->route('colocations.show', $colocation);
    }
}
