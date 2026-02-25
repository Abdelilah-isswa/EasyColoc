<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ColocationController;
use App\Http\Controllers\CreateColocationController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ExpenseController;
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



Route::middleware('auth')->group(function () {

    Route::get('/colocations/create', [ColocationController::class, 'create'])
        ->name('colocations.create');

    Route::post('/colocations', [ColocationController::class, 'store'])
        ->name('colocations.store');

    Route::get('/colocations/{colocation}', [ColocationController::class, 'show'])
        ->name('colocations.show');

});
Route::middleware('auth')->group(function () {
    Route::post('/colocations/{colocation}/categories', [CategoryController::class, 'store'])
        ->name('categories.store');

    Route::delete('/colocations/{colocation}/categories/{category}', [CategoryController::class, 'destroy'])
        ->name('categories.destroy');
});
 
Route::middleware('auth')->group(function () {
    Route::get('/colocations/{colocation}/categories', [CategoryController::class, 'index'])
        ->name('categories.index');
    Route::post('/colocations/{colocation}/categories', [CategoryController::class, 'store'])
        ->name('categories.store');
    Route::delete('/colocations/{colocation}/categories/{category}', [CategoryController::class, 'destroy'])
        ->name('categories.destroy');
});
// routes/web.php
Route::middleware('auth')->group(function () {
    Route::get('/my-colocations', [ColocationController::class, 'myColocations'])
        ->name('colocations.my');
});
Route::post('/colocations/{colocation}/expenses', [ExpenseController::class, 'store'])
    ->name('expenses.store');
Route::middleware('auth')->group(function () {
    Route::get('/colocations/{colocation}/expenses/create', [ExpenseController::class, 'create'])
        ->name('expenses.create');
    Route::post('/colocations/{colocation}/expenses', [ExpenseController::class, 'store'])
        ->name('expenses.store');
});

require __DIR__.'/auth.php';   