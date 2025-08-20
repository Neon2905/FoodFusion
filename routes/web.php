<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/login', function () {
    // Handle login logic'
    dd('Login route hit');
});

Route::get('/login', function () {
    // Handle login logic
    dd('Login route hit');
})->name('login');
