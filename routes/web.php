<?php

use App\Http\Controllers\LienController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RedirectionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard.index');
// })->middleware(['auth', 'verified'])->name('dashboard.index');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [LienController::class, 'index'])->name('dashboard.index');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard/create', [LienController::class, 'create'])->name('dashboard.create');
    Route::post('/dashboard', [LienController::class, 'store'])->name('dashboard.store');
    Route::get('/dashboard/{lien}/edit', [LienController::class, 'edit'])->name('dashboard.edit');
    Route::put('/dashboard/{lien}', [LienController::class, 'update'])->name('dashboard.update');
    Route::delete('/dashboard/{lien}', [LienController::class, 'destroy'])->name('dashboard.destroy');
});

Route::get('/{code}', [RedirectionController::class, 'redirect'])
    ->where('code', '[A-Za-z0-9]{6}')
    ->name('redirection');

Route::get('/lien-expire', function () {
    return view('errors.lien-expire');
})->name('lien-expire');

require __DIR__.'/auth.php';
