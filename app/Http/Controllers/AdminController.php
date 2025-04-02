<?php

namespace App\Http\Controllers;

use App\Models\Societe;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;
use App\Models\Patient;
use App\Models\User;
use App\Models\Medecin;
use App\Models\Badge;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
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
        // On démarre la query avec le eager loading
    $query = Patient::with(['societe', 'salarie','badge']);

    // Filtrer par nom, prénom ou numéro
    if ($request->filled('search')) {
        $search = $request->input('search');
        $query->where(function ($q) use ($search) {
            $q->where('nom', 'like', "%{$search}%")
              ->orWhere('prenom', 'like', "%{$search}%")
              ->orWhere('numero', 'like', "%{$search}%");
        });
    }

    // Filtrer par société
   if ($request->filled('societes')) {
    $query->whereIn('societe_id', $request->input('societes'));
}

    // Filtrer par type de patient (SALARIE ou FAMILLE)
    if ($request->filled('types')) {
        $query->whereIn('type', $request->input('types'));
    }

    $patients = $query->orderBy('created_at', 'desc')->paginate(4)->appends($request->query());

    return Inertia::render('Admin/Patients/Index', [
        'filters'   => $request->only(['search', 'societes', 'types']), // Changer 'type' en 'types'
        'patients'  => $patients,
        'societes'  => Societe::all(),
        'salaries'  => Patient::where('type', 'SALARIE')->get(),
    ]);
    }
    public function patientDossier($id)
    {

        // Récupère le patient avec toutes ses consultations et les relations associées
    $patient = Patient::with([
        'consultations.medecin',
        'consultations.ordonnance',
        'consultations.demande_examen',
        'consultations.lettre_reference',
        'societe',
        'salarie'
    ])->withCount('consultations')->find($id);
        // Récupère toutes les consultations du patient
    $consultations = $patient->consultations;

    // Compte le nombre total de dossiers associés à toutes les consultations
    $totalDossiers = $consultations->reduce(function ($carry, $consultation) {
        return $carry + (bool) $consultation->ordonnance
            + (bool) $consultation->demande_examen
            + (bool) $consultation->lettre_reference;
    }, 0);

    // Récupère la date de la dernière consultation
    $lastConsultationDate = $consultations->max('date_consultation');

        return inertia('Admin/Patients/viewDetails',
        [
            'patient'=>$patient,
            'totalDossiers'=>$totalDossiers,
            'lastConsultationDate'=>$lastConsultationDate
        ]);
    }

    public function searchSalaries(Request $request)
{
    $searchTerm = $request->input('search');

    $salaries = Patient::where('type', 'SALARIE')
        ->where(function ($query) use ($searchTerm) {
            $query->where('nom', 'like', "%{$searchTerm}%")
                  ->orWhere('prenom', 'like', "%{$searchTerm}%")
                  ->orWhere(DB::raw('SUBSTRING(numero, 5)'), 'like', "%{$searchTerm}%");
        })
        ->take(4) // Limit the number of results returned
        ->get();

    return response()->json($salaries);
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
            'poste' => 'required_if:type,SALARIE|nullable|string',
            'date_entree_entreprise' => 'required_if:type,SALARIE|nullable|date',
            'statut_emploi' => 'required_if:type,SALARIE|nullable|in:Permanent,Temporaire,CDD,CDI',
            'date_fin_contrat' => 'required_if:statut_emploi,Temporaire,CDD|nullable|date|after_or_equal:date_entree_entreprise',
        ]);

        // Si le patient est de type FAMILLE, on récupère la société du salarié référent
        if ($validatedData['type'] === 'FAMILLE') {
        $parentPatient = Patient::find($validatedData['parent_id']);
        if ($parentPatient && $parentPatient->societe_id) {
            $validatedData['societe_id'] = $parentPatient->societe_id;
        }}
        // Ne formate la date_entree_entreprise que si elle est nécessaire
        $validatedData['date_naissance'] = date('Y-m-d', strtotime($validatedData['date_naissance']));
        if ($validatedData['type'] === 'SALARIE') {
            $validatedData['date_entree_entreprise'] = date('Y-m-d', strtotime($validatedData['date_entree_entreprise']));
        } else {
            $validatedData['date_entree_entreprise'] = null;
        }
        // Ne formate la date_fin_contrat que si elle est nécessaire
        if (in_array($validatedData['statut_emploi'], ['Temporaire', 'CDD']) && $validatedData['date_fin_contrat']) {
        $validatedData['date_fin_contrat'] = date('Y-m-d', strtotime($validatedData['date_fin_contrat']));
        } else {
        $validatedData['date_fin_contrat'] = null;
        }

        // Générer un numéro unique pour le patient
        $validatedData['numero'] = 'PAT' . str_pad(Patient::max('id') + 1,4, '0', STR_PAD_LEFT);


        Patient::create($validatedData);

        // Générer un badge pour le patient
        $badgeData = [
            'patient_id' => Patient::latest()->first()->id,
            'numero' => 'BAD' . str_pad(Badge::max('id') + 1, 6, '0', STR_PAD_LEFT),
            'qr_code' => 'QR' . \Illuminate\Support\Str::random(10),
            'date_emission' => now(),
            'validite' => now()->addMonth(), // Badge valide pour un mois
            'status' => 'actif',
        ];

        Badge::create($badgeData);

        return redirect()->route('admin.patient.index')
                        ->with('success', 'Patient ajouté avec succès');
    }
public function deletePatient(Request $request, Patient $patient)
{
    // Supprimer le patient
    $patient->delete();

    return redirect()->route('admin.patient.index')->with('success', 'Patient supprimé avec succès');
}
public function updatePatient(Request $request, Patient $patient)
{
    $validatedData = $request->validate([
            'lien_parente' => 'required_if:type,FAMILLE',
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'date_naissance' => 'required|date',
            'adresse' => 'required|string',
            'telephone' => 'required_if:type,SALARIE|string|nullable',
            'email' => 'required_if:type,SALARIE|email|nullable',
            'poste' => 'required_if:type,SALARIE|nullable|string',
            'statut_emploi' => 'required_if:type,SALARIE|nullable|in:Permanent,Temporaire,CDD,CDI',
            'date_fin_contrat' => 'required_if:statut_emploi,Temporaire,CDD|nullable|date|after_or_equal:date_entree_entreprise',
    ]);
    $validatedData['date_naissance'] = date('Y-m-d', strtotime($validatedData['date_naissance']));

    $patient->update($validatedData);
    return to_route('admin.patient.index')->with('success','PAtient mis à jour avec success');
}
public function showParametres(Request $request, Patient $patient)
{
    return Inertia::render('Admin/parametre');
}

 public function storeUser(Request $request): RedirectResponse
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'prenom' => 'required|string|max:255|',
        'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
        'role' => ['required', Rule::in(['admin', 'medecin'])],
        'specialite' => 'required_if:role,medecin|string|max:255|nullable',
        'type' => 'required_if:role,medecin|in:GENERALISTE,ENTREPRISE|nullable',
    ]);
       // Créer l'utilisateur
    $user = User::create([
        'name' => $validated['name'],
        'prenom' => $validated['prenom'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'role' => $validated['role'],
    ]);
      // Créer le médecin si nécessaire
    if ($validated['role'] === 'medecin') {
        Medecin::create([
            'user_id' => $user->id,
            'nom' => $validated['name'],
            'prenom' => $validated['prenom'],
            'specialite' => $validated['specialite'],
            'type' => $validated['type'],
        ]);
    }
    event(new Registered($user));
return redirect()->back()->with('Utilisateur enregistrer avec succes');
}
}
?>
