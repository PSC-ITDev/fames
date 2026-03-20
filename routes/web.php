<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AssetController;
 
Route::get('/', function () {
    return redirect()->route('login');
});

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');


Route::group(['prefix' => '', 'middleware' => 'auth'], function () {
    
    Route::get('/newasset', [AssetController::class, 'newasset'])
        ->name('new-asset');

});


require __DIR__.'/auth.php';
