<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordRecoveryController;
use App\Http\Controllers\VerificationController;

Route::get('/login', function () {
    return view('auth.login');
})->name('login.form');

Route::get('/register', function () {
    return view('auth.register');
})->name('register.form');

Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/login/apple', function () {
    // TODO: Decorate this
    // Restricted Apple login route until we get a proper setup. Current budget doesn't meet for it.
    return response('Service Unavailable', 503);
});

Route::get('/login/{provider}', [AuthController::class, 'redirectToProvider'])
    ->where('provider', 'facebook|apple|google')
    ->name('oauth.redirect');

Route::get('/auth/{provider}/callback', [AuthController::class, 'handleProviderCallback'])
    ->where('provider', 'facebook|apple|google')
    ->name('oauth.callback');

Route::get('/email/verify', [VerificationController::class, 'index'])
    ->middleware('auth')
    ->name('verification.notice');

Route::post('/email/verification-notification', [VerificationController::class, 'requestNotification'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');

Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::get('/recover-password', [PasswordRecoveryController::class, 'showRecoveryForm'])->name('password.recover');

Route::post('/recover-password', [PasswordRecoveryController::class, 'requestRecovery'])->name('password.recover');

Route::get('/reset-password', [PasswordRecoveryController::class, 'showResetForm'])->name('password.reset');

Route::post('/reset-password', [PasswordRecoveryController::class, 'reset'])->name('password.update');
