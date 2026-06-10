<x-app-layout>
    <x-slot name="header">Utilisateurs</x-slot>
    <x-slot name="title">Utilisateurs</x-slot>

    <div class="flex items-center justify-between mb-6">
        <p class="text-gray-500 text-sm">Gestion des accès au système</p>
        <a href="{{ route('users.create') }}"
           class="flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-cyan-400 text-gray-900 font-semibold text-sm rounded-xl transition-colors shadow-lg shadow-cyan-500/20">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            Nouvel utilisateur
        </a>
    </div>

    <div class="rounded-2xl bg-white shadow-sm ring-1 ring-gray-200 overflow-hidden">
        <table class="min-w-full text-sm align-middle">
            <thead class="bg-gray-50/50">
                <tr class="border-b border-gray-100">
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Utilisateur</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4 hidden sm:table-cell">Email</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Rôle</th>
                    <th class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse($users as $user)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-violet-500 to-purple-700 flex items-center justify-center text-gray-900 text-xs font-bold flex-shrink-0">
                                    {{ strtoupper(substr($user->prenom, 0, 1)) }}{{ strtoupper(substr($user->nom, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-gray-900 font-medium">{{ $user->prenom }} {{ $user->nom }}</p>
                                    <p class="text-gray-400 text-xs">{{ '@' . $user->username }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-500 hidden sm:table-cell">{{ $user->email }}</td>
                        <td class="px-6 py-4">
                            @php
                            $roleColors = [
                                'ADMIN' => 'bg-violet-500/10 text-violet-400 border-violet-500/20',
                                'RESPONSABLE' => 'bg-blue-500/10 text-indigo-600 border-blue-500/20',
                                'CAISSIER' => 'bg-emerald-500/10 text-green-600 border-emerald-500/20',
                            ];
                            $colorClass = $roleColors[$user->role?->nom] ?? 'bg-gray-200/10 text-gray-500 border-gray-200/20';
                            @endphp
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium border {{ $colorClass }}">
                                {{ $user->role?->nom ?? '—' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('users.edit', $user) }}"
                                   class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors flex items-center justify-center"> <i class='bi bi-pencil-square text-lg'></i></a>
                                @if(auth()->id() !== $user->id)
                                    <form method="POST" action="{{ route('users.destroy', $user) }}"
                                          onsubmit="return confirm('Supprimer cet utilisateur ?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors flex items-center justify-center"> <i class='bi bi-trash text-lg'></i></button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-12 text-gray-400">Aucun utilisateur</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">{{ $users->links() }}</div>
        @endif
    </div>
</x-app-layout>
