<?php

use App\Http\Controllers\BoardController;
use App\Http\Controllers\CommentController;
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
Route::get('/boards/{board:slug}', [BoardController::class, 'show'])->name('boards.show');

Route::scopeBindings()->group(function () {
    Route::middleware('auth')->group(function () {
        Route::get('/boards/{board:slug}/create', [PostController::class, 'create'])->name('posts.create');
        Route::post('/boards/{board:slug}', [PostController::class, 'store'])->name('posts.store');
        Route::get('/boards/{board:slug}/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
        Route::patch('/boards/{board:slug}/{post}', [PostController::class, 'update'])->name('posts.update');
        Route::delete('/boards/{board:slug}/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
        Route::post('/uploads/posts/images', [PostAttachmentUploadController::class, 'storeImage'])
            ->middleware('throttle:10,1')
            ->name('posts.images.store');


        //댓글    
        Route::post('/boards/{board:slug}/{post}/comments', [CommentController::class, 'store'])
        ->name('comments.store');

        Route::patch('/boards/{board:slug}/{post}/comments/{comment}', [CommentController::class, 'update'])
        ->name('comments.update');          

        Route::delete('/boards/{board:slug}/{post}/comments/{comment}', [CommentController::class, 'destroy'])
        ->name('comments.destroy');        
            
    });

    Route::get('/boards/{board:slug}/{post}', [PostController::class, 'show'])->name('posts.show');
});

require __DIR__.'/auth.php';
