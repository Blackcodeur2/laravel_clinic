<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reçu de Paiement {{ $paiement->id }}</title>
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
            border-bottom: 2px solid #28a745;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .header table {
            width: 100%;
        }
        .logo-text {
            font-size: 26px;
            font-weight: bold;
            color: #28a745;
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
        .recu-box {
            border: 1px solid #ddd;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 30px;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
        }
        .details-table td {
            padding: 8px;
            font-size: 14px;
            border-bottom: 1px solid #eee;
        }
        .label {
            font-weight: bold;
            color: #555;
            width: 200px;
        }
        .amount-highlight {
            font-size: 22px;
            font-weight: bold;
            color: #28a745;
        }
        .footer {
            margin-top: 40px;
            border-top: 1px solid #ddd;
            padding-top: 20px;
            font-size: 11px;
            color: #777;
            text-align: center;
        }
        .qr-section {
            margin-top: 30px;
            border-top: 1px dashed #ccc;
            padding-top: 15px;
        }
        .qr-section table {
            width: 100%;
        }
        .qr-url {
            font-size: 9px;
            color: #888;
            word-break: break-all;
        }
        .qr-badge {
            display: inline-block;
            background-color: #28a745;
            color: white;
            font-size: 10px;
            font-weight: bold;
            padding: 2px 6px;
            border-radius: 3px;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <table>
                <tr>
                    @if($clinicSettings->logo_path && file_exists(public_path('storage/' . $clinicSettings->logo_path)))
                        <td style="width: 70px; vertical-align: top;">
                            <img src="{{ public_path('storage/' . $clinicSettings->logo_path) }}" style="max-height: 60px; max-width: 60px; object-fit: contain;" alt="Logo">
                        </td>
                    @endif
                    <td style="vertical-align: top;">
                        <span class="logo-text">{{ $clinicSettings->nom_clinique }}</span><br>
                        <span style="font-size: 12px; color: #555;">{{ $clinicSettings->slogan ?: 'Système de Facturation Médicale' }}</span>
                    </td>
                    <td class="clinic-info" style="vertical-align: top;">
                        <strong>{{ $clinicSettings->nom_clinique }}</strong><br>
                        @if($clinicSettings->adresse) {{ $clinicSettings->adresse }}@if($clinicSettings->ville), {{ $clinicSettings->ville }}@endif<br>@endif
                        @if($clinicSettings->telephone) Tél: {{ $clinicSettings->telephone }}<br>@endif
                        @if($clinicSettings->email) Email: {{ $clinicSettings->email }}@endif
                    </td>
                </tr>
            </table>
        </div>

        <div class="title">Reçu de Paiement</div>

        <div class="recu-box">
            <table class="details-table">
                <tr>
                    <td class="label">Date de Paiement :</td>
                    <td>{{ \Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y H:i') }}</td>
                </tr>
                <tr>
                    <td class="label">Patient :</td>
                    <td>
                        <strong>{{ $paiement->facture->consultation->patient->nom }} {{ $paiement->facture->consultation->patient->prenom }}</strong>
                    </td>
                </tr>
                <tr>
                    <td class="label">Référence Facture :</td>
                    <td>{{ $paiement->facture->numero_facture }}</td>
                </tr>
                <tr>
                    <td class="label">Mode de Paiement :</td>
                    <td>{{ $paiement->mode_paiement }}</td>
                </tr>
                <tr>
                    <td class="label">Référence Transaction :</td>
                    <td>{{ $paiement->reference ?: 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label">Montant Versé :</td>
                    <td><span class="amount-highlight">{{ number_format($paiement->montant, 2, ',', ' ') }} FCFA</span></td>
                </tr>
            </table>
        </div>

        <table class="details-table" style="background-color: #fff;">
            <tr>
                <td class="label">Montant Total Facture :</td>
                <td style="text-align: right;">{{ number_format($paiement->facture->montant_total, 2, ',', ' ') }} FCFA</td>
            </tr>
            <tr>
                <td class="label">Cumul Payé :</td>
                <td style="text-align: right; color: #28a745;">{{ number_format($paiement->facture->montant_paye, 2, ',', ' ') }} FCFA</td>
            </tr>
            <tr>
                <td class="label">Reste à Payer :</td>
                <td style="text-align: right; color: #dc3545; font-weight: bold;">{{ number_format($paiement->facture->reste_a_payer, 2, ',', ' ') }} FCFA</td>
            </tr>
        </table>

        <div class="qr-section">
            <table>
                <tr>
                    <td style="width: 140px; text-align: center;">
                        <img src="data:image/svg+xml;base64,{{ $qrCode }}" width="120" height="120" alt="QR Code">
                        <div>
                            <span class="qr-badge">✓ REÇU AUTHENTIQUE</span>
                        </div>
                    </td>
                    <td style="vertical-align: middle; padding-left: 20px;">
                        <p style="font-size: 12px; font-weight: bold; margin: 0 0 5px 0; color: #28a745;">Authenticité vérifiable par QR Code</p>
                        <p style="font-size: 11px; color: #555; margin: 0 0 8px 0;">
                            Scannez ce code QR pour vérifier l'authenticité de ce reçu.<br>
                            Réf. Facture : <strong>{{ $paiement->facture->numero_facture }}</strong>
                        </p>
                        <p class="qr-url">{{ $qrCodeUrl }}</p>
                    </td>
                </tr>
            </table>
        </div>

        <div class="footer">
            <p>Ce reçu atteste du versement de la somme mentionnée ci-dessus.</p>
            <p>{{ $clinicSettings->nom_clinique }} - Logiciel de Facturation Médicale Certifié - Généré le {{ date('d/m/Y H:i') }}</p>
        </div>
    </div>
</body>
</html>
