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

    <div class="rounded-2xl bg-gray-50 border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200">
                    <th class="text-left text-gray-500 font-medium px-6 py-3.5">Utilisateur</th>
                    <th class="text-left text-gray-500 font-medium px-6 py-3.5 hidden sm:table-cell">Email</th>
                    <th class="text-left text-gray-500 font-medium px-6 py-3.5">Rôle</th>
                    <th class="text-right text-gray-500 font-medium px-6 py-3.5">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800">
                @forelse($users as $user)
                    <tr class="hover:bg-white transition-colors">
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
                                   class="px-3 py-1.5 bg-white border border-gray-300 text-gray-700 text-xs rounded-lg hover:bg-gray-100 transition-colors">
                                    Modifier
                                </a>
                                @if(auth()->id() !== $user->id)
                                    <form method="POST" action="{{ route('users.destroy', $user) }}"
                                          onsubmit="return confirm('Supprimer cet utilisateur ?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="px-3 py-1.5 bg-red-500/10 border border-red-500/20 text-red-400 text-xs rounded-lg hover:bg-red-500/20 transition-colors">
                                            Supprimer
                                        </button>
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
