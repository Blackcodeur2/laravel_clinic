<x-app-layout>
    <x-slot name="header">Facture {{ $facture->numero_facture }}</x-slot>
    <x-slot name="title">Facture {{ $facture->numero_facture }}</x-slot>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- Left: Invoice details --}}
        <div class="xl:col-span-2 space-y-6">

            {{-- Header info --}}
            <div class="rounded-2xl bg-gray-50 border border-gray-200 p-6">
                <div class="flex flex-wrap items-start justify-between gap-4 mb-6">
                    <div>
                        <p class="text-gray-500 text-sm">Patient</p>
                        <h2 class="text-gray-900 text-xl font-bold mt-1">
                            {{ $facture->consultation?->patient?->prenom }} {{ $facture->consultation?->patient?->nom }}
                        </h2>
                        <p class="text-gray-500 text-sm">{{ $facture->consultation?->patient?->telephone }}</p>
                    </div>
                    <div class="text-right">
                        <x-statut-badge :statut="$facture->statut"/>
                        <p class="text-gray-500 text-sm mt-2">{{ \Carbon\Carbon::parse($facture->date_facture)->format('d/m/Y') }}</p>
                        <p class="text-gray-400 text-xs font-mono mt-1">{{ $facture->numero_facture }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div class="rounded-xl bg-white p-4 text-center">
                        <p class="text-gray-500 text-xs mb-1">Total</p>
                        <p class="text-gray-900 font-bold text-lg">{{ number_format($facture->montant_total, 0, ',', ' ') }} F</p>
                    </div>
                    <div class="rounded-xl bg-emerald-500/5 border border-emerald-500/20 p-4 text-center">
                        <p class="text-gray-500 text-xs mb-1">Payé</p>
                        <p class="text-green-600 font-bold text-lg">{{ number_format($facture->montant_paye, 0, ',', ' ') }} F</p>
                    </div>
                    <div class="rounded-xl bg-amber-500/5 border border-amber-500/20 p-4 text-center">
                        <p class="text-gray-500 text-xs mb-1">Reste</p>
                        <p class="text-yellow-600 font-bold text-lg">{{ number_format($facture->reste_a_payer, 0, ',', ' ') }} F</p>
                    </div>
                </div>

                <div class="flex gap-3 mt-5">
                    <a href="{{ route('factures.pdf', $facture) }}" target="_blank"
                       class="flex items-center gap-2 px-4 py-2 bg-blue-500/10 border border-blue-500/20 text-indigo-600 text-sm rounded-xl hover:bg-blue-500/20 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Télécharger PDF
                    </a>
                </div>
            </div>

            {{-- Line items --}}
            <div class="rounded-2xl bg-gray-50 border border-gray-200 overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                    <h3 class="text-gray-900 font-semibold">Lignes de facturation</h3>
                </div>

                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left text-gray-500 font-medium px-6 py-3">Désignation</th>
                            <th class="text-center text-gray-500 font-medium px-4 py-3">Qté</th>
                            <th class="text-right text-gray-500 font-medium px-4 py-3">PU</th>
                            <th class="text-right text-gray-500 font-medium px-6 py-3">Total</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800">
                        @forelse($facture->ligneFactures as $ligne)
                            <tr class="hover:bg-white/30 transition-colors">
                                <td class="px-6 py-3">
                                    <p class="text-gray-900">
                                        {{ $ligne->serviceMedical?->nom ?? $ligne->medicament?->nom ?? '—' }}
                                    </p>
                                    <p class="text-gray-400 text-xs">
                                        {{ $ligne->serviceMedical ? 'Service' : 'Médicament' }}
                                    </p>
                                </td>
                                <td class="px-4 py-3 text-gray-700 text-center">{{ $ligne->quantite }}</td>
                                <td class="px-4 py-3 text-gray-700 text-right">{{ number_format($ligne->prix_unitaire, 0, ',', ' ') }} F</td>
                                <td class="px-6 py-3 text-gray-900 font-semibold text-right">{{ number_format($ligne->total, 0, ',', ' ') }} F</td>
                                <td class="px-4 py-3 text-right">
                                    @can('update', $facture)
                                        <form method="POST" action="{{ route('factures.lignes.destroy', [$facture, $ligne]) }}"
                                              onsubmit="return confirm('Supprimer cette ligne ?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-400 hover:text-red-300 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-8 text-gray-400 text-sm">Aucune ligne. Ajoutez des services ou médicaments.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- Add line form --}}
                @can('update', $facture)
                    @if($facture->statut !== 'PAYEE')
                        <div class="border-t border-gray-200 p-6" x-data="{ type: 'service' }">
                            <h4 class="text-gray-700 text-sm font-medium mb-4">Ajouter une ligne</h4>
                            <form method="POST" action="{{ route('factures.lignes.store', $facture) }}" class="space-y-4">
                                @csrf

                                <div class="flex gap-3">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="type" value="service" x-model="type"
                                               class="accent-cyan-500"/>
                                        <span class="text-gray-700 text-sm">Service médical</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="type" value="medicament" x-model="type"
                                               class="accent-cyan-500"/>
                                        <span class="text-gray-700 text-sm">Médicament</span>
                                    </label>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    <div x-show="type === 'service'">
                                        <label class="block text-gray-500 text-xs mb-1">Service</label>
                                        <select name="service_medical_id"
                                                class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-gray-900 text-sm focus:outline-none focus:border-cyan-500">
                                            <option value="">-- Choisir --</option>
                                            @foreach($services as $service)
                                                <option value="{{ $service->id }}" data-prix="{{ $service->prix }}">
                                                    {{ $service->nom }} ({{ number_format($service->prix, 0, ',', ' ') }} F)
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div x-show="type === 'medicament'">
                                        <label class="block text-gray-500 text-xs mb-1">Médicament</label>
                                        <select name="medicament_id"
                                                class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-gray-900 text-sm focus:outline-none focus:border-cyan-500">
                                            <option value="">-- Choisir --</option>
                                            @foreach($medicaments as $med)
                                                <option value="{{ $med->id }}" data-prix="{{ $med->prix }}">
                                                    {{ $med->nom }} (Stock: {{ $med->stock }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-gray-500 text-xs mb-1">Quantité</label>
                                        <input type="number" name="quantite" value="1" min="1"
                                               class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-gray-900 text-sm focus:outline-none focus:border-cyan-500"/>
                                    </div>
                                    <div>
                                        <label class="block text-gray-500 text-xs mb-1">Prix unitaire (F)</label>
                                        <input type="number" name="prix_unitaire" step="0.01" min="0"
                                               class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-gray-900 text-sm focus:outline-none focus:border-cyan-500"/>
                                    </div>
                                </div>

                                <button type="submit"
                                        class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-cyan-500 hover:from-blue-700 hover:to-cyan-600 text-white font-semibold text-sm rounded-xl transition-all shadow-lg shadow-blue-500/20">
                                    + Ajouter la ligne
                                </button>
                            </form>
                        </div>
                    @endif
                @endcan
            </div>
        </div>

        {{-- Right: Payments --}}
        <div class="space-y-6">
            <div class="rounded-2xl bg-gray-50 border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-gray-900 font-semibold">Paiements</h3>
                </div>

                <div class="divide-y divide-slate-800">
                    @forelse($facture->paiements as $paiement)
                        <div class="px-6 py-4 flex items-start justify-between gap-3">
                            <div>
                                <p class="text-gray-900 text-sm font-semibold">{{ number_format($paiement->montant, 0, ',', ' ') }} F</p>
                                <p class="text-gray-500 text-xs">{{ $paiement->mode_paiement }}</p>
                                <p class="text-gray-400 text-xs">{{ \Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y') }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('paiements.recu', $paiement) }}" target="_blank"
                                   class="text-indigo-600 hover:text-blue-300 text-xs transition-colors">
                                    Reçu
                                </a>
                                @can('update', $facture)
                                    <form method="POST" action="{{ route('paiements.destroy', $paiement) }}"
                                          onsubmit="return confirm('Supprimer ce paiement ?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-300 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-400 text-sm text-center py-6">Aucun paiement</p>
                    @endforelse
                </div>

                {{-- Add payment form --}}
                @can('update', $facture)
                    @if($facture->statut !== 'PAYEE')
                        <div class="border-t border-gray-200 p-5">
                            <h4 class="text-gray-700 text-sm font-medium mb-4">Enregistrer un paiement</h4>
                            <form method="POST" action="{{ route('paiements.store', $facture) }}" class="space-y-3">
                                @csrf

                                <div>
                                    <label class="block text-gray-500 text-xs mb-1">Montant (F) <span class="text-red-400">*</span></label>
                                    <input type="number" name="montant" step="0.01" min="0.01"
                                           value="{{ old('montant', $facture->reste_a_payer) }}"
                                           class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-gray-900 text-sm focus:outline-none focus:border-cyan-500"/>
                                </div>

                                <div>
                                    <label class="block text-gray-500 text-xs mb-1">Mode de paiement <span class="text-red-400">*</span></label>
                                    <select name="mode_paiement"
                                            class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-gray-900 text-sm focus:outline-none focus:border-cyan-500">
                                        <option value="ESPECES">Espèces</option>
                                        <option value="CHEQUE">Chèque</option>
                                        <option value="VIREMENT">Virement</option>
                                        <option value="MOBILE">Mobile money</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-gray-500 text-xs mb-1">Référence</label>
                                    <input type="text" name="reference" value="{{ old('reference') }}"
                                           class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-gray-900 text-sm focus:outline-none focus:border-cyan-500"
                                           placeholder="Optionnel"/>
                                </div>

                                <div>
                                    <label class="block text-gray-500 text-xs mb-1">Date <span class="text-red-400">*</span></label>
                                    <input type="date" name="date_paiement" value="{{ old('date_paiement', now()->format('Y-m-d')) }}"
                                           class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-gray-900 text-sm focus:outline-none focus:border-cyan-500"/>
                                </div>

                                <button type="submit"
                                        class="w-full py-2.5 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white font-semibold text-sm rounded-xl transition-all shadow-lg shadow-emerald-500/20">
                                    Enregistrer le paiement
                                </button>
                            </form>
                        </div>
                    @endif
                @endcan
            </div>
        </div>
    </div>
</x-app-layout>
