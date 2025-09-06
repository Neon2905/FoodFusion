<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;

use App\Mail\Welcome;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('send:welcome-email {user}', function ($user) {
    // Logic to send the welcome email to the user
    Mail::to($user)->send(new Welcome());
})->purpose('Send a welcome email to a user');

Artisan::command('test', function () {
    $author = \App\Models\Recipe::first()->author;
    $author->recipies()->create([
        'title' => 'Quidem non mollitia eum.',
        'description' => 'This is a sample recipe description.',
        // Add other required fields here
    ]);
    $this->info('Recipe created for author: ' . $author->name);
})->purpose('Testing');