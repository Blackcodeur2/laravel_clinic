<x-app-layout>
    <x-slot name="header">Factures</x-slot>
    <x-slot name="title">Factures</x-slot>

    <div x-data="{
        search: '',
        statut: '',
        currentPage: 1,
        pageSize: 6,
        factures: [
            @foreach($factures as $facture)
            {
                id: {{ $facture->id }},
                numero_facture: '{{ $facture->numero_facture }}',
                patient_name: '{{ addslashes($facture->consultation?->patient?->prenom) }} {{ addslashes($facture->consultation?->patient?->nom) }}',
                date_formatted: '{{ \Carbon\Carbon::parse($facture->date_facture)->format('d/m/Y') }}',
                montant_total: {{ $facture->montant_total }},
                montant_paye: {{ $facture->montant_paye }},
                statut: '{{ $facture->statut }}',
                show_url: '{{ route('factures.show', $facture) }}',
                pdf_url: '{{ route('factures.pdf', $facture) }}',
                destroy_url: '{{ route('factures.destroy', $facture) }}',
                can_delete: {{ auth()->user()->can('delete', $facture) ? 'true' : 'false' }}
            },
            @endforeach
        ],
        get filteredFactures() {
            return this.factures.filter(f => {
                const term = this.search.toLowerCase().trim();
                const matchesSearch = f.numero_facture.toLowerCase().includes(term) || 
                                      f.patient_name.toLowerCase().includes(term);
                const matchesStatut = this.statut === '' || f.statut === this.statut;
                return matchesSearch && matchesStatut;
            });
        },
        get paginatedFactures() {
            const start = (this.currentPage - 1) * this.pageSize;
            return this.filteredFactures.slice(start, start + this.pageSize);
        },
        get totalPages() {
            return Math.ceil(this.filteredFactures.length / this.pageSize) || 1;
        },
        formatCurrency(value) {
            return new Intl.NumberFormat('fr-FR').format(value) + ' F';
        },
        init() {
            this.$watch('search', () => this.currentPage = 1);
            this.$watch('statut', () => this.currentPage = 1);
        }
    }">
        <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
            <div class="flex flex-wrap gap-3">
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" x-model="search"
                           placeholder="N° facture ou patient..."
                           class="pl-10 pr-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-900 text-sm placeholder-slate-400 focus:outline-none focus:border-cyan-500 w-64 transition-colors"/>
                </div>
                <select x-model="statut"
                        class="px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-700 text-sm focus:outline-none focus:border-cyan-500 transition-colors">
                    <option value="">Tous les statuts</option>
                    <option value="IMPAYEE">Impayée</option>
                    <option value="PARTIELLEMENT_PAYEE">Partielle</option>
                    <option value="PAYEE">Payée</option>
                </select>
            </div>

            @can('create', App\Models\Facture::class)
                <a href="{{ route('factures.create') }}"
                   class="flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-cyan-500 hover:from-blue-700 hover:to-cyan-600 text-white font-semibold text-sm rounded-xl transition-all shadow-lg shadow-blue-500/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    Nouvelle facture
                </a>
            @endcan
        </div>

        <div class="rounded-2xl bg-white shadow-sm ring-1 ring-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm align-middle">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">N° Facture</th>
                            <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4 hidden md:table-cell">Patient</th>
                            <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4 hidden lg:table-cell">Date</th>
                            <th class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Total</th>
                            <th class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4 hidden sm:table-cell">Payé</th>
                            <th class="text-center text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Statut</th>
                            <th class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        <template x-for="facture in paginatedFactures" :key="facture.id">
                            <tr class="hover:bg-gray-50/50 transition-colors group">
                                <td class="px-6 py-4">
                                    <a :href="facture.show_url"
                                       class="text-blue-600 hover:text-cyan-500 font-mono font-semibold hover:underline transition-colors" x-text="facture.numero_facture">
                                    </a>
                                </td>
                                <td class="px-6 py-4 text-gray-900 hidden md:table-cell" x-text="facture.patient_name"></td>
                                <td class="px-6 py-4 text-gray-500 hidden lg:table-cell" x-text="facture.date_formatted"></td>
                                <td class="px-6 py-4 text-gray-900 font-semibold text-right" x-text="formatCurrency(facture.montant_total)"></td>
                                <td class="px-6 py-4 text-emerald-600 text-right hidden sm:table-cell" x-text="formatCurrency(facture.montant_paye)"></td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold border"
                                          :class="{
                                              'bg-red-500/10 text-red-500 border-red-500/20': facture.statut === 'IMPAYEE',
                                              'bg-amber-500/10 text-amber-500 border-amber-500/20': facture.statut === 'PARTIELLEMENT_PAYEE',
                                              'bg-emerald-500/10 text-emerald-600 border-emerald-500/20': facture.statut === 'PAYEE'
                                          }"
                                          x-text="facture.statut === 'IMPAYEE' ? 'Impayée' : (facture.statut === 'PARTIELLEMENT_PAYEE' ? 'Partielle' : 'Payée')">
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a :href="facture.show_url"
                                           class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors flex items-center justify-center"
                                           title="Voir">
                                            <i class='bi bi-eye text-lg'></i>
                                        </a>
                                        <a :href="facture.pdf_url" target="_blank"
                                           class="p-2 text-gray-500 hover:text-indigo-600 hover:bg-blue-50 rounded-lg transition-colors flex items-center justify-center"
                                           title="Imprimer PDF">
                                            <i class='bi bi-file-earmark-pdf text-lg'></i>
                                        </a>
                                        <template x-if="facture.can_delete">
                                            <form method="POST" :action="facture.destroy_url"
                                                  onsubmit="return confirm('Supprimer cette facture ?')">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <button type="submit"
                                                        class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors flex items-center justify-center"
                                                        title="Supprimer">
                                                    <i class='bi bi-trash text-lg'></i>
                                                </button>
                                            </form>
                                        </template>
                                    </div>
                                </td>
                            </tr>
                        </template>
                        <tr x-show="filteredFactures.length === 0">
                            <td colspan="7" class="text-center py-12 text-gray-400">Aucune facture trouvée</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Custom Pagination Controls --}}
            <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between bg-white print:hidden" x-show="totalPages > 1">
                <div class="text-sm text-gray-500">
                    Affichage de la page <span class="font-semibold text-gray-900" x-text="currentPage"></span> sur <span class="font-semibold text-gray-900" x-text="totalPages"></span>
                </div>
                <div class="flex gap-2">
                    <button @click="currentPage > 1 ? currentPage-- : null"
                            :disabled="currentPage === 1"
                            :class="currentPage === 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-50 active:bg-gray-100'"
                            class="px-4 py-2 bg-white border border-gray-300 rounded-xl text-sm text-gray-700 font-semibold transition-all shadow-sm">
                        Précédent
                    </button>
                    <button @click="currentPage < totalPages ? currentPage++ : null"
                            :disabled="currentPage === totalPages"
                            :class="currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-50 active:bg-gray-100'"
                            class="px-4 py-2 bg-white border border-gray-300 rounded-xl text-sm text-gray-700 font-semibold transition-all shadow-sm">
                        Suivant
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
