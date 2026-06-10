<x-app-layout>
    <x-slot name="header">Nouvel utilisateur</x-slot>
    <x-slot name="title">Nouvel utilisateur</x-slot>

    <div class="max-w-2xl">
        <div class="rounded-2xl bg-gray-50 border border-gray-200 p-6">
            <form method="POST" action="{{ route('users.store') }}" class="space-y-5">
                @csrf

                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-1.5">Prénom <span class="text-red-400">*</span></label>
                        <input type="text" name="prenom" value="{{ old('prenom') }}" required
                               class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-900 text-sm focus:outline-none focus:border-cyan-500 transition-colors @error('prenom') border-red-500 @enderror"/>
                        @error('prenom')<p class="mt-1 text-red-400 text-xs">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-1.5">Nom <span class="text-red-400">*</span></label>
                        <input type="text" name="nom" value="{{ old('nom') }}" required
                               class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-900 text-sm focus:outline-none focus:border-cyan-500 transition-colors @error('nom') border-red-500 @enderror"/>
                        @error('nom')<p class="mt-1 text-red-400 text-xs">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-1.5">Nom d'utilisateur <span class="text-red-400">*</span></label>
                        <input type="text" name="username" value="{{ old('username') }}" required
                               class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-900 text-sm font-mono focus:outline-none focus:border-cyan-500 transition-colors @error('username') border-red-500 @enderror"/>
                        @error('username')<p class="mt-1 text-red-400 text-xs">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-1.5">Rôle <span class="text-red-400">*</span></label>
                        <select name="role_id" required
                                class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-900 text-sm focus:outline-none focus:border-cyan-500 transition-colors @error('role_id') border-red-500 @enderror">
                            <option value="">-- Sélectionner --</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                    {{ $role->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('role_id')<p class="mt-1 text-red-400 text-xs">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-1.5">Email <span class="text-red-400">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-900 text-sm focus:outline-none focus:border-cyan-500 transition-colors @error('email') border-red-500 @enderror"/>
                    @error('email')<p class="mt-1 text-red-400 text-xs">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-1.5">Mot de passe <span class="text-red-400">*</span></label>
                        <input type="password" name="password" required
                               class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-900 text-sm focus:outline-none focus:border-cyan-500 transition-colors @error('password') border-red-500 @enderror"/>
                        @error('password')<p class="mt-1 text-red-400 text-xs">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-1.5">Confirmer <span class="text-red-400">*</span></label>
                        <input type="password" name="password_confirmation" required
                               class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-900 text-sm focus:outline-none focus:border-cyan-500 transition-colors"/>
                    </div>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit"
                            class="px-6 py-2.5 bg-blue-600 hover:bg-cyan-400 text-gray-900 font-semibold text-sm rounded-xl transition-colors">
                        Créer l'utilisateur
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
