<?php

use App\Http\Controllers\SuperAdminController;
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


Route::get('/forgot_password', [UserController::class, 'V_forgot_password'])->name('forgot_password');
Route::post('/forgot_password_checker', [UserController::class, 'forgot_password_verifier'])->name('forgot_password_checker');


Route::get('/reset-password/{token}', function (string $token) {
    return Inertia::render('Auth/ResetPassword', [
        'token' => $token,
        'email' => request()->query('email')
    ]);
})->name('password.reset');

Route::post('/reset-password', [UserController::class, 'reset_password'])
    ->name('password.update');

Route::get('/verify-email/{token}', [UserController::class, 'verifyEmail'])->name('verifyToken');



Route::post('/logout', [UserController::class, 'logout'])->name('logout');




Route::get('/redirect-by-role', function () {
    // Le middleware fera la redirection
})->middleware(['auth', 'redirect.by.role'])->name('role.redirect');


Route::middleware(['auth'])->group(function () {

    // Super Admin
    Route::get('/SuperAdmin', [SuperAdminController::class, 'V_Dashboard'])->name('SuperAdmin.Home');
    Route::get('/SuperAdmin/create', [SuperAdminController::class, 'V_create_admin'])->name('SuperAdmin.Create');
    Route::post('/SuperAdmin/store', [SuperAdminController::class, 'store'])->name('SuperAdmin.store');
    Route::put('/super-admin/{admin}', [SuperAdminController::class, 'update'])->name('SuperAdmin.update');
Route::delete('/super-admin/{admin}', [SuperAdminController::class, 'destroy'])->name('SuperAdmin.destroy');

    // Admin Centre de Santé
    Route::get('/AdminCS', fn() => Inertia::render('Admin/Admin_Home'))->name('AdminCS.Home');

    // Médecin
    Route::get('/Medecin', fn() => Inertia::render('Medecin/Home'))->name('Medecin.Home');

    // Secrétaire
    Route::get('/Secretaire', fn() => Inertia::render('Secretaire/Home'))->name('Secretaire.Home');

    // Laborantin
    Route::get('/Laborantin', fn() => Inertia::render('Laborantin/Home'))->name('Laborantin.Home');
});
