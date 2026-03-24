<?php

use Illuminate\Support\Facades\Route;

Route::livewire('/', 'pages::home')->name('home');

Route::livewire('/contact', 'pages::contact')->name('contact');
Route::livewire('/events', 'pages::events')->name('events');
Route::livewire('/events/{slug}', 'pages::event-detail')->name('events.show');
Route::livewire('/gallery', 'pages::gallery')->name('gallery');
Route::livewire('/about', 'pages::about')->name('about');
Route::livewire('/menu', 'pages::menu')->name('menu');

// PDF menu download
Route::get('/menu.pdf', function () {
    // TODO: Implement PDF generation with barryvdh/laravel-dompdf or spatie/laravel-pdf
    abort(501, 'PDF menu generation not yet implemented');
})->name('menu.pdf');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
