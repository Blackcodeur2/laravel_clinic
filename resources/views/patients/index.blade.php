<x-app-layout>
    <x-slot name="header">Patients</x-slot>
    <x-slot name="title">Patients</x-slot>

    <div x-data="{
        search: '',
        sexe: '',
        currentPage: 1,
        pageSize: 6,
        patients: [
            @foreach($patients as $patient)
            {
                id: {{ $patient->id }},
                nom: '{{ addslashes($patient->nom) }}',
                prenom: '{{ addslashes($patient->prenom) }}',
                initials: '{{ strtoupper(substr($patient->prenom, 0, 1)) }}{{ strtoupper(substr($patient->nom, 0, 1)) }}',
                telephone: '{{ $patient->telephone }}',
                date_naissance: '{{ \Carbon\Carbon::parse($patient->date_naissance)->format('d/m/Y') }}',
                sexe: '{{ $patient->sexe }}',
                has_unpaid: {{ $patient->hasUnpaidFacture() ? 'true' : 'false' }},
                edit_url: '{{ route('patients.edit', $patient) }}',
                show_url: '{{ route('patients.show', $patient) }}',
                destroy_url: '{{ route('patients.destroy', $patient) }}',
                can_update: {{ auth()->user()->can('update', $patient) ? 'true' : 'false' }},
                can_delete: {{ auth()->user()->can('delete', $patient) ? 'true' : 'false' }}
            },
            @endforeach
        ],
        get filteredPatients() {
            return this.patients.filter(p => {
                const term = this.search.toLowerCase().trim();
                const matchesSearch = p.nom.toLowerCase().includes(term) || 
                                      p.prenom.toLowerCase().includes(term) || 
                                      p.telephone.includes(term);
                const matchesSexe = this.sexe === '' || p.sexe === this.sexe;
                return matchesSearch && matchesSexe;
            });
        },
        get paginatedPatients() {
            const start = (this.currentPage - 1) * this.pageSize;
            return this.filteredPatients.slice(start, start + this.pageSize);
        },
        get totalPages() {
            return Math.ceil(this.filteredPatients.length / this.pageSize) || 1;
        },
        init() {
            this.$watch('search', () => this.currentPage = 1);
            this.$watch('sexe', () => this.currentPage = 1);
        }
    }">
        <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
            <div class="flex flex-wrap gap-3">
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" x-model="search"
                           placeholder="Rechercher un patient..."
                           class="pl-10 pr-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-900 text-sm placeholder-slate-400 focus:outline-none focus:border-cyan-500 w-72 transition-colors"/>
                </div>
                <select x-model="sexe"
                        class="px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-700 text-sm focus:outline-none focus:border-cyan-500 transition-colors">
                    <option value="">Tous les sexes</option>
                    <option value="M">Masculin</option>
                    <option value="F">Féminin</option>
                </select>
            </div>

            @can('create', App\Models\Patient::class)
                <a href="{{ route('patients.create') }}"
                   class="flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-cyan-500 hover:from-blue-700 hover:to-cyan-600 text-white font-semibold text-sm rounded-xl transition-all shadow-lg shadow-blue-500/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    Nouveau patient
                </a>
            @endcan
        </div>

        <div class="rounded-2xl bg-white shadow-sm ring-1 ring-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm align-middle">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Patient</th>
                            <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4 hidden sm:table-cell">Téléphone</th>
                            <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4 hidden md:table-cell">Date naissance</th>
                            <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4 hidden lg:table-cell">Sexe</th>
                            <th class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        <template x-for="patient in paginatedPatients" :key="patient.id">
                            <tr class="hover:bg-gray-50/50 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center text-gray-900 text-xs font-bold flex-shrink-0" x-text="patient.initials">
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-gray-900 font-medium leading-none" x-text="patient.prenom + ' ' + patient.nom"></span>
                                            <template x-if="patient.has_unpaid">
                                                <span class="inline-flex items-center gap-1.5 mt-1 text-xs text-red-500 font-semibold">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span>
                                                    Facture impayée
                                                </span>
                                            </template>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-700 hidden sm:table-cell" x-text="patient.telephone"></td>
                                <td class="px-6 py-4 text-gray-500 hidden md:table-cell" x-text="patient.date_naissance"></td>
                                <td class="px-6 py-4 hidden lg:table-cell">
                                    <span class="px-2 py-1 rounded-md text-xs font-medium border"
                                          :class="patient.sexe === 'M' ? 'bg-blue-500/10 text-indigo-600 border-blue-500/20' : 'bg-pink-500/10 text-pink-400 border-pink-500/20'"
                                          x-text="patient.sexe === 'M' ? 'Masculin' : 'Féminin'">
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a :href="patient.show_url"
                                           class="p-2 text-gray-500 hover:text-cyan-600 hover:bg-cyan-50 rounded-lg transition-colors flex items-center justify-center"
                                           title="Historique du patient">
                                            <i class='bi bi-eye text-lg'></i>
                                        </a>
                                        <template x-if="patient.can_update">
                                            <a :href="patient.edit_url"
                                               class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors flex items-center justify-center"> 
                                                <i class='bi bi-pencil-square text-lg'></i>
                                            </a>
                                        </template>
                                        <template x-if="patient.can_delete">
                                            <form method="POST" :action="patient.destroy_url"
                                                  onsubmit="return confirm('Supprimer ce patient ?')">
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
                        <tr x-show="filteredPatients.length === 0">
                            <td colspan="5" class="text-center py-12 text-gray-400">
                                Aucun patient trouvé
                            </td>
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
