<x-app-layout>
    <x-slot name="header">Consultations</x-slot>
    <x-slot name="title">Consultations</x-slot>

    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <form method="GET" action="{{ route('consultations.index') }}" class="flex flex-wrap gap-3">
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Patient ou médecin..."
                       class="pl-10 pr-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-900 text-sm placeholder-slate-400 focus:outline-none focus:border-cyan-500 w-64 transition-colors"/>
            </div>

            <select name="medecin_id"
                    class="px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-700 text-sm focus:outline-none focus:border-cyan-500 transition-colors">
                <option value="">Tous les médecins</option>
                @foreach($medecins as $medecin)
                    <option value="{{ $medecin->id }}" {{ request('medecin_id') == $medecin->id ? 'selected' : '' }}>
                        Dr. {{ $medecin->prenom }} {{ $medecin->nom }}
                    </option>
                @endforeach
            </select>

            <input type="date" name="date_consultation" value="{{ request('date_consultation') }}"
                   class="px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-700 text-sm focus:outline-none focus:border-cyan-500 transition-colors"/>

            <button type="submit" class="px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-700 text-sm hover:bg-gray-100 transition-colors">
                Filtrer
            </button>
        </form>

        @can('create', App\Models\Consultation::class)
            <a href="{{ route('consultations.create') }}"
               class="flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-cyan-400 text-gray-900 font-semibold text-sm rounded-xl transition-colors shadow-lg shadow-cyan-500/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Nouvelle consultation
            </a>
        @endcan
    </div>

    <div class="rounded-2xl bg-white shadow-sm ring-1 ring-gray-200 overflow-hidden">
        <table class="min-w-full text-sm align-middle">
            <thead class="bg-gray-50/50">
                <tr class="border-b border-gray-100">
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Patient</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4 hidden md:table-cell">Médecin</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4 hidden lg:table-cell">Date</th>
                    <th class="text-center text-gray-500 font-medium px-6 py-3.5">Facture</th>
                    <th class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse($consultations as $consultation)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="px-6 py-4 text-gray-900 font-medium">
                            {{ $consultation->patient?->prenom }} {{ $consultation->patient?->nom }}
                        </td>
                        <td class="px-6 py-4 text-gray-500 hidden md:table-cell">
                            Dr. {{ $consultation->medecin?->prenom }} {{ $consultation->medecin?->nom }}
                        </td>
                        <td class="px-6 py-4 text-gray-500 hidden lg:table-cell">
                            {{ \Carbon\Carbon::parse($consultation->date_consultation)->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($consultation->facture)
                                <a href="{{ route('factures.show', $consultation->facture) }}"
                                   class="text-blue-600 hover:text-cyan-300 text-xs font-mono transition-colors">
                                    {{ $consultation->facture->numero_facture }}
                                </a>
                            @else
                                <span class="text-gray-900 text-xs">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('consultations.show', $consultation) }}"
                                   class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors flex items-center justify-center"
                                   title="Voir">
                                    <i class='bi bi-eye text-lg'></i>
                                </a>
                                @can('update', $consultation)
                                    <a href="{{ route('consultations.edit', $consultation) }}"
                                       class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors flex items-center justify-center"> <i class='bi bi-pencil-square text-lg'></i></a>
                                @endcan
                                @can('delete', $consultation)
                                    <form method="POST" action="{{ route('consultations.destroy', $consultation) }}"
                                          onsubmit="return confirm('Supprimer cette consultation ?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors flex items-center justify-center"> <i class='bi bi-trash text-lg'></i></button>
                                    </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-12 text-gray-400">Aucune consultation</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>
