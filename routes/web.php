<?php

use App\Http\Controllers\BoardController;
use App\Http\Controllers\PostAttachmentUploadController;
use App\Http\Controllers\PostController;
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

Route::middleware('auth')->group(function () {
    Route::get('/boards/{board:slug}/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/boards/{board:slug}/posts', [PostController::class, 'store'])->name('posts.store');
    Route::post('/uploads/posts/images', [PostAttachmentUploadController::class, 'storeImage'])
        ->middleware('throttle:10,1')
        ->name('posts.images.store');
});

Route::get('/boards/{board:slug}/posts/{post}', [PostController::class, 'show'])->name('posts.show');

require __DIR__.'/auth.php';
