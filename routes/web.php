<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/recipes', function () {
    return view('recipe');
});

Route::get('/resources', function () {
    return view('welcome');
});

Route::get('/about', function () {
    return view('welcome');
});

Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);

Route::get('/login', function () {
    // Handle login logic
})->name('login');

Route::get('/logout', [AuthController::class, 'logout']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
