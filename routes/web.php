<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\MedecinController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

    //     return Inertia::render('Welcome'
// Route::get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});






//Route pour admin
Route::middleware(['auth','role:admin'])->group(function () {
Route::get('/admin/dashboard',[AdminController::class, 'index'])->middleware('verified')->name('admin.dashboard');
    Route::get('admin/societes',[AdminController::class,'showSocietes'])->name('admin.societe.index');
    Route::post('admin/societes', [AdminController::class, 'storeSociete'])->name('admin.societe.store');
    Route::get('admin/societes/{societe}/edit', [AdminController::class, 'editSociete'])->name('admin.societe.edit');
    Route::delete('admin/societes/{societe}', [AdminController::class, 'deleteSociete'])->name('admin.societe.delete');
    Route::patch('admin/societes/{societe}', [AdminController::class, 'updateSociete'])->name('admin.societe.update');
    Route::get('admin/patients', [AdminController::class, 'showPatients'])->name('admin.patient.index');
    // Routes pour la gestion des patients
    Route::post('admin/patients', [AdminController::class, 'storePatient'])->name('admin.patient.store');
    Route::get('admin/patients/{patient}/edit', [AdminController::class, 'editPatient'])->name('admin.patient.edit');
    Route::patch('admin/patients/{patient}', [AdminController::class, 'updatePatient'])->name('admin.patient.update');
    Route::delete('admin/patients/{patient}', [AdminController::class, 'deletePatient'])->name('admin.patient.delete');
    // Routes pour les dossiers médicaux
    Route::get('admin/dossiers', [AdminController::class, 'showDossiers'])->name('admin.dossiers');
    // Route pour les paramètres
    Route::get('admin/parametres', [AdminController::class, 'showParametres'])->name('admin.parametres');
}
);

//Route pour medecin
Route::middleware(['auth','role:medecin'])->group(function () {
    Route::get('/medecin/dashboard', [MedecinController::class, 'index'])->name('medecin.dashboard');
    Route::get('medecin/patients', [MedecinController::class, 'patient'])->name('medecin.patients.index');
    Route::get('medecin/consultation', [MedecinController::class, 'consultation'])->name('medecin.consultation');
    Route::get('medecin/parametres', [MedecinController::class, 'parametre'])->name('medecin.parametre');
    });


require __DIR__.'/auth.php';
