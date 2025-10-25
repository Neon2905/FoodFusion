<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/profile/setup', [ProfileController::class, 'setupForm'])->middleware(['profile.notset'])
        ->name('profile.setup');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/profile/{user:username}', [ProfileController::class, 'view'])->name('profile.view');
});
