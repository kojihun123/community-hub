<?php

use App\Http\Controllers\AdminReportController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PopularPostController;
use App\Http\Controllers\PostAttachmentUploadController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostLikeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
    Route::patch('/reports/{report}', [AdminReportController::class, 'update'])->name('reports.update');
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
        
        //좋아요
        Route::post('/boards/{board:slug}/{post}/likes', [PostLikeController::class, 'store'])
        ->name('posts.likes.store');

        //신고
        Route::post('/boards/{board:slug}/{post}/reports', [ReportController::class, 'store'])
        ->name('posts.reports.store');
            
    });

    Route::get('/popular', [PopularPostController::class, 'index'])->name('popular');

    Route::get('/boards/{board:slug}/{post}', [PostController::class, 'show'])->name('posts.show');
});

require __DIR__.'/auth.php';
