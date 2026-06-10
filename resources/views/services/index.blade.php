<x-app-layout>
    <x-slot name="header">Services médicaux</x-slot>
    <x-slot name="title">Services médicaux</x-slot>

    <div class="flex items-center justify-between mb-6">
        <p class="text-slate-400 text-sm">Catalogue des actes et services facturables</p>

        @can('create', App\Models\ServiceMedical::class)
            <a href="{{ route('services.create') }}"
               class="flex items-center gap-2 px-4 py-2.5 bg-cyan-500 hover:bg-cyan-400 text-slate-900 font-semibold text-sm rounded-xl transition-colors shadow-lg shadow-cyan-500/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Nouveau service
            </a>
        @endcan
    </div>

    <div class="rounded-2xl bg-slate-900 border border-slate-800 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-800">
                    <th class="text-left text-slate-400 font-medium px-6 py-3.5">Code</th>
                    <th class="text-left text-slate-400 font-medium px-6 py-3.5">Nom</th>
                    <th class="text-left text-slate-400 font-medium px-6 py-3.5 hidden md:table-cell">Description</th>
                    <th class="text-right text-slate-400 font-medium px-6 py-3.5">Prix (FCFA)</th>
                    <th class="text-right text-slate-400 font-medium px-6 py-3.5">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800">
                @forelse($services as $service)
                    <tr class="hover:bg-slate-800/50 transition-colors">
                        <td class="px-6 py-4">
                            <span class="font-mono text-xs bg-slate-800 border border-slate-700 px-2 py-1 rounded text-slate-300">
                                {{ $service->code }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-white font-medium">{{ $service->nom }}</td>
                        <td class="px-6 py-4 text-slate-400 hidden md:table-cell">{{ $service->description ?? '—' }}</td>
                        <td class="px-6 py-4 text-white font-semibold text-right">
                            {{ number_format($service->prix, 0, ',', ' ') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @can('update', $service)
                                    <a href="{{ route('services.edit', $service) }}"
                                       class="px-3 py-1.5 bg-slate-800 border border-slate-700 text-slate-300 text-xs rounded-lg hover:bg-slate-700 transition-colors">
                                        Modifier
                                    </a>
                                @endcan
                                @can('delete', $service)
                                    <form method="POST" action="{{ route('services.destroy', $service) }}"
                                          onsubmit="return confirm('Supprimer ce service ?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="px-3 py-1.5 bg-red-500/10 border border-red-500/20 text-red-400 text-xs rounded-lg hover:bg-red-500/20 transition-colors">
                                            Supprimer
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-12 text-slate-500">Aucun service médical</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($services instanceof \Illuminate\Pagination\LengthAwarePaginator && $services->hasPages())
            <div class="px-6 py-4 border-t border-slate-800">{{ $services->links() }}</div>
        @endif
    </div>
</x-app-layout>
