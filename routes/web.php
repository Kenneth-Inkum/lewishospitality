<?php

use Illuminate\Support\Facades\Route;

Route::livewire('/', 'pages::home')->name('home');

Route::livewire('/contact', 'pages::contact')->name('contact');
Route::livewire('/events', 'pages::events')->name('events');
Route::livewire('/events/{slug}', 'pages::event-detail')->name('events.show');
Route::livewire('/gallery', 'pages::gallery')->name('gallery');
Route::livewire('/about', 'pages::about')->name('about');
Route::livewire('/menu', 'pages::menu')->name('menu');
Route::livewire('/reservations', 'pages::reservations')->name('reservations');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::livewire('dashboard', 'admin::dashboard')->name('dashboard');
    
    // Admin Routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::livewire('reservations', 'admin::reservations.index')->name('reservations.index');
    });
});

require __DIR__.'/settings.php';
