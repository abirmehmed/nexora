<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/@{username}', [ProfileController::class, 'show'])->name('profile.show');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/feed', [App\Http\Controllers\FeedController::class, 'index'])->name('feed');
});

require __DIR__.'/auth.php';

use App\Http\Controllers\FollowController;
use App\Http\Controllers\BlockController;

Route::middleware('auth')->group(function () {
    Route::post('/users/{user}/follow', [FollowController::class, 'store'])->name('users.follow');
    Route::delete('/users/{user}/follow', [FollowController::class, 'destroy'])->name('users.unfollow');
    Route::post('/users/{user}/block', [BlockController::class, 'store'])->name('users.block');
    Route::delete('/users/{user}/block', [BlockController::class, 'destroy'])->name('users.unblock');
});

use App\Http\Controllers\PostController;

Route::resource('posts', PostController::class)->except(['show'])->middleware('auth');
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');

use App\Http\Controllers\CommentController;

Route::middleware('auth')->group(function () {
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});
