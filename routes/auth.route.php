<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VerificationController;

Route::middleware(['guest'])->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login.form');

    Route::post('/login', [AuthController::class, 'login'])->name('login');

    Route::get('/register', function () {
        return view('auth.register');
    })->name('register.form');

    Route::post('/register', [AuthController::class, 'register'])->name('register');

    Route::get('/login/apple', function () {
        // TODO: Decorate this
        // Restricted Apple login route until we get a proper setup. Current budget doesn't meet for it.
        return response('Service Unavailable', 503);
    });

    Route::get('/oauth/{provider}', [AuthController::class, 'redirectToProvider'])
        ->where('provider', 'facebook|apple|google')
        ->name('oauth.redirect');


    Route::get('/oauth/{provider}/callback', [AuthController::class, 'handleProviderCallback'])
        ->where('provider', 'facebook|apple|google')
        ->name('oauth.callback');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile/setup', function () {
        return view('profile.setup');
    })->middleware(['profile.notset'])
        ->name('profile.setup');

    Route::post('/profile/setup', [ProfileController::class, 'setup'])
        ->middleware('profile.notset')
        ->name('profile.setup.submit');

    Route::get('/email/verify', [VerificationController::class, 'index'])
        ->middleware('notverified')
        ->name('verification.notice');

    Route::post('/email/verification-notification', [VerificationController::class, 'requestNotification'])
        ->middleware(['throttle:6,1'])
        ->name('verification.send');

    Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
        ->middleware(['throttle:6,1'])
        ->withoutMiddleware('auth')
        ->name('verification.verify');
});

Route::middleware(['auth', 'verified', 'throttle:6,1'])->group(function () {
    Route::get('/recover-password', function () {
        return view('auth.recover-password');
    })->name('password.recover');

    Route::post('/recover-password', [PasswordController::class, 'requestRecovery'])
        ->name('password.recover');

    Route::get('/recover-password/submit', [PasswordController::class, 'showRecoverySubmitForm'])
        ->name('password.recover.submit');

    Route::post('/recover-password/submit', [PasswordController::class, 'reset'])
        ->name('password.recover.submit');

    Route::get('/change-password', function () {
        return view('auth.change-password');
    })->name('password.change');

    Route::post('/change-password', [PasswordController::class, 'changePassword'])
        ->name('password.change');
});
