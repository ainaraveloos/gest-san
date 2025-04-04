<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\MedecinController;
use App\Http\Controllers\Auth\RegisteredUserController;


use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();

        return match($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'medecin' => redirect()->route('medecin.dashboard'),
            default => redirect()->route('login')
        };
    }

    return redirect()->route('login');
})->name('home');

    //     return Inertia::render('Welcome'
// Route::get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});






//Route pour User admin
Route::middleware(['auth','role:admin'])->group(function () {
    //Routes affichage de l'admin dashboard
    Route::get('/admin/dashboard',[AdminController::class, 'index'])->middleware('verified')->name('admin.dashboard');
    //Routes pour la gestion des sociétés
    Route::get('admin/societes',[AdminController::class,'showSocietes'])->name('admin.societe.index');
    Route::post('admin/societes', [AdminController::class, 'storeSociete'])->name('admin.societe.store');
    Route::get('admin/societes/{societe}/edit', [AdminController::class, 'editSociete'])->name('admin.societe.edit');
    Route::delete('admin/societes/{societe}', [AdminController::class, 'deleteSociete'])->name('admin.societe.delete');
    Route::patch('admin/societes/{societe}', [AdminController::class, 'updateSociete'])->name('admin.societe.update');
    //Liste des medecins
    Route::get('admin/medecins_list',[AdminController::class,'medecinsList'])->name('medecins.list');
    // Routes pour la gestion des patients
    Route::get('admin/patients', [AdminController::class, 'showPatients'])->name('admin.patient.index');
    Route::post('admin/patients', [AdminController::class, 'storePatient'])->name('admin.patient.store');

    // Routes pour la gestion des badges
    Route::post('admin/badge/renew', [AdminController::class, 'renewBadge'])->name('admin.badge.renew');
    Route::post('admin/badge/check-expired', [AdminController::class, 'checkExpiredBadges'])->name('admin.badge.checkExpired');

    Route::get('admin/patient/{patient}',[AdminController::class,'patientDossier'])->name('admin.patient.view');
    Route::patch('admin/patients/{patient}', [AdminController::class, 'updatePatient'])->name('admin.patient.update');
    Route::delete('admin/patients/{patient}', [AdminController::class, 'deletePatient'])->name('admin.patient.delete');
    Route::get('admin/salaries/search', [AdminController::class, 'searchSalaries'])->name('admin.salaries.search');
    // Route pour les paramètres
    Route::get('admin/parametres', [AdminController::class, 'showParametres'])->name('admin.parametres');
    Route::post('user/store', [AdminController::class, 'storeUser'])->name('register.store');
}
);

//Route pour User medecin
Route::middleware(['auth','role:medecin'])->group(function () {
    Route::get('/medecin/dashboard', [MedecinController::class, 'index'])->name('medecin.dashboard');
    Route::get('medecin/dossier_medical', [MedecinController::class, 'patientDossier'])->name('patient.dossier');
Route::get('/patients/{id}/dossier', [MedecinController::class, 'showDossier'])->name('patient.dossier.details');
    Route::get('medecin/consultation/create', [MedecinController::class, 'createConsultation'])->name('medecin.consultation.create');
    Route::get('medecin/patients/search', [MedecinController::class, 'searchPatients'])->name('patients.search');
    Route::post('medecin/consultation', [MedecinController::class, 'store'])->name('consultations.store');
    Route::get('medecin/parametres', [MedecinController::class, 'parametre'])->name('medecin.parametre');
    Route::post('medecin/parametres/update', [MedecinController::class, 'updateParameters'])->name('medecin.parametre.update');
    Route::delete('medecin/consultations/{consultation}', [MedecinController::class, 'destroyConsultation'])->name('consultation.destroy');
    Route::patch('medecin/consultations/{consultation}', [MedecinController::class, 'updateConsultation'])->name('consultation.update');
    });



require __DIR__.'/auth.php';
