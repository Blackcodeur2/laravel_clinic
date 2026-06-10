<x-app-layout>
    <x-slot name="header">Médicaments</x-slot>
    <x-slot name="title">Médicaments</x-slot>

    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <form method="GET" action="{{ route('medicaments.index') }}" class="flex gap-3">
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="search" value="{{ $search ?? '' }}"
                       placeholder="Nom du médicament..."
                       class="pl-10 pr-4 py-2.5 bg-slate-800 border border-slate-700 rounded-xl text-white text-sm placeholder-slate-400 focus:outline-none focus:border-cyan-500 w-60 transition-colors"/>
            </div>
            <button type="submit" class="px-4 py-2.5 bg-slate-800 border border-slate-700 rounded-xl text-slate-300 text-sm hover:bg-slate-700 transition-colors">
                Filtrer
            </button>
        </form>

        @can('create', App\Models\Medicament::class)
            <a href="{{ route('medicaments.create') }}"
               class="flex items-center gap-2 px-4 py-2.5 bg-cyan-500 hover:bg-cyan-400 text-slate-900 font-semibold text-sm rounded-xl transition-colors shadow-lg shadow-cyan-500/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Nouveau médicament
            </a>
        @endcan
    </div>

    <div class="rounded-2xl bg-slate-900 border border-slate-800 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-800">
                    <th class="text-left text-slate-400 font-medium px-6 py-3.5">Médicament</th>
                    <th class="text-right text-slate-400 font-medium px-6 py-3.5">Prix unit. (FCFA)</th>
                    <th class="text-center text-slate-400 font-medium px-6 py-3.5">Stock</th>
                    <th class="text-right text-slate-400 font-medium px-6 py-3.5">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800">
                @forelse($medicaments as $med)
                    <tr class="hover:bg-slate-800/50 transition-colors">
                        <td class="px-6 py-4">
                            <p class="text-white font-medium">{{ $med->nom }}</p>
                            @if($med->description)
                                <p class="text-slate-500 text-xs mt-0.5">{{ $med->description }}</p>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-white font-semibold text-right">
                            {{ number_format($med->prix, 0, ',', ' ') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold border
                                {{ $med->stock <= 10 ? 'bg-red-500/10 text-red-400 border-red-500/20' : ($med->stock <= 50 ? 'bg-amber-500/10 text-amber-400 border-amber-500/20' : 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20') }}">
                                {{ $med->stock }}
                                @if($med->stock <= 10) ⚠ @endif
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @can('update', $med)
                                    <a href="{{ route('medicaments.edit', $med) }}"
                                       class="px-3 py-1.5 bg-slate-800 border border-slate-700 text-slate-300 text-xs rounded-lg hover:bg-slate-700 transition-colors">
                                        Modifier
                                    </a>
                                @endcan
                                @can('delete', $med)
                                    <form method="POST" action="{{ route('medicaments.destroy', $med) }}"
                                          onsubmit="return confirm('Supprimer ce médicament ?')">
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
                        <td colspan="4" class="text-center py-12 text-slate-500">Aucun médicament</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($medicaments->hasPages())
            <div class="px-6 py-4 border-t border-slate-800">{{ $medicaments->links() }}</div>
        @endif
    </div>
</x-app-layout>
