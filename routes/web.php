<?php

use App\Http\Controllers\BloodPressureController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BloodPressureController::class, 'index'])->middleware(['auth', 'verified'])->name('blood-pressure.index');
Route::get('/blood-pressure/create', [BloodPressureController::class, 'create'])->middleware(['auth', 'verified'])->name('blood-pressure.create');
Route::post('/blood-pressure/store', [BloodPressureController::class, 'store'])->middleware(['auth', 'verified'])->name('blood-pressure.store');
Route::get('/blood-pressure/{id}/edit', [BloodPressureController::class, 'edit'])->middleware(['auth', 'verified'])->name('blood-pressure.edit');
Route::put('/blood-pressure/{id}', [BloodPressureController::class, 'update'])->middleware(['auth', 'verified'])->name('blood-pressure.update');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
