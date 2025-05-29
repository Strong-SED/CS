<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\DossierMedicalController;
use App\Http\Controllers\MedecinController;
use App\Http\Controllers\RendezVousController;
use App\Http\Controllers\SecretaireController;
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
    Route::middleware(['auth', 'checkAdmin'])->group(function () {
        Route::get('/AdminCS', [AdminController::class, 'V_Dashboard'])->name('AdminCS.Home');
        Route::get('/AdminCS/create', [AdminController::class, 'V_create'])->name('AdminCS.Create');
        Route::post('/AdminCS/store', [AdminController::class, 'store'])->name('AdminCS.store');
        Route::put('/AdminCS/{user}', [AdminController::class, 'update'])->name('AdminCS.update');
        Route::delete('/AdminCS/{user}', [AdminController::class, 'destroy'])->name('AdminCS.destroy');
    });

    Route::get('/AdminCS/CS', [AdminController::class, 'V_CentreDS'])
        ->middleware('auth')
        ->name('AdminCS.Centre');
    Route::post('/AdminCS/CS/create', [AdminController::class, 'CS_store'])
        ->middleware('auth')
        ->name('CS.Create');




    // Médecin
    Route::get('/Medecin',[MedecinController::class , 'Home'])->name('Medecin.Home');
    Route::get('/Medecin/InfosPatient',[MedecinController::class , 'InfosPatient'])->name('Medecin.Patients');
    Route::get('/Medecin/Historiques_Consultation' , [MedecinController::class , 'V_Historique'])->name('Medecin.Historique');
    Route::post('/consultations/complete', [ConsultationController::class, 'completeConsultation'])->name('consultations.complete');
    Route::get('/Medecin/Rdv' , [RendezVousController::class , 'Rdv_VM'])->name('Medecin.Rdv');
    Route::post('/rdvs', [RendezVousController::class, 'store'])->name('rdvs.store');
    Route::put('/rdvs/{rendezVous}', [RendezVousController::class, 'update'])->name('rdvs.update');

    // Secrétaire
    Route::get('/Secretaire/Home', [SecretaireController::class , 'Home'])->name('Secretaire.Home');
    Route::get('/Secretaire/Dashboard', [SecretaireController::class , 'V_Dashboard'])->name('Secretaire.Dashboard');
    Route::get('/Secretaire/CreateP', [SecretaireController::class , 'V_CreateP'])->name('Secretaire.CreateP');
    Route::post('/Secretaire/Store', [SecretaireController::class, 'store'])->name('Secretaire.StoreP');
    Route::put('/Secretaire/Update/{id}', [SecretaireController::class, 'update'])->name('Secretaire.UpdateP');
    Route::delete('/Secretaire/{id}/Delete', [SecretaireController::class, 'destroy'])->name('Secretaire.DeleteP');
    Route::post('/dossiers-medicaux', [DossierMedicalController::class, 'store'])->name('dossiers-medicaux.store');
    Route::post('/consultations', [ConsultationController::class, 'store'])->name('consultations.store');

    Route::get('/Secretaire/Rdv' , [RendezVousController::class , 'Rdv_V'])->name('Secretaire.Rdv');

    // Visualisation du dossier médical
    Route::get('/Patients/{id}/Dossier', [SecretaireController::class, 'showMedicalRecord'])->name('Secretaire.ShowDossier');


    // Laborantin
    Route::get('/Laborantin', fn() => Inertia::render('Laborantin/Home'))->name('Laborantin.Home');
});
