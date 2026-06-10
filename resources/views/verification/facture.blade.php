<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vérification Facture {{ $facture->numero_facture }} — MyClinic</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background: linear-gradient(135deg, #0056b3 0%, #003580 100%);
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
            background: #0056b3;
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
            background: #28a745;
            color: #fff;
            font-size: 11px;
            font-weight: bold;
            padding: 4px 12px;
            border-radius: 20px;
            letter-spacing: 1px;
            margin-top: 10px;
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
        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-payee { background: #d4edda; color: #155724; }
        .status-partiel { background: #fff3cd; color: #856404; }
        .status-impayee { background: #f8d7da; color: #721c24; }
        .divider { border: none; border-top: 1px solid #eee; margin: 20px 0; }
        .footer-note {
            background: #f0f7ff;
            border: 1px solid #b8d9f8;
            border-radius: 8px;
            padding: 14px 16px;
            font-size: 12px;
            color: #0056b3;
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
            <h1>Facture Authentifiée</h1>
            <p>Ce document a été émis et est valide dans notre système.</p>
            <span class="verified-badge">✓ DOCUMENT ORIGINAL VÉRIFIÉ</span>
        </div>

        <div class="card-body">
            <p class="section-title">Informations de la Facture</p>
            <div class="info-grid">
                <div class="info-item">
                    <label>Numéro Facture</label>
                    <span>{{ $facture->numero_facture }}</span>
                </div>
                <div class="info-item">
                    <label>Date d'émission</label>
                    <span>{{ \Carbon\Carbon::parse($facture->date_facture)->format('d/m/Y') }}</span>
                </div>
                <div class="info-item">
                    <label>Patient</label>
                    <span>{{ $facture->consultation->patient->nom }} {{ $facture->consultation->patient->prenom }}</span>
                </div>
                <div class="info-item">
                    <label>Médecin</label>
                    <span>Dr. {{ $facture->consultation->medecin->nom }} {{ $facture->consultation->medecin->prenom }}</span>
                </div>
                <div class="info-item">
                    <label>Montant Total</label>
                    <span>{{ number_format($facture->montant_total, 0, ',', ' ') }} FCFA</span>
                </div>
                <div class="info-item">
                    <label>Statut</label>
                    <span>
                        @if($facture->statut === 'PAYEE')
                            <span class="status-badge status-payee">PAYÉE</span>
                        @elseif($facture->statut === 'PARTIELLEMENT_PAYEE')
                            <span class="status-badge status-partiel">PARTIELLEMENT PAYÉE</span>
                        @else
                            <span class="status-badge status-impayee">IMPAYÉE</span>
                        @endif
                    </span>
                </div>
            </div>

            <hr class="divider">

            <div class="footer-note">
                <strong>🔒 Vérification Réussie</strong>
                Ce QR code garantit que cette facture a été générée par le système MyClinic et n'a pas été falsifiée. En cas de doute, contactez la clinique.
            </div>
        </div>

        <div class="clinic-footer">
            MyClinic — Système de Facturation Médicale Certifié | Vérifié le {{ date('d/m/Y à H:i') }}
        </div>
    </div>
</body>
</html>
