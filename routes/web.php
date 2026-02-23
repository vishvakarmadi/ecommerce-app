<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SSOController;

// Welcome page with SSO login button
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
})->name('home');

// SSO Routes
Route::get('/login', function () {
    return view('welcome');
})->name('login');
Route::get('/sso/foodpanda', [SSOController::class, 'redirectToFoodpanda'])->name('sso.foodpanda');
Route::get('/sso/callback', [SSOController::class, 'handleCallback'])->name('sso.callback');

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::post('/logout', [SSOController::class, 'logout'])->name('logout');
});
