<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title ?? 'Document Médical' }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            padding: 20px;
            font-size: 14px;
            line-height: 1.4;
            background-color: white;
            margin: 0;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 5px;
        }
        .subtitle {
            font-size: 12px;
            color: #666;
        }
        .doctor-info {
            text-align: right;
            margin-bottom: 20px;
        }
        .patient-info {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            padding: 10px;
            background-color: #f8f8f8;
        }
        .document-title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin: 20px 0;
           
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f8f8f8;
            font-weight: bold;
        }
        .signature {
            margin-top: 40px;
            text-align: right;
            border-top: 1px solid #666;
            padding-top: 10px;
            font-style: italic;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        /* Styles pour l'impression */
        @media print {
            body {
                padding: 0;
                margin: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">MEDICARE</div>
        <div class="subtitle">Centre Médical Agréé</div>
        <div class="subtitle">123 Rue de la Santé, Adresse - Tél: 0000000000</div>
    </div>

    <div class="doctor-info">
        <p>Le {{ \Carbon\Carbon::parse($consultation->date_consultation)->format('d F Y') }}</p>
        <p><strong>Dr. {{ $consultation->medecin->nom }} {{ $consultation->medecin->prenom }}</strong></p>
        <p>Médecin {{ $consultation->medecin->type }}</p>
        <p>N° RPPS : ________________</p>
    </div>

    <div class="document-title">
        @yield('document_title')
    </div>

    <div class="patient-info">
        <table>
            <tr>
                <th colspan="2">Informations du Patient</th>
            </tr>
            <tr>
                <td width="30%"><strong>Nom et Prénom</strong></td>
                <td>{{ $consultation->patient->nom }} {{ $consultation->patient->prenom }}</td>
            </tr>
            <tr>
                <td><strong>Âge</strong></td>
                <td>{{ \Carbon\Carbon::parse($consultation->patient->date_naissance)->age }} ans</td>
            </tr>
            <tr>
                <td><strong>Numéro de dossier</strong></td>
                <td>{{ $consultation->patient->numero }}</td>
            </tr>
        </table>
    </div>

    @yield('content')

    <div class="signature">
        <p>Signature et cachet du médecin</p>
        <p style="font-size: 10px;">(Article R.4127-76 du code de la santé publique)</p>
    </div>

    <div class="footer">
        Document généré le {{ now()->format('d/m/Y à H:i') }} - MEDICARE
    </div>
</body>
</html>
