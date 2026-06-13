<x-app-layout>
    <x-slot name="header">Rapport d'Activité Mensuel</x-slot>
    <x-slot name="title">Rapport d'Activité - {{ $month }}/{{ $year }}</x-slot>

    {{-- Print Header (Only visible on print) --}}
    <div class="hidden print:block mb-8 border-b pb-4">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $clinicSettings->nom_clinique }}</h1>
                <p class="text-gray-500 text-sm">{{ $clinicSettings->adresse ?: 'Clinique Médicale' }}</p>
                <p class="text-gray-500 text-sm">Tél: {{ $clinicSettings->telephone ?: '—' }}</p>
            </div>
            <div class="text-right">
                <h2 class="text-xl font-bold text-gray-800">RAPPORT D'ACTIVITÉ MENSUEL</h2>
                <p class="text-gray-600 font-mono text-sm font-semibold">Période : {{ date('F Y', mktime(0, 0, 0, $month, 10, $year)) }}</p>
                <p class="text-gray-400 text-xs mt-1">Généré le {{ now()->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>

    {{-- Filter Bar (Hidden on print) --}}
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6 print:hidden">
        <form method="GET" action="{{ route('reports.monthly') }}" class="flex flex-wrap gap-3">
            <select name="month"
                    class="px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-700 text-sm focus:outline-none focus:border-cyan-500 transition-colors">
                @for ($m = 1; $m <= 12; $m++)
                    @php $val = sprintf('%02d', $m); @endphp
                    <option value="{{ $val }}" {{ $month == $val ? 'selected' : '' }}>
                        {{ ucfirst(\Carbon\Carbon::create(null, $m, 1)->translatedFormat('F')) }}
                    </option>
                @endfor
            </select>

            <select name="year"
                    class="px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-700 text-sm focus:outline-none focus:border-cyan-500 transition-colors">
                @for ($y = 2025; $y <= 2027; $y++)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>

            <button type="submit" class="px-5 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-700 text-sm hover:bg-gray-100 font-semibold transition-colors">
                Filtrer
            </button>
        </form>

        <button onclick="window.print()" class="flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-sm rounded-xl transition-colors shadow-lg shadow-emerald-500/10">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            Imprimer le rapport
        </button>
    </div>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- Total Consultations --}}
        <div class="rounded-2xl bg-white shadow-sm ring-1 ring-gray-200 p-6 flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs font-semibold uppercase tracking-wider mb-1">Consultations</p>
                <h3 class="text-2xl font-bold text-gray-900">{{ $totalConsultations }}</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-600">
                <i class="bi bi-clipboard-pulse text-2xl"></i>
            </div>
        </div>

        {{-- Total Billed --}}
        <div class="rounded-2xl bg-white shadow-sm ring-1 ring-gray-200 p-6 flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs font-semibold uppercase tracking-wider mb-1">Total Facturé</p>
                <h3 class="text-2xl font-bold text-gray-900">{{ number_format($totalInvoiced, 0, ',', ' ') }} <span class="text-sm font-medium">FCFA</span></h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-500/10 flex items-center justify-center text-indigo-600">
                <i class="bi bi-file-earmark-text text-2xl"></i>
            </div>
        </div>

        {{-- Total Collected --}}
        <div class="rounded-2xl bg-white shadow-sm ring-1 ring-gray-200 p-6 flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs font-semibold uppercase tracking-wider mb-1">Total Encaissé</p>
                <h3 class="text-2xl font-bold text-emerald-600">{{ number_format($totalCollected, 0, ',', ' ') }} <span class="text-sm font-medium">FCFA</span></h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-500/10 flex items-center justify-center text-emerald-600">
                <i class="bi bi-cash-stack text-2xl"></i>
            </div>
        </div>

        {{-- Total Unpaid --}}
        <div class="rounded-2xl bg-white shadow-sm ring-1 ring-gray-200 p-6 flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs font-semibold uppercase tracking-wider mb-1">Reste à recouvrer</p>
                <h3 class="text-2xl font-bold text-red-500">{{ number_format($totalUnpaid, 0, ',', ' ') }} <span class="text-sm font-medium">FCFA</span></h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-red-500/10 flex items-center justify-center text-red-500">
                <i class="bi bi-exclamation-triangle text-2xl"></i>
            </div>
        </div>
    </div>

    {{-- Main Tables --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        {{-- Consultations Per Doctor --}}
        <div class="rounded-2xl bg-white shadow-sm ring-1 ring-gray-200 p-6 flex flex-col">
            <h4 class="text-gray-900 font-bold text-base mb-4 flex items-center gap-2">
                <i class="bi bi-people text-blue-500"></i>
                Activités par médecin
            </h4>
            <div class="overflow-x-auto flex-1">
                <table class="min-w-full text-sm text-left">
                    <thead>
                        <tr class="border-b border-gray-100 text-gray-500 text-xs font-semibold uppercase">
                            <th class="py-2.5">Médecin</th>
                            <th class="py-2.5 text-right">Consultations effectuées</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($consultationsPerDoctor as $item)
                            <tr>
                                <td class="py-3 text-gray-900 font-medium">Dr. {{ $item->medecin?->prenom }} {{ $item->medecin?->nom }}</td>
                                <td class="py-3 text-right text-gray-900 font-semibold">{{ $item->count }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center py-6 text-gray-400">Aucune activité enregistrée.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Payments By Method --}}
        <div class="rounded-2xl bg-white shadow-sm ring-1 ring-gray-200 p-6 flex flex-col">
            <h4 class="text-gray-900 font-bold text-base mb-4 flex items-center gap-2">
                <i class="bi bi-wallet2 text-emerald-500"></i>
                Encaissements par mode de paiement
            </h4>
            <div class="overflow-x-auto flex-1">
                <table class="min-w-full text-sm text-left">
                    <thead>
                        <tr class="border-b border-gray-100 text-gray-500 text-xs font-semibold uppercase">
                            <th class="py-2.5">Mode de paiement</th>
                            <th class="py-2.5 text-right">Montant encaissé</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($paymentsByMethod as $payment)
                            <tr>
                                <td class="py-3 text-gray-900 font-medium">{{ ucfirst(strtolower(str_replace('_', ' ', $payment->mode_paiement))) }}</td>
                                <td class="py-3 text-right font-semibold text-emerald-600">{{ number_format($payment->total, 0, ',', ' ') }} FCFA</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center py-6 text-gray-400">Aucun encaissement.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- Top Services --}}
        <div class="rounded-2xl bg-white shadow-sm ring-1 ring-gray-200 p-6 flex flex-col">
            <h4 class="text-gray-900 font-bold text-base mb-4 flex items-center gap-2">
                <i class="bi bi-bandaid text-indigo-500"></i>
                Top 5 des prestations de services
            </h4>
            <div class="overflow-x-auto flex-1">
                <table class="min-w-full text-sm text-left">
                    <thead>
                        <tr class="border-b border-gray-100 text-gray-500 text-xs font-semibold uppercase">
                            <th class="py-2.5">Prestation</th>
                            <th class="py-2.5 text-center">Quantité</th>
                            <th class="py-2.5 text-right">Revenus générés</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($topServices as $service)
                            <tr>
                                <td class="py-3 text-gray-900 font-medium">{{ $service->serviceMedical?->nom }}</td>
                                <td class="py-3 text-center text-gray-500">{{ $service->count }}</td>
                                <td class="py-3 text-right font-semibold text-gray-900">{{ number_format($service->revenue, 0, ',', ' ') }} FCFA</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-6 text-gray-400">Aucune prestation facturée.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Top Medications --}}
        <div class="rounded-2xl bg-white shadow-sm ring-1 ring-gray-200 p-6 flex flex-col">
            <h4 class="text-gray-900 font-bold text-base mb-4 flex items-center gap-2">
                <i class="bi bi-capsule text-rose-500"></i>
                Top 5 des médicaments dispensés
            </h4>
            <div class="overflow-x-auto flex-1">
                <table class="min-w-full text-sm text-left">
                    <thead>
                        <tr class="border-b border-gray-100 text-gray-500 text-xs font-semibold uppercase">
                            <th class="py-2.5">Médicament</th>
                            <th class="py-2.5 text-center">Quantité vendue</th>
                            <th class="py-2.5 text-right">Revenus générés</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($topMedicaments as $med)
                            <tr>
                                <td class="py-3 text-gray-900 font-medium">{{ $med->medicament?->nom }}</td>
                                <td class="py-3 text-center text-gray-500">{{ $med->count }}</td>
                                <td class="py-3 text-right font-semibold text-gray-900">{{ number_format($med->revenue, 0, ',', ' ') }} FCFA</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-6 text-gray-400">Aucun médicament vendu.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Print Layout Optimizations --}}
    <style>
        @media print {
            body {
                background-color: white !important;
                color: black !important;
            }
            .fixed, header, nav, footer, .print\:hidden, [x-data] button {
                display: none !important;
            }
            .lg\:pl-64 {
                padding-left: 0 !important;
            }
            main {
                padding: 0 !important;
            }
            .ring-1, .shadow-sm {
                box-shadow: none !important;
                border: 0 !important;
                ring: 0 !important;
            }
            .rounded-2xl {
                border-radius: 0 !important;
            }
            table {
                border-collapse: collapse !important;
                width: 100% !important;
            }
            th, td {
                border-bottom: 1px solid #ddd !important;
                padding: 8px !important;
            }
            .grid {
                display: block !important;
            }
            .grid > div {
                margin-bottom: 2rem !important;
                page-break-inside: avoid !important;
            }
        }
    </style>
</x-app-layout>
