<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

use App\Http\Controllers\RecipeController;
use App\Http\Controllers\AuthController;

Route::get('/test', function() {
    return view('recipe');
});


Route::get('/', function () {
    return view('welcome');
});

Route::get('/recipes', [RecipeController::class, 'index']);
Route::get('/recipes/{slug}', [RecipeController::class, 'show'])->name('recipes.show');


Route::get('/resources', function () {
    return view('welcome');
});

Route::get('/about', function () {
    return view('welcome');
});

Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);

Route::get('/login', function () {
    // return view('auth.login');
})->name('login');

Route::get('/logout', [AuthController::class, 'logout']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// Show verification notice
Route::get('/email/verify', function (Request $request) {
    // $request->user()->sendEmailVerificationNotification();
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');


// Resend verification email
Route::post('/email/verification-notification', function (Request $request) {
    // $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Handle verification link from email
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/profile'); // or wherever you want to redirect after verification
})->middleware(['auth', 'signed'])->name('verification.verify');

// Route::middleware(['auth', 'verified'])->group(function () {
//     Route::get('/profile', [ProfileController::class, 'show']);
//     // ...other protected routes...
// });
