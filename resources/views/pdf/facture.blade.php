<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Facture {{ $facture->numero_facture }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 14px;
            line-height: 1.5;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            padding: 20px;
        }
        .header {
            border-bottom: 2px solid #0056b3;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .header table {
            width: 100%;
        }
        .logo-text {
            font-size: 26px;
            font-weight: bold;
            color: #0056b3;
        }
        .clinic-info {
            text-align: right;
            font-size: 12px;
            color: #666;
        }
        .title {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 20px 0;
            color: #222;
        }
        .details-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .details-table td {
            padding: 5px;
            vertical-align: top;
        }
        .label {
            font-weight: bold;
            color: #555;
            width: 120px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            margin-bottom: 30px;
        }
        .items-table th {
            background-color: #0056b3;
            color: white;
            padding: 8px;
            text-align: left;
            font-size: 13px;
        }
        .items-table td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
            font-size: 13px;
        }
        .total-section {
            width: 40%;
            float: right;
            margin-top: 10px;
        }
        .total-table {
            width: 100%;
            border-collapse: collapse;
        }
        .total-table td {
            padding: 6px;
            font-size: 14px;
        }
        .total-table .bold {
            font-weight: bold;
            font-size: 16px;
            color: #000;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            font-size: 11px;
            font-weight: bold;
            border-radius: 3px;
            color: white;
        }
        .badge-payee { background-color: #28a745; }
        .badge-partiel { background-color: #ffc107; color: #333; }
        .badge-impayee { background-color: #dc3545; }
        .footer {
            margin-top: 60px;
            border-top: 1px solid #ddd;
            padding-top: 20px;
            font-size: 11px;
            color: #777;
            text-align: center;
        }
        .qr-section {
            margin-top: 40px;
            border-top: 1px dashed #ccc;
            padding-top: 15px;
        }
        .qr-section table {
            width: 100%;
        }
        .qr-label {
            font-size: 11px;
            color: #555;
            margin-top: 5px;
        }
        .qr-url {
            font-size: 9px;
            color: #888;
            word-break: break-all;
        }
        .qr-badge {
            display: inline-block;
            background-color: #0056b3;
            color: white;
            font-size: 10px;
            font-weight: bold;
            padding: 2px 6px;
            border-radius: 3px;
            margin-bottom: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <table>
                <tr>
                    <td>
                        <span class="logo-text">{{ $clinicSettings->nom_clinique }}</span><br>
                        <span style="font-size: 12px; color: #555;">{{ $clinicSettings->slogan ?: 'Système de Facturation Médicale' }}</span>
                    </td>
                    <td class="clinic-info">
                        <strong>{{ $clinicSettings->nom_clinique }}</strong><br>
                        @if($clinicSettings->adresse) {{ $clinicSettings->adresse }}@if($clinicSettings->ville), {{ $clinicSettings->ville }}@endif<br>@endif
                        @if($clinicSettings->telephone) Tél: {{ $clinicSettings->telephone }}<br>@endif
                        @if($clinicSettings->email) Email: {{ $clinicSettings->email }}@endif
                    </td>
                </tr>
            </table>
        </div>

        <div class="title">Facture d'Honoraires & Prestations</div>

        <table class="details-table">
            <tr>
                <td class="label">N° Facture :</td>
                <td><strong>{{ $facture->numero_facture }}</strong></td>
                <td class="label">Date :</td>
                <td>{{ \Carbon\Carbon::parse($facture->date_facture)->format('d/m/Y H:i') }}</td>
            </tr>
            <tr>
                <td class="label">Patient :</td>
                <td>
                    {{ $facture->consultation->patient->nom }} {{ $facture->consultation->patient->prenom }}<br>
                    Né(e) le : {{ \Carbon\Carbon::parse($facture->consultation->patient->date_naissance)->format('d/m/Y') }}<br>
                    Sexe : {{ $facture->consultation->patient->sexe === 'M' ? 'Masculin' : 'Féminin' }}<br>
                    Téléphone : {{ $facture->consultation->patient->telephone }}
                </td>
                <td class="label">Médecin :</td>
                <td>
                    Dr. {{ $facture->consultation->medecin->nom }} {{ $facture->consultation->medecin->prenom }}
                </td>
            </tr>
            <tr>
                <td class="label">Statut Facture :</td>
                <td colspan="3">
                    @if($facture->statut === 'PAYEE')
                        <span class="badge badge-payee">PAYÉE</span>
                    @elseif($facture->statut === 'PARTIELLEMENT_PAYEE')
                        <span class="badge badge-partiel">PARTIELLEMENT PAYÉE</span>
                    @else
                        <span class="badge badge-impayee">IMPAYÉE</span>
                    @endif
                </td>
            </tr>
        </table>

        <table class="items-table">
            <thead>
                <tr>
                    <th>Type Prestation</th>
                    <th>Désignation / Code</th>
                    <th style="text-align: right;">Prix Unitaire (FCFA)</th>
                    <th style="text-align: center;">Quantité</th>
                    <th style="text-align: right;">Total (FCFA)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($facture->ligneFactures as $ligne)
                    <tr>
                        <td>
                            @if($ligne->service_medical_id)
                                Service Médical
                            @else
                                Médicament / Pharmacie
                            @endif
                        </td>
                        <td>
                            @if($ligne->service_medical_id)
                                [{{ $ligne->serviceMedical->code }}] {{ $ligne->serviceMedical->nom }}
                            @else
                                {{ $ligne->medicament->nom }}
                            @endif
                        </td>
                        <td style="text-align: right;">{{ number_format($ligne->prix_unitaire, 2, ',', ' ') }}</td>
                        <td style="text-align: center;">{{ $ligne->quantite }}</td>
                        <td style="text-align: right;">{{ number_format($ligne->total, 2, ',', ' ') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total-section">
            <table class="total-table">
                <tr>
                    <td><strong>Montant Total :</strong></td>
                    <td style="text-align: right;"><strong>{{ number_format($facture->montant_total, 2, ',', ' ') }} FCFA</strong></td>
                </tr>
                <tr>
                    <td>Montant Payé :</td>
                    <td style="text-align: right; color: #28a745;">{{ number_format($facture->montant_paye, 2, ',', ' ') }} FCFA</td>
                </tr>
                <tr class="bold" style="border-top: 1px solid #333;">
                    <td>Reste à Payer :</td>
                    <td style="text-align: right; color: #dc3545;">{{ number_format($facture->reste_a_payer, 2, ',', ' ') }} FCFA</td>
                </tr>
            </table>
        </div>

        <div style="clear: both;"></div>

        <div class="qr-section">
            <table>
                <tr>
                    <td style="width: 140px; text-align: center;">
                        <img src="data:image/svg+xml;base64,{{ $qrCode }}" width="120" height="120" alt="QR Code">
                        <div class="qr-label">
                            <span class="qr-badge">✓ ORIGINAL VÉRIFIÉ</span>
                        </div>
                    </td>
                    <td style="vertical-align: middle; padding-left: 20px;">
                        <p style="font-size: 12px; font-weight: bold; margin: 0 0 5px 0; color: #0056b3;">Authenticité vérifiable par QR Code</p>
                        <p style="font-size: 11px; color: #555; margin: 0 0 8px 0;">
                            Scannez ce code QR pour vérifier l'originalité de cette facture.<br>
                            N° Facture : <strong>{{ $facture->numero_facture }}</strong>
                        </p>
                        <p class="qr-url">{{ $qrCodeUrl }}</p>
                    </td>
                </tr>
            </table>
        </div>

        <div class="footer">
            <p>Merci pour votre confiance. Pour toute réclamation, veuillez présenter cette facture.</p>
            <p>{{ $clinicSettings->nom_clinique }} - Généré le {{ date('d/m/Y H:i') }}</p>
        </div>
    </div>
</body>
</html>
