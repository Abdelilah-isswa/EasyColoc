<?php
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ColocationController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettlementController;
use App\Http\Controllers\HomeController;


Route::get('/register/{token?}', [RegisteredUserController::class, 'showRegistrationForm'])
     ->middleware('guest')
     ->name('register.token');

Route::post('/register', [RegisteredUserController::class, 'store'])
     ->middleware('guest')
     ->name('register');

Route::get('/invitations/{token}/accept', [InvitationController::class, 'acceptForm'])
    ->name('invitations.acceptForm');


Route::middleware('auth')->group(function () {
    
   
   
    Route::get('/', [HomeController::class, 'index'])
        ->middleware('verified')
        ->name('home');
    
    
  
    
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    
   
    
    Route::get('/colocations/create', [ColocationController::class, 'create'])->name('colocations.create');
    Route::post('/colocations', [ColocationController::class, 'store'])->name('colocations.store');
    Route::get('/my-colocations', [ColocationController::class, 'myColocations'])->name('colocations.my');
    Route::get('/colocations/{colocation}', [ColocationController::class, 'show'])->name('colocations.show');
    Route::get('/colocations/{colocation}/statistics', [ColocationController::class, 'statistics'])->name('colocations.statistics');
    Route::patch('/colocations/{colocation}/cancel', [ColocationController::class, 'cancel'])->name('colocations.cancel');
    Route::post('/colocations/{colocation}/leave', [ColocationController::class, 'leave'])->name('colocations.leave');
    Route::delete('/colocations/{colocation}/members/{user}', [ColocationController::class, 'removeMember'])->name('colocations.members.remove');

    
    
    
    Route::post('/colocations/{colocation}/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::delete('/colocations/{colocation}/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

   
    Route::get('/colocations/{colocation}/expenses/create', [ExpenseController::class, 'create'])->name('expenses.create');
    Route::post('/colocations/{colocation}/expenses', [ExpenseController::class, 'store'])->name('expenses.store');
    Route::post('/colocations/{colocation}/expenses/{expense}/pay', [ExpenseController::class, 'pay'])->name('expenses.pay');
    Route::get('/colocations/{colocation}/expenses', [ExpenseController::class, 'history'])->name('colocations.expenses.history');

  
    Route::post('/colocations/{colocation}/invite', [InvitationController::class, 'send'])->name('invitations.send');
    Route::post('/invitations/{token}/accept', [InvitationController::class, 'accept'])->name('invitations.accept');
    Route::post('/invitations/{token}/decline', [InvitationController::class, 'decline'])->name('invitations.decline');

    
    Route::patch('/settlements/{settlement}/pay', [SettlementController::class, 'markAsPaid'])->name('settlements.markAsPaid');

    
    Route::middleware('admin')->group(function () {
        
        Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
        Route::post('/admin/users/{user}/ban', [UserController::class, 'ban'])->name('admin.users.ban');
        Route::post('/admin/users/{user}/unban', [UserController::class, 'unban'])->name('admin.users.unban');
        
        
        Route::get('/admin/colocations', [ColocationController::class, 'allColocations'])->name('admin.colocations.index');
    });
});

require __DIR__.'/auth.php';
