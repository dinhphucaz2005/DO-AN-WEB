<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MemeController;

Route::get('/', [MemeController::class, 'editor'])->name('home');
Route::get('/meme-editor', [MemeController::class, 'editor'])->name('meme.editor');
Route::get('/gif-creator', [MemeController::class, 'gifCreator'])->name('gif.creator');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/memes', [MemeController::class, 'index'])->name('memes.index');
    Route::post('/memes', [MemeController::class, 'store'])->name('memes.store');
    Route::post('/memes/save-image', [MemeController::class, 'saveImage'])->name('memes.saveImage');
    Route::get('/memes/{id}', [MemeController::class, 'show'])->name('memes.show');
    Route::delete('/memes/{id}', [MemeController::class, 'destroy'])->name('memes.destroy');
});

require __DIR__.'/auth.php';
