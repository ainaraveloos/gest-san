<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class DocumentController extends Controller
{
    /**
     * Prévisualise un document
     */
    public function previewDocument($type, $consultation)
    {
        $consultation = Consultation::with(['medecin', 'ordonnance.medicaments', 'demande_examen', 'lettre_reference.refereMed', 'patient'])
            ->findOrFail($consultation);

        // Vérifier si l'utilisateur a accès à cette consultation
        $user = Auth::user();
        if ($user->role === 'medecin' && $consultation->medecin_id !== $user->medecin->id) {
            abort(403, 'Accès non autorisé à cette consultation.');
        }

        $view = match($type) {
            'ordonnance' => 'documents.ordonnance',
            'demande_examen' => 'documents.demande-examen',
            'lettre_reference' => 'documents.lettre-reference',
            'certificat_medical' => 'documents.certificat-medical',
            default => abort(404)
        };
        // Gérérer le qrcode du patient en SVG
        $qrCode = QrCode::format('svg')->size(100)->generate(json_encode($consultation->patient));
        // Renvoyer la vue directement pour l'aperçu
        return view($view, compact('consultation', 'qrCode'));
    }

    /**
     * Télécharge un document PDF
     */
    public function downloadDocument($type, $consultation)
    {
        $consultation = Consultation::with(['medecin', 'ordonnance.medicaments', 'demande_examen', 'lettre_reference.refereMed', 'patient'])
            ->findOrFail($consultation);

        // Vérifier si l'utilisateur a accès à cette consultation
        $user = Auth::user();
        if ($user->role === 'medecin' && $consultation->medecin_id !== $user->medecin->id) {
            abort(403, 'Accès non autorisé à cette consultation.');
        }

        $view = match($type) {
            'ordonnance' => 'documents.ordonnance',
            'demande_examen' => 'documents.demande-examen',
            'lettre_reference' => 'documents.lettre-reference',
            'certificat_medical' => 'documents.certificat-medical',
            default => abort(404)
        };

        // Gérérer le qrcode du patient en SVG
        $qrCode = QrCode::format('svg')->size(100)->generate(json_encode($consultation->patient));

        Carbon::setLocale('fr'); 
        $date = Carbon::parse($consultation->date_consultation)->format('d_F_Y');

        $filename = sprintf(
            '%s_%s_%s.pdf',
            ucfirst($type),
            $consultation->patient->numero,
            $date
        );

        $pdf = Pdf::loadView($view, compact('consultation', 'qrCode'));
        return $pdf->download($filename);
    }
}
