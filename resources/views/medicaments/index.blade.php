<x-app-layout>
    <x-slot name="header">Médicaments</x-slot>
    <x-slot name="title">Médicaments</x-slot>

    <div x-data="{
        search: '',
        statutStock: '',
        currentPage: 1,
        pageSize: 6,
        medicaments: [
            @foreach($medicaments as $med)
            {
                id: {{ $med->id }},
                nom: '{{ addslashes($med->nom) }}',
                description: '{{ addslashes($med->description ?? '') }}',
                prix: {{ $med->prix }},
                stock: {{ $med->stock }},
                edit_url: '{{ route('medicaments.edit', $med) }}',
                destroy_url: '{{ route('medicaments.destroy', $med) }}',
                can_update: {{ auth()->user()->can('update', $med) ? 'true' : 'false' }},
                can_delete: {{ auth()->user()->can('delete', $med) ? 'true' : 'false' }}
            },
            @endforeach
        ],
        get filteredMedicaments() {
            return this.medicaments.filter(m => {
                const term = this.search.toLowerCase().trim();
                const matchesSearch = m.nom.toLowerCase().includes(term) || 
                                      m.description.toLowerCase().includes(term);
                
                let matchesStock = true;
                if (this.statutStock === 'in_stock') {
                    matchesStock = m.stock > 0;
                } else if (this.statutStock === 'out_of_stock') {
                    matchesStock = m.stock === 0;
                }
                return matchesSearch && matchesStock;
            });
        },
        get paginatedMedicaments() {
            const start = (this.currentPage - 1) * this.pageSize;
            return this.filteredMedicaments.slice(start, start + this.pageSize);
        },
        get totalPages() {
            return Math.ceil(this.filteredMedicaments.length / this.pageSize) || 1;
        },
        formatCurrency(value) {
            return new Intl.NumberFormat('fr-FR').format(value);
        },
        init() {
            this.$watch('search', () => this.currentPage = 1);
            this.$watch('statutStock', () => this.currentPage = 1);
        }
    }">
        <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
            <div class="flex flex-wrap gap-3">
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" x-model="search"
                           placeholder="Nom du médicament..."
                           class="pl-10 pr-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-900 text-sm placeholder-slate-400 focus:outline-none focus:border-cyan-500 w-60 transition-colors"/>
                </div>
                <select x-model="statutStock"
                        class="px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-700 text-sm focus:outline-none focus:border-cyan-500 transition-colors">
                    <option value="">Tous les stocks</option>
                    <option value="in_stock">En stock</option>
                    <option value="out_of_stock">Rupture de stock</option>
                </select>
            </div>

            @can('create', App\Models\Medicament::class)
                <a href="{{ route('medicaments.create') }}"
                   class="flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-cyan-500 hover:from-blue-700 hover:to-cyan-600 text-white font-semibold text-sm rounded-xl transition-all shadow-lg shadow-blue-500/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    Nouveau médicament
                </a>
            @endcan
        </div>

        <div class="rounded-2xl bg-white shadow-sm ring-1 ring-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm align-middle">
                    <thead class="bg-gray-50/50 border-b border-gray-150">
                        <tr>
                            <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Médicament</th>
                            <th class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Prix unit. (FCFA)</th>
                            <th class="text-center text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Stock</th>
                            <th class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        <template x-for="med in paginatedMedicaments" :key="med.id">
                            <tr class="hover:bg-gray-50/50 transition-colors group">
                                <td class="px-6 py-4">
                                    <p class="text-gray-900 font-medium" x-text="med.nom"></p>
                                    <template x-if="med.description">
                                        <p class="text-gray-400 text-xs mt-0.5" x-text="med.description"></p>
                                    </template>
                                </td>
                                <td class="px-6 py-4 text-gray-900 font-semibold text-right" x-text="formatCurrency(med.prix)"></td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold border"
                                          :class="{
                                              'bg-red-500/10 text-red-500 border-red-500/20': med.stock <= 10,
                                              'bg-amber-500/10 text-yellow-600 border-amber-500/20': med.stock > 10 && med.stock <= 50,
                                              'bg-emerald-500/10 text-green-600 border-emerald-500/20': med.stock > 50
                                          }">
                                        <span x-text="med.stock"></span>
                                        <template x-if="med.stock <= 10">
                                            <span class="ml-1">⚠</span>
                                        </template>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <template x-if="med.can_update">
                                            <a :href="med.edit_url"
                                               class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors flex items-center justify-center">
                                                <i class='bi bi-pencil-square text-lg'></i>
                                            </a>
                                        </template>
                                        <template x-if="med.can_delete">
                                            <form method="POST" :action="med.destroy_url"
                                                  onsubmit="return confirm('Supprimer ce médicament ?')">
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
                        <tr x-show="filteredMedicaments.length === 0">
                            <td colspan="4" class="text-center py-12 text-gray-400">Aucun médicament</td>
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
