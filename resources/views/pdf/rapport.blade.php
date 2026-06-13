<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rapport d'Activité - {{ date('F Y', mktime(0, 0, 0, $month, 10, $year)) }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 13px;
            line-height: 1.5;
            color: #333;
            padding: 0;
        }
        .container {
            width: 100%;
            padding: 24px 28px;
        }

        /* ─── HEADER ─── */
        .header {
            border-bottom: 2px solid #0056b3;
            padding-bottom: 16px;
            margin-bottom: 20px;
        }
        .header table { width: 100%; }
        .logo-text {
            font-size: 22px;
            font-weight: bold;
            color: #0056b3;
        }
        .clinic-info {
            text-align: right;
            font-size: 11px;
            color: #666;
        }
        .report-title {
            text-align: center;
            font-size: 17px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 14px 0 4px 0;
            color: #222;
            letter-spacing: 1px;
        }
        .report-subtitle {
            text-align: center;
            font-size: 13px;
            color: #555;
            margin-bottom: 20px;
        }

        /* ─── KPI CARDS ─── */
        .kpi-row { width: 100%; margin-bottom: 20px; }
        .kpi-row table { width: 100%; border-collapse: separate; border-spacing: 8px 0; }
        .kpi-card {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 12px 14px;
            text-align: center;
            width: 25%;
        }
        .kpi-label {
            font-size: 10px;
            font-weight: bold;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }
        .kpi-value {
            font-size: 18px;
            font-weight: bold;
            color: #111827;
        }
        .kpi-value.green  { color: #059669; }
        .kpi-value.red    { color: #dc2626; }
        .kpi-value.blue   { color: #2563eb; }
        .kpi-unit { font-size: 10px; font-weight: normal; color: #9ca3af; }

        /* ─── SECTION TITLES ─── */
        .section-title {
            font-size: 13px;
            font-weight: bold;
            color: #1e3a5f;
            border-left: 3px solid #0056b3;
            padding-left: 8px;
            margin-bottom: 8px;
            margin-top: 0;
        }

        /* ─── TABLES ─── */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 12px;
        }
        .data-table th {
            background-color: #0056b3;
            color: white;
            padding: 7px 10px;
            text-align: left;
            font-size: 11px;
            font-weight: bold;
        }
        .data-table th.right { text-align: right; }
        .data-table th.center { text-align: center; }
        .data-table td {
            padding: 6px 10px;
            border-bottom: 1px solid #e5e7eb;
            color: #374151;
        }
        .data-table td.right { text-align: right; font-weight: 600; }
        .data-table td.center { text-align: center; }
        .data-table td.green { color: #059669; font-weight: 600; }
        .data-table tr:last-child td { border-bottom: none; }
        .data-table tr:nth-child(even) td { background-color: #f9fafb; }
        .empty-row td { text-align: center; color: #9ca3af; font-style: italic; padding: 16px; }

        /* ─── TWO COLUMN LAYOUT ─── */
        .two-col { width: 100%; margin-bottom: 20px; }
        .two-col table { width: 100%; border-spacing: 10px 0; border-collapse: separate; }
        .col-block {
            width: 50%;
            vertical-align: top;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 12px;
        }

        /* ─── FOOTER ─── */
        .footer {
            margin-top: 40px;
            border-top: 1px solid #ddd;
            padding-top: 12px;
            font-size: 10px;
            color: #9ca3af;
            text-align: center;
        }
        .footer strong { color: #6b7280; }

        /* ─── DIVIDER ─── */
        .divider { border: none; border-top: 1px solid #e5e7eb; margin: 12px 0; }
    </style>
</head>
<body>
<div class="container">

    {{-- ── HEADER ── --}}
    <div class="header">
        <table>
            <tr>
                @if($clinicSettings->logo_path && file_exists(public_path('storage/' . $clinicSettings->logo_path)))
                    <td style="width: 64px; vertical-align: top;">
                        <img src="{{ public_path('storage/' . $clinicSettings->logo_path) }}"
                             style="max-height: 52px; max-width: 52px; object-fit: contain;" alt="Logo">
                    </td>
                @endif
                <td style="vertical-align: top;">
                    <span class="logo-text">{{ $clinicSettings->nom_clinique }}</span><br>
                    <span style="font-size: 11px; color: #555;">{{ $clinicSettings->slogan ?: 'Système de Facturation Médicale' }}</span>
                </td>
                <td class="clinic-info" style="vertical-align: top;">
                    <strong>{{ $clinicSettings->nom_clinique }}</strong><br>
                    @if($clinicSettings->adresse){{ $clinicSettings->adresse }}@if($clinicSettings->ville), {{ $clinicSettings->ville }}@endif<br>@endif
                    @if($clinicSettings->telephone)Tél : {{ $clinicSettings->telephone }}<br>@endif
                    @if($clinicSettings->email)Email : {{ $clinicSettings->email }}@endif
                </td>
            </tr>
        </table>
    </div>

    {{-- ── TITLE ── --}}
    <div class="report-title">Rapport d'Activité Mensuel</div>
    <div class="report-subtitle">
        Période : {{ ucfirst(\Carbon\Carbon::create(null, $month, 1)->translatedFormat('F Y')) }}
        &nbsp;|&nbsp; Généré le {{ now()->format('d/m/Y à H:i') }}
    </div>

    {{-- ── KPI CARDS ── --}}
    <div class="kpi-row">
        <table>
            <tr>
                <td class="kpi-card">
                    <div class="kpi-label">Consultations</div>
                    <div class="kpi-value blue">{{ $totalConsultations }}</div>
                </td>
                <td class="kpi-card">
                    <div class="kpi-label">Total Facturé</div>
                    <div class="kpi-value">{{ number_format($totalInvoiced, 0, ',', ' ') }} <span class="kpi-unit">FCFA</span></div>
                </td>
                <td class="kpi-card">
                    <div class="kpi-label">Total Encaissé</div>
                    <div class="kpi-value green">{{ number_format($totalCollected, 0, ',', ' ') }} <span class="kpi-unit">FCFA</span></div>
                </td>
                <td class="kpi-card">
                    <div class="kpi-label">Reste à recouvrer</div>
                    <div class="kpi-value red">{{ number_format($totalUnpaid, 0, ',', ' ') }} <span class="kpi-unit">FCFA</span></div>
                </td>
            </tr>
        </table>
    </div>

    {{-- ── TWO-COLUMN: MEDECINS + MODES PAIEMENT ── --}}
    <div class="two-col">
        <table>
            <tr>
                {{-- Activités par médecin --}}
                <td class="col-block">
                    <p class="section-title">Activités par médecin</p>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Médecin</th>
                                <th class="right">Consultations</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($consultationsPerDoctor as $item)
                                <tr>
                                    <td>Dr. {{ $item->medecin?->prenom }} {{ $item->medecin?->nom }}</td>
                                    <td class="right">{{ $item->count }}</td>
                                </tr>
                            @empty
                                <tr class="empty-row"><td colspan="2">Aucune activité enregistrée.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </td>

                {{-- Encaissements par mode de paiement --}}
                <td class="col-block">
                    <p class="section-title">Encaissements par mode de paiement</p>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Mode</th>
                                <th class="right">Montant encaissé</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($paymentsByMethod as $payment)
                                <tr>
                                    <td>{{ ucfirst(strtolower(str_replace('_', ' ', $payment->mode_paiement))) }}</td>
                                    <td class="right green">{{ number_format($payment->total, 0, ',', ' ') }} FCFA</td>
                                </tr>
                            @empty
                                <tr class="empty-row"><td colspan="2">Aucun encaissement.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    {{-- ── TWO-COLUMN: TOP SERVICES + TOP MÉDICAMENTS ── --}}
    <div class="two-col">
        <table>
            <tr>
                {{-- Top 5 services --}}
                <td class="col-block">
                    <p class="section-title">Top 5 prestations de services</p>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Prestation</th>
                                <th class="center">Qté</th>
                                <th class="right">Revenus (FCFA)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topServices as $service)
                                <tr>
                                    <td>{{ $service->serviceMedical?->nom }}</td>
                                    <td class="center">{{ $service->count }}</td>
                                    <td class="right">{{ number_format($service->revenue, 0, ',', ' ') }}</td>
                                </tr>
                            @empty
                                <tr class="empty-row"><td colspan="3">Aucune prestation facturée.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </td>

                {{-- Top 5 médicaments --}}
                <td class="col-block">
                    <p class="section-title">Top 5 médicaments dispensés</p>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Médicament</th>
                                <th class="center">Qté vendue</th>
                                <th class="right">Revenus (FCFA)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topMedicaments as $med)
                                <tr>
                                    <td>{{ $med->medicament?->nom }}</td>
                                    <td class="center">{{ $med->count }}</td>
                                    <td class="right">{{ number_format($med->revenue, 0, ',', ' ') }}</td>
                                </tr>
                            @empty
                                <tr class="empty-row"><td colspan="3">Aucun médicament vendu.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    {{-- ── FOOTER ── --}}
    <div class="footer">
        <p><strong>{{ $clinicSettings->nom_clinique }}</strong> — Rapport confidentiel à usage interne uniquement.</p>
        <p>Généré automatiquement par le système MyClinic le {{ now()->format('d/m/Y à H:i') }}</p>
    </div>

</div>
</body>
</html>
