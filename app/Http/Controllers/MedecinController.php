<?php

namespace App\Http\Controllers;
use App\Models\Patient;
use App\Models\Consultation;
use App\Models\Demande_examen;
use App\Models\Medicament;
use App\Models\Lettre_reference;
use App\Models\Medecin;
use App\Models\medicament as ModelsMedicament;
use App\Models\Ordonnance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\ViewErrorBag;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;


class MedecinController extends Controller
{
    public function index(Request $request)    {
        return inertia("Medecin/dashboard");
    }
   public function patientDossier(Request $request)
{
    $medecinId = Auth::user()->medecin->id;

    $query = Patient::whereHas('consultations', function($query) use ($medecinId) {
        $query->where('medecin_id', $medecinId);
    })
    ->withCount(['consultations' => function($query) use ($medecinId) {
        $query->where('medecin_id', $medecinId);
    }])
    ->with(['societe', 'consultations' => function($query) use ($medecinId) {
        $query->where('medecin_id', $medecinId)
            ->orderBy('date_consultation', 'desc');
    }]);
    // Gestion du tri
    $sort = $request->input('sort', 'desc');
    $query->orderBy('consultations_count', $sort);
    if ($request->filled('search')) {
        $search = $request->input('search');
        $query->where(function($q) use ($search) {
            $q->where('nom', 'like', "%{$search}%")
            ->orWhere('prenom', 'like', "%{$search}%")
            ->orWhere('numero', 'like', "%{$search}%");
        });
    }

    $patients = $query->paginate(4)->appends($request->query())
        ->through(function ($patient) {
            return [
                'id' => $patient->id,
                'nom' => $patient->nom,
                'prenom' => $patient->prenom,
                'numero' => $patient->numero,
                'telephone' => $patient->telephone,
                'societe' => $patient->societe,
                'consultations' => $patient->consultations,
                'consultations_count' => $patient->consultations_count,
            ];
        });
    return inertia('Medecin/patients/Dossier', [
        'patients' => $patients,
        'filters' => $request->only(['search', 'sort']),
        'medecin' => Auth::user()->medecin
    ]);
}
public function showDossier($id)
{
     $medecin = Auth::user()->medecin;
     $medecinId = $medecin->id;
    // Récupérer le patient avec ses consultations et les documents associés
    $patient = Patient::with(['consultations' => function($query) use ($medecinId) {
        // Consultations du medecin authentifié
        $query->where('medecin_id', $medecinId)
              ->with(['ordonnance' => function($query) {
                  $query->with('medicaments'); // Inclure les médicaments dans l'ordonnance
              }, 'demande_examen', 'lettre_reference.medecin_ref'])
              ->orderBy('date_consultation', 'desc');
    }, 'societe'])->findOrFail($id);

    // Retourner la vue avec les données du patient
    return inertia('Medecin/patients/Details', [
        'patient' => $patient,
        'medecin' => $medecin
    ]);
}
    public function createConsultation(Request $request)
    {
          // Récupérer les 5 dernières consultations avec leurs relations
        $consultations = Consultation::with(['ordonnance', 'demande_examen', 'lettre_reference','patient'])
            ->where('medecin_id', Auth::user()->medecin->id)
            ->latest()
            ->take(4)
            ->get();
        $patients = Patient::orderBy('nom')->get();
        $medecins = Medecin::where('id', '!=', Auth::user()->medecin->id)
            ->orderBy('nom')
            ->get();
        return inertia('Medecin/consultation/createConsultation', [
            'patients' => $patients,
            'medecins' => $medecins,
            'consultations'=> $consultations
        ]);
    }
    public function searchPatients(Request $request)
{
    $searchTerm = $request->input('search');

    //recherche de patient dans create consultation
    $patients = Patient::where(function ($query) use ($searchTerm) {
            $query->where('nom', 'like', "%{$searchTerm}%")
                ->orWhere('prenom', 'like', "%{$searchTerm}%")
                ->orWhere(DB::raw('SUBSTRING(numero, 5)'), 'like', "%{$searchTerm}%");
        })
        ->take(4)->get();

    return response()->json($patients);
}
    public function store(Request $request)
    {
        // Validation des données
        $request->validate([
            'patient_id' => 'required',
            'type' => 'required|in:maladie,visite_aptitude',
            'motif' => 'required|string|max:1000',
            'diagnostic' => 'required|string|max:1000',
        // Constantes vitales pour le patient
        'poids' => 'required|numeric',
        'taille' => 'required|numeric',
        'freq_card' => 'required|numeric',
        'temperature' => 'required|numeric',
        'observations' => 'nullable|string|max:1000',
            // autres validations pour ordonnance, examens, lettre de references
            'medicament.*.designation' => 'required|string|max:255',
            'medicament.*.forme' => 'required|string|max:255',
            'medicament.*.posologie' => 'required|string|max:255',
            'medicament.*.quantite' => 'required|integer|min:1',
            'nbr_jours_repos' => 'nullable|integer|min:0',
            'liste_examens' => 'nullable|array',
            'liste_examens.*' => 'string|distinct',
            'remarques' => 'nullable|string|max:1000',
            'refere_med_id' => 'nullable|exists:medecins,id',
            'motif_ref' => 'nullable|string|max:1000',
        ]);
            // 1. Mettre à jour les données du patient
            $patient = Patient::findOrFail($request->patient_id);
            $patient->update([
                'poids' => $request->poids,
                'taille' => $request->taille,
                'freq_card' => $request->freq_card,
                'press_art' => $request->press_art,
                'temperature' => $request->temperature,
                // Calculer l'IMC si nécessaire
                'imc' => $request->poids / (($request->taille / 100) * ($request->taille / 100))
            ]);
            // 2. Créer la consultation
            $consultation = Consultation::create([
                'medecin_id' => Auth::user()->medecin->id,
                'patient_id' => $request->patient_id,
                'type' => $request->type,
                'date_consultation' => now(),
                'motif' => $request->motif,
                'diagnostic' => $request->diagnostic,

            ]);

            // 3. Créer l'ordonnance si présente
    $ordonnance = null;
    if ($request->nbr_jours_repos != null || ($request->has('medicament') && count($request->medicament) > 0)) {
        $ordonnance = Ordonnance::create([
            'consultation_id' => $consultation->id,
            'nbr_jours_repos' => $request->nbr_jours_repos,
        ]);
    }

    // 4. Créer les médicaments
    if ($ordonnance && $request->has('medicament') && count($request->medicament) > 0) {
        foreach ($request->medicament as $medicamentData) {
            Medicament::create(array_merge($medicamentData, ['ordonnance_id' => $ordonnance->id]));
        }
    }

            // 4. Créer la demande d'examens si présente
            if ($request->has('liste_examens') && !empty($request->liste_examens)) {
                Demande_examen::create([
                    'consultation_id' => $consultation->id,
                    'liste_examens' => json_encode($request->liste_examens),
                    'remarques' => $request->remarques,
                ]);
            }

            // 5. Créer la lettre de référence si présente
            if ($request->refere_med_id != null ) {
                Lettre_reference::create([
                    'consultation_id' => $consultation->id,
                    'refere_med_id' => $request->refere_med_id,
                    'motif_ref' => $request->motif_ref,
                ]);
            }


            return redirect()
                ->route('patient.dossier.details',$request->patient_id);



    }
    public function parametre()
    {
        $doctor = Auth::user()->medecin;

        return inertia("Medecin/parametre",['doctor'=> $doctor]);
    }
    public function updateParameters(Request $request)
{
    // Validation des données
    $request->validate([
        'email' => 'required|email|unique:users,email,' . Auth::id(),
        'password' => 'nullable|string|min:8',
        'password_confirmation' => 'nullable|string|same:password',
    ]);


    $user = $request->user();


    $user->email = $request->email;


    if ($request->filled('password')) {
        $user->password = bcrypt($request->password);
    }


    $user->save();


    return redirect()->to('medecin/dashboard');
}
public function destroyConsultation(Request $request, $id)
{
    $consultation = Consultation::findOrFail($id);

    // Supprimer consultation avec tout les documents associés
    $consultation->delete();
    return redirect()
        ->back();

}
public function updateConsultation(Request $request, $id)
{
    $consultation = Consultation::findOrFail($id);

    // Validate the incoming request data
    $request->validate([
        'motif' => 'required|string|max:255',
        'diagnostic' => 'nullable|string|max:255',
    ]);

    // Update consultation details
    $consultation->update($request->only(['motif','diagnostic']));

    return redirect()
        ->back();

}
}
?>