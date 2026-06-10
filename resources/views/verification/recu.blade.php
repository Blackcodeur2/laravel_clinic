<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vérification Reçu de Paiement — MyClinic</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background: linear-gradient(135deg, #28a745 0%, #1a6e2e 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .card {
            background: #fff;
            border-radius: 12px;
            max-width: 580px;
            width: 100%;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .card-header {
            background: #28a745;
            color: #fff;
            padding: 28px 32px;
            text-align: center;
        }
        .card-header .check-icon {
            width: 64px;
            height: 64px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            font-size: 32px;
        }
        .card-header h1 { font-size: 22px; margin-bottom: 6px; }
        .card-header p { font-size: 13px; opacity: 0.85; }
        .verified-badge {
            display: inline-block;
            background: rgba(255,255,255,0.25);
            color: #fff;
            font-size: 11px;
            font-weight: bold;
            padding: 4px 12px;
            border-radius: 20px;
            letter-spacing: 1px;
            margin-top: 10px;
            border: 1px solid rgba(255,255,255,0.5);
        }
        .card-body { padding: 32px; }
        .section-title {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #888;
            margin-bottom: 12px;
            font-weight: bold;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 24px;
        }
        .info-item label {
            display: block;
            font-size: 11px;
            color: #888;
            margin-bottom: 3px;
        }
        .info-item span {
            font-size: 14px;
            font-weight: 600;
            color: #222;
        }
        .amount-highlight {
            font-size: 28px;
            font-weight: bold;
            color: #28a745;
            text-align: center;
            padding: 16px;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .divider { border: none; border-top: 1px solid #eee; margin: 20px 0; }
        .footer-note {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 8px;
            padding: 14px 16px;
            font-size: 12px;
            color: #166534;
            margin-top: 16px;
        }
        .footer-note strong { display: block; margin-bottom: 4px; }
        .clinic-footer {
            text-align: center;
            padding: 16px;
            background: #f8f9fa;
            border-top: 1px solid #eee;
            font-size: 11px;
            color: #aaa;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-header">
            <div class="check-icon">✓</div>
            <h1>Reçu de Paiement Authentifié</h1>
            <p>Ce reçu a été émis et est valide dans notre système.</p>
            <span class="verified-badge">✓ REÇU ORIGINAL VÉRIFIÉ</span>
        </div>

        <div class="card-body">
            <div class="amount-highlight">
                {{ number_format($paiement->montant, 0, ',', ' ') }} FCFA
                <div style="font-size: 12px; color: #666; font-weight: normal; margin-top: 4px;">Montant versé</div>
            </div>

            <p class="section-title">Informations du Paiement</p>
            <div class="info-grid">
                <div class="info-item">
                    <label>Date de Paiement</label>
                    <span>{{ \Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y') }}</span>
                </div>
                <div class="info-item">
                    <label>Mode de Paiement</label>
                    <span>{{ $paiement->mode_paiement }}</span>
                </div>
                <div class="info-item">
                    <label>Référence Facture</label>
                    <span>{{ $paiement->facture->numero_facture }}</span>
                </div>
                <div class="info-item">
                    <label>Référence Transaction</label>
                    <span>{{ $paiement->reference ?: 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <label>Patient</label>
                    <span>{{ $paiement->facture->consultation->patient->nom }} {{ $paiement->facture->consultation->patient->prenom }}</span>
                </div>
                <div class="info-item">
                    <label>Médecin</label>
                    <span>Dr. {{ $paiement->facture->consultation->medecin->nom }}</span>
                </div>
            </div>

            <hr class="divider">

            <div class="footer-note">
                <strong>🔒 Vérification Réussie</strong>
                Ce QR code garantit que ce reçu a été généré par le système MyClinic et n'a pas été falsifié. En cas de doute, contactez la clinique.
            </div>
        </div>

        <div class="clinic-footer">
            MyClinic — Système de Facturation Médicale Certifié | Vérifié le {{ date('d/m/Y à H:i') }}
        </div>
    </div>
</body>
</html>
