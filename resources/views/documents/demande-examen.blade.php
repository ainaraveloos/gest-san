@extends('documents.layout')

@section('document_title')
    Demande d'Examen
@endsection

@section('content')
    <h4 style="margin-bottom: 10px;">Examens demandés :</h4>

    <table>
        <thead>
            <tr>
                <th>Désignation de l'examen</th>
            </tr>
        </thead>
        <tbody>
            @foreach(json_decode($consultation->demande_examen->liste_examens) as $examen)
                <tr>
                    <td style="font-weight: bold;">{{ strtoupper($examen) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if($consultation->demande_examen->remarques)
        <div style="margin: 15px 0; padding: 10px; background-color: #f8f8f8; border: 1px solid #ddd;">
            <p style="margin: 0 0 5px 0;"><strong>Remarques :</strong></p>
            <p style="margin: 0; line-height: 1.4;">{{ $consultation->demande_examen->remarques }}</p>
        </div>
    @endif
@endsection
