<?php

use App\Http\Controllers\BoardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/boards', [BoardController::class, 'index'])->name('boards.index');
Route::get('/boards/{slug}', [BoardController::class, 'show'])->name('boards.show');

require __DIR__.'/auth.php';
