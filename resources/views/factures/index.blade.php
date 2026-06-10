<x-app-layout>
    <x-slot name="header">Factures</x-slot>
    <x-slot name="title">Factures</x-slot>

    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <form method="GET" action="{{ route('factures.index') }}" class="flex flex-wrap gap-3">
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="N° facture ou patient..."
                       class="pl-10 pr-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-900 text-sm placeholder-slate-400 focus:outline-none focus:border-cyan-500 w-64 transition-colors"/>
            </div>
            <select name="statut"
                    class="px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-700 text-sm focus:outline-none focus:border-cyan-500 transition-colors">
                <option value="">Tous les statuts</option>
                <option value="IMPAYEE" {{ request('statut') === 'IMPAYEE' ? 'selected' : '' }}>Impayée</option>
                <option value="PARTIELLEMENT_PAYEE" {{ request('statut') === 'PARTIELLEMENT_PAYEE' ? 'selected' : '' }}>Partielle</option>
                <option value="PAYEE" {{ request('statut') === 'PAYEE' ? 'selected' : '' }}>Payée</option>
            </select>
            <button type="submit" class="px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-700 text-sm hover:bg-gray-100 transition-colors">
                Filtrer
            </button>
        </form>

        @can('create', App\Models\Facture::class)
            <a href="{{ route('factures.create') }}"
               class="flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-cyan-400 text-gray-900 font-semibold text-sm rounded-xl transition-colors shadow-lg shadow-cyan-500/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Nouvelle facture
            </a>
        @endcan
    </div>

    <div class="rounded-2xl bg-white shadow-sm ring-1 ring-gray-200 overflow-hidden">
        <table class="min-w-full text-sm align-middle">
            <thead class="bg-gray-50/50">
                <tr class="border-b border-gray-100">
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">N° Facture</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4 hidden md:table-cell">Patient</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4 hidden lg:table-cell">Date</th>
                    <th class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Total</th>
                    <th class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4 hidden sm:table-cell">Payé</th>
                    <th class="text-center text-gray-500 font-medium px-6 py-3.5">Statut</th>
                    <th class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse($factures as $facture)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="px-6 py-4">
                            <a href="{{ route('factures.show', $facture) }}"
                               class="text-blue-600 hover:text-cyan-300 font-mono font-medium transition-colors">
                                {{ $facture->numero_facture }}
                            </a>
                        </td>
                        <td class="px-6 py-4 text-gray-900 hidden md:table-cell">
                            {{ $facture->consultation?->patient?->prenom }} {{ $facture->consultation?->patient?->nom }}
                        </td>
                        <td class="px-6 py-4 text-gray-500 hidden lg:table-cell">
                            {{ \Carbon\Carbon::parse($facture->date_facture)->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 text-gray-900 font-semibold text-right">
                            {{ number_format($facture->montant_total, 0, ',', ' ') }} F
                        </td>
                        <td class="px-6 py-4 text-green-600 text-right hidden sm:table-cell">
                            {{ number_format($facture->montant_paye, 0, ',', ' ') }} F
                        </td>
                        <td class="px-6 py-4 text-center">
                            <x-statut-badge :statut="$facture->statut"/>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('factures.show', $facture) }}"
                                   class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors flex items-center justify-center"
                                   title="Voir">
                                    <i class='bi bi-eye text-lg'></i>
                                </a>
                                <a href="{{ route('factures.pdf', $facture) }}" target="_blank"
                                   class="p-2 text-gray-500 hover:text-indigo-600 hover:bg-blue-50 rounded-lg transition-colors flex items-center justify-center"
                                   title="Imprimer PDF">
                                    <i class='bi bi-file-earmark-pdf text-lg'></i>
                                </a>
                                @can('delete', $facture)
                                    <form method="POST" action="{{ route('factures.destroy', $facture) }}"
                                          onsubmit="return confirm('Supprimer cette facture ?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors flex items-center justify-center"
                                                title="Supprimer">
                                            <i class='bi bi-trash text-lg'></i>
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-12 text-gray-400">Aucune facture trouvée</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($factures->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $factures->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
