<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Home');
})->name("Home");

Route::get('/test', function () {
    return Inertia::render('Test');
})->name("Test");

Route::get('/login', function () {
    return Inertia::render('Auth/Login');
})->name('login');
