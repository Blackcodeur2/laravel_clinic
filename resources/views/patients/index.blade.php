<x-app-layout>
    <x-slot name="header">Patients</x-slot>
    <x-slot name="title">Patients</x-slot>

    <div class="flex items-center justify-between mb-6">
        <form method="GET" action="{{ route('patients.index') }}" class="flex gap-3">
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="search" value="{{ $search ?? '' }}"
                       placeholder="Rechercher un patient..."
                       class="pl-10 pr-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-900 text-sm placeholder-slate-400 focus:outline-none focus:border-cyan-500 w-72 transition-colors"/>
            </div>
            <button type="submit" class="px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-700 text-sm hover:bg-gray-100 transition-colors">
                Filtrer
            </button>
        </form>

        @can('create', App\Models\Patient::class)
            <a href="{{ route('patients.create') }}"
               class="flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-cyan-400 text-gray-900 font-semibold text-sm rounded-xl transition-colors shadow-lg shadow-cyan-500/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Nouveau patient
            </a>
        @endcan
    </div>

    <div class="rounded-2xl bg-white shadow-sm ring-1 ring-gray-200 overflow-hidden">
        <table class="min-w-full text-sm align-middle">
            <thead class="bg-gray-50/50">
                <tr class="border-b border-gray-100">
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Patient</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4 hidden sm:table-cell">Téléphone</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4 hidden md:table-cell">Date naissance</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4 hidden lg:table-cell">Sexe</th>
                    <th class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse($patients as $patient)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center text-gray-900 text-xs font-bold flex-shrink-0">
                                    {{ strtoupper(substr($patient->prenom, 0, 1)) }}{{ strtoupper(substr($patient->nom, 0, 1)) }}
                                </div>
                                <span class="text-gray-900 font-medium">{{ $patient->prenom }} {{ $patient->nom }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-700 hidden sm:table-cell">{{ $patient->telephone }}</td>
                        <td class="px-6 py-4 text-gray-500 hidden md:table-cell">{{ \Carbon\Carbon::parse($patient->date_naissance)->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 hidden lg:table-cell">
                            <span class="px-2 py-1 rounded-md text-xs font-medium border
                                {{ $patient->sexe === 'M' ? 'bg-blue-500/10 text-indigo-600 border-blue-500/20' : 'bg-pink-500/10 text-pink-400 border-pink-500/20' }}">
                                {{ $patient->sexe === 'M' ? 'Masculin' : 'Féminin' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @can('update', $patient)
                                    <a href="{{ route('patients.edit', $patient) }}"
                                       class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors flex items-center justify-center"> <i class='bi bi-pencil-square text-lg'></i></a>
                                @endcan
                                @can('delete', $patient)
                                    <form method="POST" action="{{ route('patients.destroy', $patient) }}"
                                          onsubmit="return confirm('Supprimer ce patient ?')">
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
                        <td colspan="5" class="text-center py-12 text-gray-400">
                            Aucun patient trouvé
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($patients instanceof \Illuminate\Pagination\LengthAwarePaginator && $patients->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $patients->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
