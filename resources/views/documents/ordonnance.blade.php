@extends('documents.layout')

@section('document_title')
    Ordonnance
@endsection

@section('content')
    @if($consultation->ordonnance->medicaments->isNotEmpty())
        <h4 style="margin-bottom: 6px;">Médicaments prescrits :</h4>

        <table>
            <thead>
                <tr>
                    <th>Désignation</th>
                    <th>Forme</th>
                    <th>Posologie</th>
                    <th style="text-align: center;">Quantité</th>
                </tr>
            </thead>
            <tbody>
                @foreach($consultation->ordonnance->medicaments as $medicament)
                    <tr>
                        <td>{{ $medicament->designation }}</td>
                        <td>{{ $medicament->forme }}</td>
                        <td>{{ $medicament->posologie }}</td>
                        <td style="text-align: center;">{{ $medicament->quantite }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if($consultation->ordonnance->nbr_jours_repos > 0)
        <div style="margin: 10px 0; padding: 6px; background-color: #f8f8f8; border: 1px solid #ddd;">
            <p style="margin: 0;"><strong>Repos prescrit :</strong> {{ $consultation->ordonnance->nbr_jours_repos }} jours</p>
        </div>
    @endif
@endsection
