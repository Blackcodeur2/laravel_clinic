<x-app-layout>
    <x-slot name="header">Services médicaux</x-slot>
    <x-slot name="title">Services médicaux</x-slot>

    <div x-data="{
        search: '',
        currentPage: 1,
        pageSize: 6,
        services: [
            @foreach($services as $service)
            {
                id: {{ $service->id }},
                code: '{{ $service->code }}',
                nom: '{{ addslashes($service->nom) }}',
                description: '{{ addslashes($service->description ?? '') }}',
                prix: {{ $service->prix }},
                edit_url: '{{ route('services.edit', $service) }}',
                destroy_url: '{{ route('services.destroy', $service) }}',
                can_update: {{ auth()->user()->can('update', $service) ? 'true' : 'false' }},
                can_delete: {{ auth()->user()->can('delete', $service) ? 'true' : 'false' }}
            },
            @endforeach
        ],
        get filteredServices() {
            return this.services.filter(s => {
                const term = this.search.toLowerCase().trim();
                return s.code.toLowerCase().includes(term) || 
                       s.nom.toLowerCase().includes(term) || 
                       s.description.toLowerCase().includes(term);
            });
        },
        get paginatedServices() {
            const start = (this.currentPage - 1) * this.pageSize;
            return this.filteredServices.slice(start, start + this.pageSize);
        },
        get totalPages() {
            return Math.ceil(this.filteredServices.length / this.pageSize) || 1;
        },
        formatCurrency(value) {
            return new Intl.NumberFormat('fr-FR').format(value);
        },
        init() {
            this.$watch('search', () => this.currentPage = 1);
        }
    }">
        <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
            <div class="flex flex-wrap gap-3">
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" x-model="search"
                           placeholder="Nom, code, desc..."
                           class="pl-10 pr-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-900 text-sm placeholder-slate-400 focus:outline-none focus:border-cyan-500 w-64 transition-colors"/>
                </div>
            </div>

            @can('create', App\Models\ServiceMedical::class)
                <a href="{{ route('services.create') }}"
                   class="flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-cyan-500 hover:from-blue-700 hover:to-cyan-600 text-white font-semibold text-sm rounded-xl transition-all shadow-lg shadow-blue-500/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    Nouveau service
                </a>
            @endcan
        </div>

        <div class="rounded-2xl bg-white shadow-sm ring-1 ring-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm align-middle">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Code</th>
                            <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Nom</th>
                            <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4 hidden md:table-cell">Description</th>
                            <th class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Prix (FCFA)</th>
                            <th class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        <template x-for="service in paginatedServices" :key="service.id">
                            <tr class="hover:bg-gray-50/50 transition-colors group">
                                <td class="px-6 py-4">
                                    <span class="font-mono text-xs bg-white border border-gray-300 px-2 py-1 rounded text-gray-700" x-text="service.code">
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-900 font-medium" x-text="service.nom"></td>
                                <td class="px-6 py-4 text-gray-500 hidden md:table-cell" x-text="service.description || '—'"></td>
                                <td class="px-6 py-4 text-gray-900 font-semibold text-right" x-text="formatCurrency(service.prix)"></td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <template x-if="service.can_update">
                                            <a :href="service.edit_url"
                                               class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors flex items-center justify-center">
                                                <i class='bi bi-pencil-square text-lg'></i>
                                            </a>
                                        </template>
                                        <template x-if="service.can_delete">
                                            <form method="POST" :action="service.destroy_url"
                                                  onsubmit="return confirm('Supprimer ce service ?')">
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
                        <tr x-show="filteredServices.length === 0">
                            <td colspan="5" class="text-center py-12 text-gray-400">Aucun service médical</td>
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
