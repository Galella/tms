<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TruckInController;
use App\Http\Controllers\TruckOutController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ContainerController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// User Management routes
Route::middleware(['auth', 'permission:manage-users'])->group(function () {
    Route::resource('users', UserController::class);
});

// Container Management routes
Route::middleware(['auth', 'permission:manage-containers'])->group(function () {
    Route::resource('containers', ContainerController::class);
});

// Terminal Management routes
Route::middleware(['auth', 'permission:manage-terminals'])->group(function () {
    Route::resource('terminals', TerminalController::class);
});

// Active Inventory routes
Route::middleware(['auth', 'permission:manage-inventory'])->group(function () {
    Route::resource('active-inventory', ActiveInventoryController::class);
});

// Truck IN routes
Route::middleware(['auth', 'terminal.access'])->group(function () {
    Route::resource('truck-in', TruckInController::class)->except(['show', 'edit', 'update']);
    Route::resource('truck-out', TruckOutController::class)->except(['show', 'edit', 'update']);
});

// Basic dashboard route
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});
