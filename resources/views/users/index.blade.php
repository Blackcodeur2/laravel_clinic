<x-app-layout>
    <x-slot name="header">Utilisateurs</x-slot>
    <x-slot name="title">Utilisateurs</x-slot>

    <div x-data="{
        search: '',
        roleId: '',
        currentPage: 1,
        pageSize: 6,
        users: [
            @foreach($users as $user)
            {
                id: {{ $user->id }},
                nom: '{{ addslashes($user->nom) }}',
                prenom: '{{ addslashes($user->prenom) }}',
                username: '{{ addslashes($user->username) }}',
                initials: '{{ strtoupper(substr($user->prenom, 0, 1)) }}{{ strtoupper(substr($user->nom, 0, 1)) }}',
                email: '{{ addslashes($user->email) }}',
                photo_profile: '{{ $user->photo_profile ? asset('storage/' . $user->photo_profile) : '' }}',
                role_nom: '{{ $user->role?->nom ?? '' }}',
                role_id: '{{ $user->role_id }}',
                edit_url: '{{ route('users.edit', $user) }}',
                destroy_url: '{{ route('users.destroy', $user) }}',
                is_current_user: {{ auth()->id() === $user->id ? 'true' : 'false' }}
            },
            @endforeach
        ],
        get filteredUsers() {
            return this.users.filter(u => {
                const term = this.search.toLowerCase().trim();
                const matchesSearch = u.nom.toLowerCase().includes(term) || 
                                      u.prenom.toLowerCase().includes(term) || 
                                      u.username.toLowerCase().includes(term) || 
                                      u.email.toLowerCase().includes(term);
                const matchesRole = this.roleId === '' || u.role_id === this.roleId;
                return matchesSearch && matchesRole;
            });
        },
        get paginatedUsers() {
            const start = (this.currentPage - 1) * this.pageSize;
            return this.filteredUsers.slice(start, start + this.pageSize);
        },
        get totalPages() {
            return Math.ceil(this.filteredUsers.length / this.pageSize) || 1;
        },
        init() {
            this.$watch('search', () => this.currentPage = 1);
            this.$watch('roleId', () => this.currentPage = 1);
        }
    }">
        <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
            <div class="flex flex-wrap gap-3">
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" x-model="search"
                           placeholder="Nom, email, username..."
                           class="pl-10 pr-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-900 text-sm placeholder-slate-400 focus:outline-none focus:border-cyan-500 w-64 transition-colors"/>
                </div>

                <select x-model="roleId"
                        class="px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-700 text-sm focus:outline-none focus:border-cyan-500 transition-colors">
                    <option value="">Tous les rôles</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->nom }}</option>
                    @endforeach
                </select>
            </div>

            <a href="{{ route('users.create') }}"
               class="flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-cyan-500 hover:from-blue-700 hover:to-cyan-600 text-white font-semibold text-sm rounded-xl transition-all shadow-lg shadow-blue-500/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Nouvel utilisateur
            </a>
        </div>

        <div class="rounded-2xl bg-white shadow-sm ring-1 ring-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm align-middle">
                    <thead class="bg-gray-50/50 border-b border-gray-150">
                        <tr>
                            <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Utilisateur</th>
                            <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4 hidden sm:table-cell">Email</th>
                            <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Rôle</th>
                            <th class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        <template x-for="user in paginatedUsers" :key="user.id">
                            <tr class="hover:bg-gray-50/50 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <template x-if="user.photo_profile">
                                            <img :src="user.photo_profile" class="w-8 h-8 rounded-full object-cover flex-shrink-0" />
                                        </template>
                                        <template x-if="!user.photo_profile">
                                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-violet-500 to-purple-700 flex items-center justify-center text-gray-900 text-xs font-bold flex-shrink-0" x-text="user.initials">
                                            </div>
                                        </template>
                                        <div>
                                            <p class="text-gray-900 font-medium" x-text="user.prenom + ' ' + user.nom"></p>
                                            <p class="text-gray-400 text-xs" x-text="'@' + user.username"></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-500 hidden sm:table-cell" x-text="user.email"></td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium border"
                                          :class="{
                                              'bg-violet-500/10 text-violet-400 border-violet-500/20': user.role_nom === 'ADMIN',
                                              'bg-blue-500/10 text-indigo-600 border-blue-500/20': user.role_nom === 'RESPONSABLE',
                                              'bg-emerald-500/10 text-green-600 border-emerald-500/20': user.role_nom === 'CAISSIER'
                                          }"
                                          x-text="user.role_nom || '—'">
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a :href="user.edit_url"
                                           class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors flex items-center justify-center"> 
                                            <i class='bi bi-pencil-square text-lg'></i>
                                        </a>
                                        <template x-if="!user.is_current_user">
                                            <form method="POST" :action="user.destroy_url"
                                                  onsubmit="return confirm('Supprimer cet utilisateur ?')">
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
                        <tr x-show="filteredUsers.length === 0">
                            <td colspan="4" class="text-center py-12 text-gray-400">Aucun utilisateur</td>
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
