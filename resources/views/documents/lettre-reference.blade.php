@extends('documents.layout')

@section('document_title')
    Lettre de Référence
@endsection

@section('content')
    <div style="margin-bottom: 20px;">
        <p><strong>Docteur</strong></p>
        <p>{{ $consultation->lettre_reference->refereMed->nom ?? '_________________' }}</p>
        <p>{{ $consultation->lettre_reference->refereMed->specialite ?? '_________________' }}</p>
    </div>

    <div style="margin-bottom: 20px;">
        <p><strong>Objet :</strong> Lettre de référence médicale</p>
        <p>Cher Confrère, Chère Consœur,</p>
        <p>Je me permets de vous adresser en consultation le patient cité ci-dessus pour les raisons suivantes :</p>
    </div>

    <table>
        <tr>
            <th colspan="2">Informations Médicales</th>
        </tr>
        <tr>
            <td width="30%"><strong>Motif de référence</strong></td>
            <td>{{ $consultation->lettre_reference->motif_ref ?? 'Non spécifié' }}</td>
        </tr>
        <tr>
            <td><strong>Diagnostic actuel</strong></td>
            <td>{{ $consultation->diagnostic ?? 'Non spécifié' }}</td>
        </tr>
        <tr>
            <td><strong>Antécédents notables</strong></td>
            <td>{{ $consultation->patient->antecedents ?? 'Aucun antécédent notable' }}</td>
        </tr>
    </table>

    <div style="margin: 20px 0;">
        <p>Je vous remercie de votre attention et vous prie de me tenir informé de vos conclusions.</p>
        <p>Confraternellement,</p>
    </div>
@endsection
