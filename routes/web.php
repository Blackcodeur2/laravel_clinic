<?php

use App\Http\Controllers\ClinicSettingController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FactureController;
use App\Http\Controllers\MedicamentController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ServiceMedicalController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerificationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Patients
    Route::resource('patients', PatientController::class)->except(['destroy']);

    // Consultations
    Route::resource('consultations', ConsultationController::class);

    // Services médicaux
    Route::resource('services', ServiceMedicalController::class);

    // Médicaments
    Route::resource('medicaments', MedicamentController::class);

    // Factures
    Route::resource('factures', FactureController::class)->except(['edit', 'update']);
    Route::post('factures/{facture}/lignes', [FactureController::class, 'addLigne'])->name('factures.lignes.store');
    Route::delete('factures/{facture}/lignes/{ligne}', [FactureController::class, 'removeLigne'])->name('factures.lignes.destroy');
    Route::get('factures/{facture}/pdf', [FactureController::class, 'pdf'])->name('factures.pdf');

    // Paiements
    Route::post('factures/{facture}/paiements', [PaiementController::class, 'store'])->name('paiements.store');
    Route::delete('paiements/{paiement}', [PaiementController::class, 'destroy'])->name('paiements.destroy');
    Route::get('paiements/{paiement}/recu', [PaiementController::class, 'recu'])->name('paiements.recu');

    // Utilisateurs (admin only)
    Route::resource('users', UserController::class);

    // Paramètres de la clinique (admin only)
    Route::get('settings', [ClinicSettingController::class, 'edit'])->name('settings.edit');
    Route::put('settings', [ClinicSettingController::class, 'update'])->name('settings.update');

    // Rapports d'activité
    Route::get('reports', [ReportController::class, 'monthlyReport'])->name('reports.monthly');
    Route::get('reports/pdf', [ReportController::class, 'pdf'])->name('reports.pdf');
});

require __DIR__.'/auth.php';

// Public QR code verification (no auth required)
Route::get('/verify/facture/{token}', [VerificationController::class, 'facture'])->name('verify.facture');
Route::get('/verify/recu/{token}', [VerificationController::class, 'recu'])->name('verify.recu');
