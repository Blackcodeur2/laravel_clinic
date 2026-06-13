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

        <a href="{{ route('reports.pdf', ['month' => $month, 'year' => $year]) }}"
           target="_blank"
           class="flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white font-semibold text-sm rounded-xl transition-all shadow-lg shadow-emerald-500/20">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Télécharger PDF
        </a>
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

</x-app-layout>
