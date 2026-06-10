<x-app-layout>
    <x-slot name="header">Modifier l'utilisateur</x-slot>
    <x-slot name="title">Modifier utilisateur</x-slot>

    <div class="max-w-2xl">
        <div class="rounded-2xl bg-gray-50 border border-gray-200 p-6">
            <form method="POST" action="{{ route('users.update', $user) }}" class="space-y-5">
                @csrf @method('PATCH')

                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-1.5">Prénom <span class="text-red-400">*</span></label>
                        <input type="text" name="prenom" value="{{ old('prenom', $user->prenom) }}" required
                               class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-900 text-sm focus:outline-none focus:border-cyan-500 transition-colors"/>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-1.5">Nom <span class="text-red-400">*</span></label>
                        <input type="text" name="nom" value="{{ old('nom', $user->nom) }}" required
                               class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-900 text-sm focus:outline-none focus:border-cyan-500 transition-colors"/>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-1.5">Nom d'utilisateur <span class="text-red-400">*</span></label>
                        <input type="text" name="username" value="{{ old('username', $user->username) }}" required
                               class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-900 text-sm font-mono focus:outline-none focus:border-cyan-500 transition-colors"/>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-1.5">Rôle <span class="text-red-400">*</span></label>
                        <select name="role_id" required
                                class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-900 text-sm focus:outline-none focus:border-cyan-500 transition-colors">
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                    {{ $role->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-1.5">Email <span class="text-red-400">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                           class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-900 text-sm focus:outline-none focus:border-cyan-500 transition-colors"/>
                </div>

                <div class="rounded-xl bg-white border border-gray-300 p-4">
                    <p class="text-gray-500 text-xs mb-3">Laisser vide pour conserver le mot de passe actuel</p>
                    <div class="grid grid-cols-2 gap-5">
                        <div>
                            <label class="block text-gray-700 text-sm font-medium mb-1.5">Nouveau mot de passe</label>
                            <input type="password" name="password"
                                   class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-900 text-sm focus:outline-none focus:border-cyan-500 transition-colors"/>
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-medium mb-1.5">Confirmer</label>
                            <input type="password" name="password_confirmation"
                                   class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-900 text-sm focus:outline-none focus:border-cyan-500 transition-colors"/>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit"
                            class="px-6 py-2.5 bg-blue-600 hover:bg-cyan-400 text-gray-900 font-semibold text-sm rounded-xl transition-colors">
                        Enregistrer
                    </button>
                    <a href="{{ route('users.index') }}"
                       class="px-6 py-2.5 bg-white border border-gray-300 text-gray-700 text-sm rounded-xl hover:bg-gray-100 transition-colors">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
