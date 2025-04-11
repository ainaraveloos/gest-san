<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title ?? 'Document Médical' }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            padding: 16px;
            font-size: 13px;
            line-height: 1.3;
            background-color: #fafafa;
            margin: 0;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 8px;
        }
        .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-bottom: 3px;
        }
        .logo-svg {
            width: 25px;
            height: 25px;
        }
        .logo span {
            font-size: 22px;
            font-weight: bold;
        }
        .subtitle {
            font-size: 11px;
            color: #666;
            margin: 1px 0;
        }
        .doctor-info {
            text-align: right;
            margin-bottom: 1px;
        }
        .doctor-info p {
            margin: 3px 0;
        }
        .patient-info {
            margin-bottom: 12px;
        }
        .patient-info table {
            width: 100%;
            border-collapse: collapse;
            padding: 6px;
        }
        .patient-info th, .patient-info td {
            padding: 6px;
            border-bottom: 1px solid #e0e0e0;
        }
        .patient-info th {
            text-align: left;
            background-color: #f0f0f0;
        }
        .document-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin: 15px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }
        th, td {
            padding: 6px;
            border: 1px solid #e0e0e0;
            text-align: left;
        }
        th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .signature {
            margin-top: 25px;
            text-align: right;
            border-top: 1px solid #aaa;
            padding-top: 8px;
            font-style: italic;
        }
        .signature p {
            margin: 2px 0;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            width: 100%;
            text-align: center;
            font-size: 9px;
            color: #666;
            border-top: 1px solid #ccc;
            padding-top: 5px;
            background-color: #fafafa;
        }
        /* Styles pour l'impression */
        @media print {
            @page {
                margin: 2cm; /* Ou 0 si vous voulez utiliser tout l'espace */
            }
            *,
            *::before,
            *::after {
                box-sizing: border-box; /* S'assurer que padding/border sont inclus dans la largeur/hauteur */
            }
            body {
                padding: 0;
                margin: 0;
                width: 100%; /* S'assurer que le body utilise la largeur disponible */
                max-width: 100%;
                background-color: white;
                color: black; /* Assurer un bon contraste */
                font-size: 12pt; /* Utiliser des points pour l'impression */
                -webkit-print-color-adjust: exact; /* Forcer l'impression des couleurs/arrière-plans */
                print-color-adjust: exact;
            }
            .no-print {
                display: none;
            }
            .footer {
                position: fixed;
                bottom: 0;
                background-color: white;
            }
            table, th, td {
                border: 1px solid #bbb;
            }
            th {
                background-color: #eee;
            }
            #qrcode-container {
                page-break-inside: avoid;
            }
            .signature {
                page-break-inside: avoid;
            }
        }
        #qrcode-container svg {
            width: 100%;
            height: 100%;
            display: block;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">
            <span>Medicare</span>
        </div>
        <div class="subtitle">Centre Médical Agréé</div>
        <div class="subtitle">123 Rue de la Santé, Adresse - Tél: 0000000000</div>
    </div>

    <div class="doctor-info">
        <p>Le {{ \Carbon\Carbon::parse($consultation->date_consultation)->locale('fr')->isoFormat('Do MMMM YYYY') }}</p>
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
                <th colspan="3">Informations du Patient</th>
            </tr>
            <tr>
                <td width="30%" rowspan="3" style="vertical-align: top;">
                    <strong>Identification</strong>
                    <div style="font-size: 14px; margin-top: 8px;">
                        <div>{{ $consultation->patient->nom }} {{ $consultation->patient->prenom }}</div>
                        <div style="margin-top: 4px;">{{ \Carbon\Carbon::parse($consultation->patient->date_naissance)->age }} ans</div>
                        <div style="margin-top: 4px;">N° {{ $consultation->patient->numero }}</div>
                    </div>
                </td>
                <td width="40%"><strong>Coordonnées</strong></td>
                <td width="30%" style="text-align: center;" rowspan="3">
                    <div id="qrcode-container" style="width: 80px; height: 80px; margin: 0 auto; overflow: hidden;">
                        <!-- le QR code SVG -->
                        {!! $qrCode !!}
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    @if(isset($consultation->patient->telephone))
                    <div>Tél: {{ $consultation->patient->telephone }}</div>
                    @endif
                    @if(isset($consultation->patient->adresse))
                    <div>{{ $consultation->patient->adresse }}</div>
                    @endif
                </td>
            </tr>
            <tr>
                <td>
                    <div style="font-size: 12px; color: #666; margin-top: 5px;">
                        Consultation du {{ \Carbon\Carbon::parse($consultation->date_consultation)->locale('fr')->isoFormat('Do MMMM YYYY') }}
                    </div>
                </td>
            </tr>
        </table>
    </div>

    @yield('content')

    <div class="signature">
        <p>Signature et cachet du médecin</p>
        <p style="font-size: 10px;">(Article R.4127-76 du code de la santé publique)</p>
    </div>
</body>
</html>
