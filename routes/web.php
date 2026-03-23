<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\DashboardController;
 
Route::get('/', function () {
    return redirect()->route('login');
});

// Route::view('dashboard', 'dashboard')
//     ->middleware(['auth', 'verified'])
//     ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');


Route::group(['prefix' => '', 'middleware' => 'auth'], function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/newasset', [AssetController::class, 'newasset'])
        ->name('new-asset');

    Route::post('saveasset',[AssetController::class, 'saveasset'])
        ->name('saveasset');

});


require __DIR__.'/auth.php';
