<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/recipes', function () {
    return view('welcome');
});

Route::get('/resources', function () {
    return view('welcome');
});

Route::get('/about', function () {
    return view('welcome');
});

Route::post('/login', [AuthController::class, 'login']);

Route::get('/login', function () {
    // Handle login logic
    dd('Login route hit');
})->name('login');
