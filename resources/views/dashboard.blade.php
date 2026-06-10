<x-app-layout>
    <x-slot name="header">Tableau de bord</x-slot>
    <x-slot name="title">Tableau de bord</x-slot>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">

        {{-- Total Patients --}}
        <div class="relative overflow-hidden rounded-2xl bg-gray-50 border border-gray-200 p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Patients</p>
                    <p class="text-gray-900 text-3xl font-bold mt-1">{{ number_format($totalPatients) }}</p>
                </div>
                <div class="w-11 h-11 rounded-xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
            <div class="absolute -bottom-4 -right-4 w-24 h-24 rounded-full bg-blue-500/5"></div>
        </div>

        {{-- Consultations aujourd'hui --}}
        <div class="relative overflow-hidden rounded-2xl bg-gray-50 border border-gray-200 p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Consultations aujourd'hui</p>
                    <p class="text-gray-900 text-3xl font-bold mt-1">{{ number_format($consultationsToday) }}</p>
                </div>
                <div class="w-11 h-11 rounded-xl bg-violet-500/10 border border-violet-500/20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
            <div class="absolute -bottom-4 -right-4 w-24 h-24 rounded-full bg-violet-500/5"></div>
        </div>

        {{-- Factures impayées --}}
        <div class="relative overflow-hidden rounded-2xl bg-gray-50 border border-gray-200 p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Factures impayées</p>
                    <p class="text-gray-900 text-3xl font-bold mt-1">{{ number_format($unpaidInvoicesCount) }}</p>
                </div>
                <div class="w-11 h-11 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
            <div class="absolute -bottom-4 -right-4 w-24 h-24 rounded-full bg-amber-500/5"></div>
        </div>

        {{-- Recettes du mois --}}
        <div class="relative overflow-hidden rounded-2xl bg-gray-50 border border-gray-200 p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Recettes du mois</p>
                    <p class="text-gray-900 text-3xl font-bold mt-1">{{ number_format($monthlyRevenue, 0, ',', ' ') }} <span class="text-lg text-gray-500">FCFA</span></p>
                </div>
                <div class="w-11 h-11 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="absolute -bottom-4 -right-4 w-24 h-24 rounded-full bg-emerald-500/5"></div>
        </div>
    </div>

    {{-- Tables --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

        {{-- Dernières factures --}}
        <div class="rounded-2xl bg-gray-50 border border-gray-200 overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                <h2 class="text-gray-900 font-semibold">Dernières factures</h2>
                <a href="{{ route('factures.index') }}"
                   class="text-blue-600 hover:text-cyan-300 text-sm font-medium transition-colors">
                    Voir tout →
                </a>
            </div>
            <div class="divide-y divide-slate-800">
                @forelse($latestInvoices as $facture)
                    <div class="flex items-center gap-4 px-6 py-3.5 hover:bg-white transition-colors">
                        <div class="flex-1 min-w-0">
                            <p class="text-gray-900 text-sm font-medium truncate">
                                {{ $facture->consultation?->patient?->prenom }} {{ $facture->consultation?->patient?->nom }}
                            </p>
                            <p class="text-gray-400 text-xs">{{ $facture->numero_facture }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-gray-900 text-sm font-semibold">{{ number_format($facture->montant_total, 0, ',', ' ') }} F</p>
                            <x-statut-badge :statut="$facture->statut"/>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-400 text-sm text-center py-8">Aucune facture</p>
                @endforelse
            </div>
        </div>

        {{-- Derniers patients --}}
        <div class="rounded-2xl bg-gray-50 border border-gray-200 overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                <h2 class="text-gray-900 font-semibold">Nouveaux patients</h2>
                <a href="{{ route('patients.index') }}"
                   class="text-blue-600 hover:text-cyan-300 text-sm font-medium transition-colors">
                    Voir tout →
                </a>
            </div>
            <div class="divide-y divide-slate-800">
                @forelse($latestPatients as $patient)
                    <div class="flex items-center gap-4 px-6 py-3.5 hover:bg-white transition-colors">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center text-gray-900 text-xs font-bold flex-shrink-0">
                            {{ strtoupper(substr($patient->prenom, 0, 1)) }}{{ strtoupper(substr($patient->nom, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-gray-900 text-sm font-medium truncate">{{ $patient->prenom }} {{ $patient->nom }}</p>
                            <p class="text-gray-400 text-xs">{{ $patient->telephone }}</p>
                        </div>
                        <span class="text-gray-400 text-xs">{{ $patient->created_at->diffForHumans() }}</span>
                    </div>
                @empty
                    <p class="text-gray-400 text-sm text-center py-8">Aucun patient</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
