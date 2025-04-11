@extends('documents.layout')

@section('document_title')
    Certificat Médical
@endsection

@section('content')

    <div style="margin: 10px 0; text-align: justify;">
        <p style="margin: 0 0 5px 0;">Je soussigné(e), Dr. {{ $consultation->medecin->nom }} {{ $consultation->medecin->prenom }}, médecin {{ strtolower($consultation->medecin->type) }}, certifie avoir examiné ce jour le patient ci-dessus :</p>
        <p style="margin: 0 0 5px 0;">
            Cette personne présente les symptômes suivants : {{ $consultation->motif ?? 'Non spécifié' }}
        </p>

        @if(isset($consultation->diagnostic) && !empty($consultation->diagnostic))
            <p style="margin: 0 0 5px 0;">Diagnostic : {{ $consultation->diagnostic }}</p>
        @endif

        @if(isset($consultation->ordonnance) && $consultation->ordonnance->nbr_jours_repos > 0)
            <p style="margin: 8px 0; font-weight: bold;">
                Après examen clinique complet réalisé ce jour, je certifie que l'état de santé
                du/de la patient(e) nécessite une interruption temporaire de travail
                d'une durée de {{ $consultation->ordonnance->nbr_jours_repos }} jour{{ $consultation->ordonnance->nbr_jours_repos > 1 ? 's' : '' }} à compter de ce jour.
            </p>
        @endif

        <p style="margin: 8px 0;">
            Ce certificat est établi à la demande de l'intéressé(e) et lui est remis en main propre
            pour faire valoir ce que de droit.
        </p>
    </div>
@endsection
