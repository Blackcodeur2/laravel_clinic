<x-app-layout>
    <x-slot name="header">Services médicaux</x-slot>
    <x-slot name="title">Services médicaux</x-slot>

    <div class="flex items-center justify-between mb-6">
        <p class="text-gray-500 text-sm">Catalogue des actes et services facturables</p>

        @can('create', App\Models\ServiceMedical::class)
            <a href="{{ route('services.create') }}"
               class="flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-cyan-400 text-gray-900 font-semibold text-sm rounded-xl transition-colors shadow-lg shadow-cyan-500/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Nouveau service
            </a>
        @endcan
    </div>

    <div class="rounded-2xl bg-gray-50 border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200">
                    <th class="text-left text-gray-500 font-medium px-6 py-3.5">Code</th>
                    <th class="text-left text-gray-500 font-medium px-6 py-3.5">Nom</th>
                    <th class="text-left text-gray-500 font-medium px-6 py-3.5 hidden md:table-cell">Description</th>
                    <th class="text-right text-gray-500 font-medium px-6 py-3.5">Prix (FCFA)</th>
                    <th class="text-right text-gray-500 font-medium px-6 py-3.5">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800">
                @forelse($services as $service)
                    <tr class="hover:bg-white transition-colors">
                        <td class="px-6 py-4">
                            <span class="font-mono text-xs bg-white border border-gray-300 px-2 py-1 rounded text-gray-700">
                                {{ $service->code }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-900 font-medium">{{ $service->nom }}</td>
                        <td class="px-6 py-4 text-gray-500 hidden md:table-cell">{{ $service->description ?? '—' }}</td>
                        <td class="px-6 py-4 text-gray-900 font-semibold text-right">
                            {{ number_format($service->prix, 0, ',', ' ') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @can('update', $service)
                                    <a href="{{ route('services.edit', $service) }}"
                                       class="px-3 py-1.5 bg-white border border-gray-300 text-gray-700 text-xs rounded-lg hover:bg-gray-100 transition-colors">
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
                        <td colspan="5" class="text-center py-12 text-gray-400">Aucun service médical</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($services instanceof \Illuminate\Pagination\LengthAwarePaginator && $services->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">{{ $services->links() }}</div>
        @endif
    </div>
</x-app-layout>
