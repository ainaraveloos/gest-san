@extends('documents.layout')

@section('document_title')
    Lettre de Référence
@endsection

@section('content')
    <div style="margin-bottom: 10px;">
        <p style="margin: 0 0 3px 0;"><strong>Docteur</strong></p>
        <p style="margin: 0;">{{ $consultation->lettre_reference->refereMed->prenom ?? '_________________' }} {{ $consultation->lettre_reference->refereMed->nom ?? '_________________' }} ({{ $consultation->lettre_reference->refereMed->specialite ?? '_________________' }})</p>
    </div>

    <div style="margin-bottom: 10px;">
        <p style="margin: 0 0 3px 0;"><strong>Objet :</strong> Lettre de référence médicale</p>
        <p style="margin: 0 0 3px 0;">Cher Confrère, Chère Consœur,</p>
        <p style="margin: 0;">Je me permets de vous adresser en consultation le patient cité ci-dessus pour les raisons suivantes :</p>
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
    </table>

    <div style="margin: 10px 0;">
        <p style="margin: 0 0 3px 0;">Je vous remercie de votre attention et vous prie de me tenir informé de vos conclusions.</p>
        <p style="margin: 0;">Confraternellement,</p>
    </div>
@endsection
