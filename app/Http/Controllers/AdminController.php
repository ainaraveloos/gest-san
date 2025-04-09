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
use App\Models\Consultation;
use App\Models\Ordonnance;
use App\Models\Demande_examen;
use App\Models\Lettre_reference;
use App\Models\Medicament;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminController extends Controller
{
    /**
     * Calcule la date de début en fonction de la période sélectionnée.
     *
     * @param string $period
     * @return \Illuminate\Support\Carbon
     */
    private function getStartDateFromPeriod(string $period): Carbon
    {
        $now = now();
        switch ($period) {
            case 'today':
                return $now->copy()->startOfDay();
            case 'week':
                return $now->copy()->subDays(7);
            case 'month':
                return $now->copy()->subMonth();
            case 'year':
                return $now->copy()->subYear();
            default:
                // Retourne le début du mois par défaut si la période n'est pas reconnue
                Log::warning("Période de tableau de bord non reconnue: {$period}. Utilisation de 'month' par défaut.");
                return $now->copy()->subMonth();
        }
    }

    public function index(Request $request)
    {
        $period = $request->input('period', 'month');
        $startDate = $this->getStartDateFromPeriod($period);
        $now = now(); // Peut être nécessaire pour certaines requêtes

        // Statistiques générales
        $totalPatients = Patient::count();
        $totalConsultations = Consultation::where('date_consultation', '>=', $startDate)->count();


        // Tendance des consultations sur 6 mois
        $monthlyTrends = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthStart = $now->copy()->subMonths($i)->startOfMonth();
            $monthEnd = $now->copy()->subMonths($i)->endOfMonth();

            $monthlyTrends[] = [
                'month' => $monthStart->format('M Y'),
                'consultations' => Consultation::whereBetween('date_consultation', [$monthStart, $monthEnd])->count(),
            ];
        }

        // Consultations par type
        $consultationsByType = Consultation::query()
            ->where('date_consultation', '>=', $startDate)
            ->select('type', DB::raw('COUNT(*) as total'))
            ->groupBy('type')
            ->get()
            ->pluck('total', 'type');

        // Statut des badges
        $badgesStatus = Badge::query()
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->get()
            ->pluck('total', 'status');

        // Dernières consultations avec relations
        $recentConsultations = Consultation::query()
            ->where('date_consultation', '>=', $startDate)
            ->select([
                'consultations.*',
                DB::raw("CONCAT(patients.nom, ' ', patients.prenom) as patient_nom_complet"),
                DB::raw("CONCAT(medecins.nom, ' ', medecins.prenom) as medecin_nom_complet")
            ])
            ->join('patients', 'consultations.patient_id', '=', 'patients.id')
            ->join('medecins', 'consultations.medecin_id', '=', 'medecins.id')
            ->orderBy('date_consultation', 'desc')
            ->take(5)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'patient' => [
                        'nom_complet' => $item->patient_nom_complet
                    ],
                    'medecin' => [
                        'nom_complet' => $item->medecin_nom_complet
                    ],
                    'date_consultation' => $item->date_consultation
                ];
            });

        // Patients fréquents
        $frequentPatients = Patient::query()
            ->select([
                'patients.id',
                'patients.nom',
                'patients.prenom',
                DB::raw('COUNT(consultations.id) as consultations_count'),
                DB::raw("CONCAT(patients.nom, ' ', patients.prenom) as nom_complet")
            ])
            ->join('consultations', 'patients.id', '=', 'consultations.patient_id')
            ->groupBy('patients.id', 'patients.nom', 'patients.prenom')
            ->orderBy('consultations_count', 'desc')
            ->take(5)
            ->get();

        // Suivi médical
        $medicalFollowUp = [
            'ordonnances' => Ordonnance::where('created_at', '>=', $startDate)->count(),
            'examens' => Demande_examen::where('created_at', '>=', $startDate)->count(),
            'references' => Lettre_reference::where('created_at', '>=', $startDate)->count(),
            'certificats' => Consultation::whereNotNull('type')
                ->where('date_consultation', '>=', $startDate)
                ->count()
        ];

        // Jours de repos médicaux par médecin
        $medicalRestDays = Ordonnance::query()
            ->where('ordonnances.nbr_jours_repos', '>', 0)
            ->select([
                'medecins.id as medecin_id',
                'medecins.nom as medecin_nom',
                'medecins.prenom as medecin_prenom',
                DB::raw('COUNT(DISTINCT consultations.patient_id) as patients_count'),
                DB::raw('SUM(ordonnances.nbr_jours_repos) as total_jours')
            ])
            ->join('consultations', 'ordonnances.consultation_id', '=', 'consultations.id')
            ->join('medecins', 'consultations.medecin_id', '=', 'medecins.id')
            ->groupBy('medecins.id', 'medecins.nom', 'medecins.prenom')
            ->orderBy('medecins.nom')
            ->get()
            ->map(function ($item) {
                return [
                    'key' => $item->medecin_id,
                    'medecin' => $item->medecin_nom . ' ' . $item->medecin_prenom,
                    'patients' => $item->patients_count,
                    'totalJours' => $item->total_jours ?? 0,
                    'moyenne' => $item->patients_count > 0
                        ? round(($item->total_jours ?? 0) / $item->patients_count, 1)
                        : 0
                ];
            });

        $data = [
            'totalPatients' => $totalPatients,
            'totalConsultations' => $totalConsultations,
            'consultationsByType' => $consultationsByType,
            'badgesStatus' => $badgesStatus,
            'medicalFollowUp' => $medicalFollowUp,
            'recentConsultations' => $recentConsultations,
            'frequentPatients' => $frequentPatients,
            'medicalRestDays' => $medicalRestDays,
            'monthlyTrends' => $monthlyTrends
        ];

        return inertia('Admin/dashboard', [
            'stats' => $data
        ]);
    }

    public function showSocietes(Request $request)
    {
        // Récupérer le paramètre de tri depuis la requête, sans valeur par défaut
        $sort = $request->input('sort');

        // Démarrer la requête avec withCount pour compter les patients
        $query = Societe::withCount('patients');

        // Appliquer le tri en fonction du paramètre, seulement si un tri est spécifié
        if ($sort) {
            switch ($sort) {
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'name_asc':
                    $query->orderBy('nom', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('nom', 'desc');
                    break;
                case 'patients_count':
                    $query->orderBy('patients_count', 'desc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        }

        // Paginer les résultats
        $societes = $query->paginate(5)->appends($request->query());

        return inertia('Admin/Societes/Index', [
            'societes' => $societes,
            'filters' => [
                'sort' => $sort
            ]
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
        return inertia('Admin/Societes/Edit', [
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
    public function showPatients(Request $request)
    {
        // On démarre la query avec le eager loading
        $query = Patient::with(['societe', 'salarie', 'badge']);

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

        // Filtrer par statut de badge
        if ($request->filled('badgeStatuses')) {
            $badgeStatuses = $request->input('badgeStatuses');

            $query->whereHas('badge', function($q) use ($badgeStatuses) {
                // Si 'expired' est dans les statuts sélectionnés, nous devons le traiter spécialement
                $hasExpired = in_array('expired', $badgeStatuses);
                $actualStatuses = array_filter($badgeStatuses, function($status) {
                    return $status !== 'expired';
                });

                $q->where(function($subq) use ($actualStatuses, $hasExpired) {
                    if (!empty($actualStatuses)) {
                        $subq->whereIn('status', $actualStatuses);
                    }

                    if ($hasExpired) {
                        if (!empty($actualStatuses)) {
                            $subq->orWhere(function($expiredQ) {
                                $expiredQ->where('status', 'actif')
                                         ->where('validite', '<', now());
                            });
                        } else {
                            $subq->where('status', 'actif')
                                 ->where('validite', '<', now());
                        }
                    }
                });
            });
        }

        // Récupérer le paramètre de tri depuis la requête
        $sort = $request->input('sort', 'newest');

        // Appliquer le tri en fonction du paramètre
        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'name_asc':
                $query->orderBy('nom', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('nom', 'desc');
                break;
            case 'societe':
                $query->leftJoin('societes', 'patients.societe_id', '=', 'societes.id')
                      ->orderBy('societes.nom', 'asc')
                      ->select('patients.*');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $patients = $query->paginate(5)->appends($request->query());

        return inertia('Admin/Patients/Index', [
            'filters'   => $request->only(['search', 'societes', 'types', 'badgeStatuses', 'sort']),
            'patients'  => $patients,
            'societes'  => Societe::all(),
            'salaries'  => Patient::where('type', 'SALARIE')->get(),
        ]);
    }
    public function patientDossier($patient)
    {
        // Récupère le patient avec toutes ses consultations et les relations associées
        $patient = Patient::with([
            'consultations' => function ($query) {
                $query->orderBy('date_consultation', 'desc');
            },
            'consultations.medecin',
            'consultations.ordonnance.medicaments',
            'consultations.demande_examen',
            'consultations.lettre_reference.refereMed',
            'societe',
            'salarie',
            'badge'
        ])->withCount('consultations')->find($patient);
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
/**
 * Renouvelle un badge pour un mois supplémentaire
 */
public function renewBadge(Request $request)
{
    $validated = $request->validate([
        'badge_id' => 'required|exists:badges,id',
    ]);

    $badge = Badge::findOrFail($validated['badge_id']);

    // Nouvelle validité: 1 mois à partir d'aujourd'hui
    $badge->validite = now()->addMonth();
    $badge->status = 'actif';
    $badge->save();

    return redirect()->back();
}

/**
 * Vérifie et met à jour automatiquement les badges expirés
 */
public function checkExpiredBadges(Request $request)
{
    // Recherche tous les badges actifs mais expirés
    $expiredBadges = Badge::where('status', 'actif')
                         ->where('validite', '<', now())
                         ->get();

    foreach ($expiredBadges as $badge) {
        $badge->status = 'desactive';
        $badge->save();
    }

    return redirect()->back();
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
    return inertia('Admin/parametre');
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
public function medecinsList(Request $request)
{
    // Récupérer le paramètre de tri depuis la requête, sans valeur par défaut
    $sort = $request->input('sort');

    // Démarrer la requête avec les relations nécessaires
    $query = Medecin::with(['user']);

    // Appliquer le tri en fonction du paramètre, seulement si un tri est spécifié
    if ($sort) {
        switch ($sort) {
            case 'name_desc':
                $query->join('users', 'medecins.user_id', '=', 'users.id')
                    ->orderBy('users.name', 'desc')
                    ->select('medecins.*');
                break;
            case 'name_asc':
                $query->join('users', 'medecins.user_id', '=', 'users.id')
                    ->orderBy('users.name', 'asc')
                    ->select('medecins.*');
                break;
        }
    }

    $medecins = $query->withCount('consultations')->paginate(5)->appends($request->query());

    return inertia('Admin/Medecins/index', [
        'medecins' => $medecins,
        'filters' => [
            'sort' => $sort
        ]
    ]);
}

public function updateMedecin(Request $request, Medecin $medecin)
{
    // Préparer les règles de validation
    $rules = [
        'name' => 'required|string|max:255',
        'prenom' => 'required|string|max:255',
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users')->ignore($medecin->user_id)],
        'specialite' => 'required|string|max:255',
        'type' => 'required|in:GENERALISTE,ENTREPRISE',
    ];

    // Ajouter les règles pour le mot de passe si nécessaire
    if ($request->has('password') && !empty($request->password)) {
        $rules['password'] = ['required', 'confirmed', Rules\Password::defaults()];
    }

    // Valider les données
    $validated = $request->validate($rules);

    // Mettre à jour l'utilisateur associé
    $user = $medecin->user;
    $user->name = $validated['name'];
    $user->prenom = $validated['prenom'];
    $user->email = $validated['email'];

    // Mettre à jour le mot de passe si nécessaire
    if (isset($validated['password'])) {
        $user->password = Hash::make($validated['password']);
    }

    $user->save();

    // Mettre à jour le médecin
    $medecin->nom = $validated['name'];
    $medecin->prenom = $validated['prenom'];
    $medecin->specialite = $validated['specialite'];
    $medecin->type = $validated['type'];
    $medecin->save();

    return redirect()->route('admin.medecins.list')
                     ->with('success', 'Médecin mis à jour avec succès');
}

public function deleteMedecin(Medecin $medecin)
{
    // Récupérer l'utilisateur associé
    $user = $medecin->user;

    // Supprimer le médecin
    $medecin->delete();

    // Supprimer l'utilisateur associé
    $user->delete();

    return redirect()->route('admin.medecins.list')
                     ->with('success', 'Médecin supprimé avec succès');
}

/**
 * Prévisualise un document
 */
public function previewDocument($type, $consultation)
{
    $consultation = Consultation::with(['medecin', 'ordonnance.medicaments', 'demande_examen', 'lettre_reference.refereMed', 'patient'])
        ->findOrFail($consultation);

    $view = match($type) {
        'ordonnance' => 'documents.ordonnance',
        'demande_examen' => 'documents.demande-examen',
        'lettre_reference' => 'documents.lettre-reference',
        'certificat_medical' => 'documents.certificat-medical',
        default => abort(404)
    };

    // Renvoyer la vue directement pour l'aperçu
    return view($view, compact('consultation'));
}

/**
 * Télécharge un document PDF
 */
public function downloadDocument($type, $consultation)
{
    $consultation = Consultation::with(['medecin', 'ordonnance.medicaments', 'demande_examen', 'lettre_reference.refereMed', 'patient'])
        ->findOrFail($consultation);

    $view = match($type) {
        'ordonnance' => 'documents.ordonnance',
        'demande_examen' => 'documents.demande-examen',
        'lettre_reference' => 'documents.lettre-reference',
        'certificat_medical' => 'documents.certificat-medical',
        default => abort(404)
    };

    $filename = sprintf(
        '%s_%s_%s.pdf',
        ucfirst($type),
        $consultation->patient->numero,
        Carbon::parse($consultation->date_consultation)->format('Y-m-d')
    );

    $pdf = Pdf::loadView($view, compact('consultation'));
    return $pdf->download($filename);
}
}
?>
