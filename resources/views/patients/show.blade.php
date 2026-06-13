<x-app-layout>
    <x-slot name="header">Détails & Historique du Patient</x-slot>
    <x-slot name="title">Détails du Patient - {{ $patient->prenom }} {{ $patient->nom }}</x-slot>

    <div class="mb-6">
        <a href="{{ route('patients.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-blue-600 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Retour à la liste
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6" x-data="{ tab: 'consultations' }">
        {{-- Patient Profile Info --}}
        <div class="lg:col-span-1 space-y-6">
            <div class="rounded-2xl bg-white shadow-sm ring-1 ring-gray-200 p-6">
                <div class="flex flex-col items-center text-center pb-6 border-b border-gray-100">
                    <div class="w-20 h-20 rounded-full bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center text-gray-900 text-2xl font-bold mb-4 shadow-lg shadow-cyan-500/20">
                        {{ strtoupper(substr($patient->prenom, 0, 1)) }}{{ strtoupper(substr($patient->nom, 0, 1)) }}
                    </div>
                    <h2 class="text-gray-900 font-bold text-xl">{{ $patient->prenom }} {{ $patient->nom }}</h2>
                    <span class="mt-2 px-3 py-1 rounded-full text-xs font-semibold border
                        {{ $patient->sexe === 'M' ? 'bg-blue-500/10 text-indigo-600 border-blue-500/20' : 'bg-pink-500/10 text-pink-400 border-pink-500/20' }}">
                        {{ $patient->sexe === 'M' ? 'Masculin' : 'Féminin' }}
                    </span>
                </div>

                <div class="py-6 space-y-4 text-sm">
                    <div>
                        <p class="text-gray-500 text-xs uppercase tracking-wider mb-1">Téléphone</p>
                        <p class="text-gray-900 font-medium">{{ $patient->telephone }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs uppercase tracking-wider mb-1">Date de naissance</p>
                        <p class="text-gray-900 font-medium">
                            {{ \Carbon\Carbon::parse($patient->date_naissance)->format('d/m/Y') }} 
                            ({{ \Carbon\Carbon::parse($patient->date_naissance)->age }} ans)
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs uppercase tracking-wider mb-1">Adresse</p>
                        <p class="text-gray-900 font-medium">{{ $patient->adresse ?: 'Non renseignée' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs uppercase tracking-wider mb-1">Date d'inscription</p>
                        <p class="text-gray-950 text-xs">{{ $patient->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-100 flex gap-2">
                    @can('update', $patient)
                        <a href="{{ route('patients.edit', $patient) }}" class="flex-1 text-center py-2 bg-gray-50 hover:bg-gray-100 border border-gray-200 text-gray-700 text-xs font-semibold rounded-xl transition-all">
                            Modifier
                        </a>
                    @endcan
                </div>
            </div>
        </div>

        {{-- Tabs & History Lists --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="rounded-2xl bg-white shadow-sm ring-1 ring-gray-200 overflow-hidden flex flex-col">
                {{-- Tabs Bar --}}
                <div class="flex border-b border-gray-200 bg-gray-50/50">
                    <button @click="tab = 'consultations'"
                            :class="tab === 'consultations' ? 'border-cyan-500 text-cyan-600 bg-white font-semibold' : 'border-transparent text-gray-500 hover:text-gray-900'"
                            class="flex-1 py-4 text-center border-b-2 text-sm transition-all focus:outline-none">
                        Consultations ({{ $consultations->count() }})
                    </button>
                    <button @click="tab = 'factures'"
                            :class="tab === 'factures' ? 'border-cyan-500 text-cyan-600 bg-white font-semibold' : 'border-transparent text-gray-500 hover:text-gray-900'"
                            class="flex-1 py-4 text-center border-b-2 text-sm transition-all focus:outline-none">
                        Factures ({{ $factures->count() }})
                    </button>
                    <button @click="tab = 'paiements'"
                            :class="tab === 'paiements' ? 'border-cyan-500 text-cyan-600 bg-white font-semibold' : 'border-transparent text-gray-500 hover:text-gray-900'"
                            class="flex-1 py-4 text-center border-b-2 text-sm transition-all focus:outline-none">
                        Paiements ({{ $paiements->count() }})
                    </button>
                </div>

                {{-- Tab contents --}}
                <div class="p-6">
                    {{-- Consultations Tab --}}
                    <div x-show="tab === 'consultations'" class="space-y-4">
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm text-left align-middle">
                                <thead>
                                    <tr class="border-b border-gray-100 text-gray-500 text-xs font-semibold uppercase">
                                        <th class="py-3">Médecin</th>
                                        <th class="py-3">Date</th>
                                        <th class="py-3 text-center">Facture</th>
                                        <th class="py-3 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @forelse($consultations as $consultation)
                                        <tr class="hover:bg-gray-50/30 transition-colors">
                                            <td class="py-3.5 text-gray-900 font-medium">Dr. {{ $consultation->medecin?->prenom }} {{ $consultation->medecin?->nom }}</td>
                                            <td class="py-3.5 text-gray-500">{{ \Carbon\Carbon::parse($consultation->date_consultation)->format('d/m/Y H:i') }}</td>
                                            <td class="py-3.5 text-center">
                                                @if($consultation->facture)
                                                    <div class="flex items-center justify-center gap-2">
                                                        <a href="{{ route('factures.show', $consultation->facture) }}" class="text-blue-600 hover:underline font-mono text-xs">
                                                            {{ $consultation->facture->numero_facture }}
                                                        </a>
                                                        <x-statut-badge :statut="$consultation->facture->statut"/>
                                                    </div>
                                                @else
                                                    <span class="text-gray-400 text-xs">—</span>
                                                @endif
                                            </td>
                                            <td class="py-3.5 text-right">
                                                <a href="{{ route('consultations.show', $consultation) }}" class="p-1.5 text-gray-500 hover:text-blue-600 rounded-lg hover:bg-blue-50 inline-flex items-center justify-center transition-colors">
                                                    <i class="bi bi-eye text-lg"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-8 text-gray-400">Aucune consultation enregistrée.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Factures Tab --}}
                    <div x-show="tab === 'factures'" class="space-y-4" style="display:none;">
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm text-left align-middle">
                                <thead>
                                    <tr class="border-b border-gray-100 text-gray-500 text-xs font-semibold uppercase">
                                        <th class="py-3">N° Facture</th>
                                        <th class="py-3">Date</th>
                                        <th class="py-3 text-right">Total</th>
                                        <th class="py-3 text-right">Payé</th>
                                        <th class="py-3 text-center">Statut</th>
                                        <th class="py-3 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @forelse($factures as $facture)
                                        <tr class="hover:bg-gray-50/30 transition-colors">
                                            <td class="py-3.5 font-mono text-xs font-semibold text-gray-900">{{ $facture->numero_facture }}</td>
                                            <td class="py-3.5 text-gray-500">{{ \Carbon\Carbon::parse($facture->date_facture)->format('d/m/Y') }}</td>
                                            <td class="py-3.5 text-right font-medium text-gray-900">{{ number_format($facture->montant_total, 0, ',', ' ') }} FCFA</td>
                                            <td class="py-3.5 text-right text-emerald-600 font-medium">{{ number_format($facture->montant_paye, 0, ',', ' ') }} FCFA</td>
                                            <td class="py-3.5 text-center">
                                                <x-statut-badge :statut="$facture->statut"/>
                                            </td>
                                            <td class="py-3.5 text-right">
                                                <div class="flex items-center justify-end gap-1.5">
                                                    <a href="{{ route('factures.show', $facture) }}" class="p-1.5 text-gray-500 hover:text-blue-600 rounded-lg hover:bg-blue-50 inline-flex items-center justify-center transition-colors" title="Voir">
                                                        <i class="bi bi-eye text-lg"></i>
                                                    </a>
                                                    <a href="{{ route('factures.pdf', $facture) }}" class="p-1.5 text-gray-500 hover:text-red-600 rounded-lg hover:bg-red-50 inline-flex items-center justify-center transition-colors" title="PDF Facture" target="_blank">
                                                        <i class="bi bi-file-earmark-pdf text-lg"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-8 text-gray-400">Aucune facture émise.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Paiements Tab --}}
                    <div x-show="tab === 'paiements'" class="space-y-4" style="display:none;">
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm text-left align-middle">
                                <thead>
                                    <tr class="border-b border-gray-100 text-gray-500 text-xs font-semibold uppercase">
                                        <th class="py-3">Facture</th>
                                        <th class="py-3">Date</th>
                                        <th class="py-3">Mode</th>
                                        <th class="py-3 text-right">Montant</th>
                                        <th class="py-3 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @forelse($paiements as $paiement)
                                        <tr class="hover:bg-gray-50/30 transition-colors">
                                            <td class="py-3.5 font-mono text-xs font-semibold text-gray-900">
                                                <a href="{{ route('factures.show', $paiement->facture) }}" class="text-blue-600 hover:underline">
                                                    {{ $paiement->facture?->numero_facture }}
                                                </a>
                                            </td>
                                            <td class="py-3.5 text-gray-500">{{ $paiement->created_at->format('d/m/Y H:i') }}</td>
                                            <td class="py-3.5 text-gray-700">
                                                <span class="px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                    {{ ucfirst($paiement->methode_paiement) }}
                                                </span>
                                            </td>
                                            <td class="py-3.5 text-right font-semibold text-emerald-600">{{ number_format($paiement->montant_paye, 0, ',', ' ') }} FCFA</td>
                                            <td class="py-3.5 text-right">
                                                <a href="{{ route('paiements.recu', $paiement) }}" class="p-1.5 text-gray-500 hover:text-emerald-600 rounded-lg hover:bg-emerald-50 inline-flex items-center justify-center transition-colors" title="Imprimer le reçu" target="_blank">
                                                    <i class="bi bi-printer text-lg"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-8 text-gray-400">Aucun paiement effectué.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
