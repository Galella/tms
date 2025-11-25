<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TruckInController;
use App\Http\Controllers\TruckOutController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Truck IN routes
Route::middleware(['auth', 'terminal.access'])->group(function () {
    Route::resource('truck-in', TruckInController::class)->except(['show', 'edit', 'update']);
    Route::resource('truck-out', TruckOutController::class)->except(['show', 'edit', 'update']);
});

// Basic dashboard route
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard.index');
    })->name('dashboard');
});
