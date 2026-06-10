<x-app-layout>
    <x-slot name="header">Consultations</x-slot>
    <x-slot name="title">Consultations</x-slot>

    <div class="flex items-center justify-between mb-6">
        <h2 class="text-slate-400 text-sm">{{ $consultations->count() }} consultation(s)</h2>

        @can('create', App\Models\Consultation::class)
            <a href="{{ route('consultations.create') }}"
               class="flex items-center gap-2 px-4 py-2.5 bg-cyan-500 hover:bg-cyan-400 text-slate-900 font-semibold text-sm rounded-xl transition-colors shadow-lg shadow-cyan-500/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Nouvelle consultation
            </a>
        @endcan
    </div>

    <div class="rounded-2xl bg-slate-900 border border-slate-800 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-800">
                    <th class="text-left text-slate-400 font-medium px-6 py-3.5">Patient</th>
                    <th class="text-left text-slate-400 font-medium px-6 py-3.5 hidden md:table-cell">Médecin</th>
                    <th class="text-left text-slate-400 font-medium px-6 py-3.5 hidden lg:table-cell">Date</th>
                    <th class="text-center text-slate-400 font-medium px-6 py-3.5">Facture</th>
                    <th class="text-right text-slate-400 font-medium px-6 py-3.5">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800">
                @forelse($consultations as $consultation)
                    <tr class="hover:bg-slate-800/50 transition-colors">
                        <td class="px-6 py-4 text-white font-medium">
                            {{ $consultation->patient?->prenom }} {{ $consultation->patient?->nom }}
                        </td>
                        <td class="px-6 py-4 text-slate-400 hidden md:table-cell">
                            Dr. {{ $consultation->medecin?->prenom }} {{ $consultation->medecin?->nom }}
                        </td>
                        <td class="px-6 py-4 text-slate-400 hidden lg:table-cell">
                            {{ \Carbon\Carbon::parse($consultation->date_consultation)->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($consultation->facture)
                                <a href="{{ route('factures.show', $consultation->facture) }}"
                                   class="text-cyan-400 hover:text-cyan-300 text-xs font-mono transition-colors">
                                    {{ $consultation->facture->numero_facture }}
                                </a>
                            @else
                                <span class="text-slate-600 text-xs">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('consultations.show', $consultation) }}"
                                   class="px-3 py-1.5 bg-slate-800 border border-slate-700 text-slate-300 text-xs rounded-lg hover:bg-slate-700 transition-colors">
                                    Voir
                                </a>
                                @can('update', $consultation)
                                    <a href="{{ route('consultations.edit', $consultation) }}"
                                       class="px-3 py-1.5 bg-slate-800 border border-slate-700 text-slate-300 text-xs rounded-lg hover:bg-slate-700 transition-colors">
                                        Modifier
                                    </a>
                                @endcan
                                @can('delete', $consultation)
                                    <form method="POST" action="{{ route('consultations.destroy', $consultation) }}"
                                          onsubmit="return confirm('Supprimer cette consultation ?')">
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
                        <td colspan="5" class="text-center py-12 text-slate-500">Aucune consultation</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>
