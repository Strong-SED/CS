<?php

use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Routes publiques
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});


Route::get('/login', [UserController::class, 'V_Login'])->name('login');

Route::post('/login_checker', [UserController::class, 'login_verifier'])->name('Login_verifier');

Route::post('/logout', [UserController::class, 'logout'])->name('logout');

Route::get('/redirect-by-role', function () {
    // Le middleware fera la redirection
})->middleware(['auth', 'redirect.by.role'])->name('role.redirect');



Route::middleware(['auth'])->group(function () {

    // Super Admin
    Route::get('/SuperAdmin', fn() => Inertia::render('SuperAdmin/Home'))->name('SuperAdmin.Home');

    // Admin Centre de Santé
    Route::get('/AdminCS', fn() => Inertia::render('Admin/Home'))->name('AdminCS.Home');

    // Médecin
    Route::get('/Medecin', fn() => Inertia::render('Medecin/Home'))->name('Medecin.Home');

    // Secrétaire
    Route::get('/Secretaire', fn() => Inertia::render('Secretaire/Home'))->name('Secretaire.Home');

    // Laborantin
    Route::get('/Laborantin', fn() => Inertia::render('Laborantin/Home'))->name('Laborantin.Home');
});
