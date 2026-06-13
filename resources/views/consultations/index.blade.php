<x-app-layout>
    <x-slot name="header">Consultations</x-slot>
    <x-slot name="title">Consultations</x-slot>

    <div x-data="{
        search: '',
        medecinId: '',
        dateFilter: '',
        currentPage: 1,
        pageSize: 6,
        consultations: [
            @foreach($consultations as $consultation)
            {
                id: {{ $consultation->id }},
                patient_name: '{{ addslashes($consultation->patient?->prenom) }} {{ addslashes($consultation->patient?->nom) }}',
                medecin_name: 'Dr. {{ addslashes($consultation->medecin?->prenom) }} {{ addslashes($consultation->medecin?->nom) }}',
                medecin_id: '{{ $consultation->medecin_id }}',
                date_formatted: '{{ \Carbon\Carbon::parse($consultation->date_consultation)->format('d/m/Y') }}',
                date_raw: '{{ \Carbon\Carbon::parse($consultation->date_consultation)->toDateString() }}',
                facture_numero: '{{ $consultation->facture?->numero_facture }}',
                facture_url: '{{ $consultation->facture ? route('factures.show', $consultation->facture) : '' }}',
                show_url: '{{ route('consultations.show', $consultation) }}',
                edit_url: '{{ route('consultations.edit', $consultation) }}',
                destroy_url: '{{ route('consultations.destroy', $consultation) }}',
                can_update: {{ auth()->user()->can('update', $consultation) ? 'true' : 'false' }},
                can_delete: {{ auth()->user()->can('delete', $consultation) ? 'true' : 'false' }}
            },
            @endforeach
        ],
        get filteredConsultations() {
            return this.consultations.filter(c => {
                const term = this.search.toLowerCase().trim();
                const matchesSearch = c.patient_name.toLowerCase().includes(term) || 
                                      c.medecin_name.toLowerCase().includes(term);
                const matchesMedecin = this.medecinId === '' || c.medecin_id === this.medecinId;
                const matchesDate = this.dateFilter === '' || c.date_raw === this.dateFilter;
                return matchesSearch && matchesMedecin && matchesDate;
            });
        },
        get paginatedConsultations() {
            const start = (this.currentPage - 1) * this.pageSize;
            return this.filteredConsultations.slice(start, start + this.pageSize);
        },
        get totalPages() {
            return Math.ceil(this.filteredConsultations.length / this.pageSize) || 1;
        },
        init() {
            this.$watch('search', () => this.currentPage = 1);
            this.$watch('medecinId', () => this.currentPage = 1);
            this.$watch('dateFilter', () => this.currentPage = 1);
        }
    }">
        <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
            <div class="flex flex-wrap gap-3">
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" x-model="search"
                           placeholder="Patient ou médecin..."
                           class="pl-10 pr-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-900 text-sm placeholder-slate-400 focus:outline-none focus:border-cyan-500 w-64 transition-colors"/>
                </div>

                <select x-model="medecinId"
                        class="px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-700 text-sm focus:outline-none focus:border-cyan-500 transition-colors">
                    <option value="">Tous les médecins</option>
                    @foreach($medecins as $medecin)
                        <option value="{{ $medecin->id }}">
                            Dr. {{ $medecin->prenom }} {{ $medecin->nom }}
                        </option>
                    @endforeach
                </select>

                <input type="date" x-model="dateFilter"
                       class="px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-700 text-sm focus:outline-none focus:border-cyan-500 transition-colors"/>
            </div>

            @can('create', App\Models\Consultation::class)
                <a href="{{ route('consultations.create') }}"
                   class="flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-cyan-500 hover:from-blue-700 hover:to-cyan-600 text-white font-semibold text-sm rounded-xl transition-all shadow-lg shadow-blue-500/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    Nouvelle consultation
                </a>
            @endcan
        </div>

        <div class="rounded-2xl bg-white shadow-sm ring-1 ring-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm align-middle">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Patient</th>
                            <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4 hidden md:table-cell">Médecin</th>
                            <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4 hidden lg:table-cell">Date</th>
                            <th class="text-center text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Facture</th>
                            <th class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        <template x-for="consultation in paginatedConsultations" :key="consultation.id">
                            <tr class="hover:bg-gray-50/50 transition-colors group">
                                <td class="px-6 py-4 text-gray-900 font-medium" x-text="consultation.patient_name"></td>
                                <td class="px-6 py-4 text-gray-500 hidden md:table-cell" x-text="consultation.medecin_name"></td>
                                <td class="px-6 py-4 text-gray-500 hidden lg:table-cell" x-text="consultation.date_formatted"></td>
                                <td class="px-6 py-4 text-center">
                                    <template x-if="consultation.facture_numero">
                                        <a :href="consultation.facture_url"
                                           class="text-blue-600 hover:text-cyan-500 hover:underline font-mono text-xs transition-colors" x-text="consultation.facture_numero">
                                        </a>
                                    </template>
                                    <template x-if="!consultation.facture_numero">
                                        <span class="text-gray-400 text-xs">—</span>
                                    </template>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a :href="consultation.show_url"
                                           class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors flex items-center justify-center"
                                           title="Voir">
                                            <i class='bi bi-eye text-lg'></i>
                                        </a>
                                        <template x-if="consultation.can_update">
                                            <a :href="consultation.edit_url"
                                               class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors flex items-center justify-center">
                                                <i class='bi bi-pencil-square text-lg'></i>
                                            </a>
                                        </template>
                                        <template x-if="consultation.can_delete">
                                            <form method="POST" :action="consultation.destroy_url"
                                                  data-confirm="Voulez-vous vraiment supprimer cette consultation ?">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <button type="submit"
                                                        class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors flex items-center justify-center">
                                                    <i class='bi bi-trash text-lg'></i>
                                                </button>
                                            </form>
                                        </template>
                                    </div>
                                </td>
                            </tr>
                        </template>
                        <tr x-show="filteredConsultations.length === 0">
                            <td colspan="5" class="text-center py-12 text-gray-400">Aucune consultation</td>
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
