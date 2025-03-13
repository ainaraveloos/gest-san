<?php

namespace App\Http\Controllers;

use App\Models\Societe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Patient;
use Illuminate\Validation\Rule;


use Inertia\Inertia;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render("Admin/dashboard");
    }
    public function showSocietes(  )
    {
         // Récupérer toutes les sociétés avec le compte des patients
        $societes = Societe::withCount('patients')->get();
        return Inertia::render('Admin/Societes/Index', [
        'societes' => $societes,
        ]);
    }
    public function storeSociete(Request $request)
    {
        // Valider les données du formulaire
        $validatedData = $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:societes,email',
            'description' => 'required|string',
        ]);

        // Ajout de la date d'adhésion (ici, la date du jour)
        $validatedData['date_adhesion'] = now();

        // Création de la société
        Societe::create($validatedData);

        // Redirection vers la liste des sociétés avec un message de succès
        return to_route('admin.societe.index')
                         ->with('success', 'Société ajoutée avec succès');
    }
    public function editSociete(Societe $societe)
    {
        return Inertia::render('Admin/Societes/Edit', [
            'societe' => $societe
        ]);
    }

    public function deleteSociete(Societe $societe)
    {
        $societe->delete();
        return to_route('admin.societe.index')
                        ->with('success', 'Société supprimée avec succès');
    }
    public function updateSociete(Request $request, Societe $societe)
    {
        $validatedData = $request->validate([
            'nom' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('societes')->ignore($societe->id)],
            'description' => 'required|string',
        ]);

        $societe->update($validatedData);

        return to_route('admin.societe.index')
                ->with('success', 'Société mise à jour avec succès');
    }
    public function showPatients(Request $request )
    {

    // Extraire les filtres depuis la requête
    $filters = $request->only(['search', 'type', 'societe']);

    // Construction de la requête avec eager loading
    $patients = Patient::with(['societe', 'salarie'])
    ->orderBy('created_at', 'desc')
    ->filter($filters)
    ->paginate(5)
    ->withQueryString();

    return Inertia::render('Admin/Patients/Index', [
        'patients' => $patients,
        'societes' => Societe::all(),
        'filters' => $filters,
        'salaries' => Patient::where('type', 'SALARIE')->get(),
    ]);
    }
    public function storePatient(Request $request)
    {
        $validatedData = $request->validate([
            'type' => 'required|in:SALARIE,FAMILLE',
            'parent_id' => 'required_if:type,FAMILLE|nullable|exists:patients,id',
            'lien_parente' => 'required_if:type,FAMILLE',
            'societe_id' => 'required_if:type,SALARIE|nullable|exists:societes,id',
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'date_naissance' => 'required|date',
            'adresse' => 'required|string',
            'telephone' => 'required_if:type,SALARIE|string|nullable',
            'email' => 'required_if:type,SALARIE|email|nullable',
            'poste' => 'required_if:type,SALARIE|nullable|string'
        ]);
    // Si le patient est de type FAMILLE, on récupère la société du salarié référent
    if ($validatedData['type'] === 'FAMILLE') {
        $parentPatient = Patient::find($validatedData['parent_id']);
        if ($parentPatient && $parentPatient->societe_id) {
            $validatedData['societe_id'] = $parentPatient->societe_id;
        }}

        $validatedData['date_naissance'] = date('Y-m-d', strtotime($validatedData['date_naissance']));

        // Générer un numéro unique pour le patient
        $validatedData['numero'] = 'PAT' . str_pad(Patient::max('id') + 1, 6, '0', STR_PAD_LEFT);
        $validatedData['statut_adhesion'] = 'active';

        Patient::create($validatedData);

        return redirect()->route('admin.patient.index')
                        ->with('success', 'Patient ajouté avec succès');
    }
public function deletePatient(Request $request, Patient $patient)
{
    // Supprimer le patient
    $patient->delete();

    return redirect()->route('admin.patient.index')->with('success', 'Patient supprimé avec succès');
}

public function showDossiers(Request $request, Patient $patient)
{
    return Inertia::render('Admin/dossier');
}
public function showParametres(Request $request, Patient $patient)
{
    return Inertia::render('Admin/parametre');
}
}
?>
