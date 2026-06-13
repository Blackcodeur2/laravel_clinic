<x-app-layout>
    <x-slot name="header">Nouvel utilisateur</x-slot>
    <x-slot name="title">Nouvel utilisateur</x-slot>

    <div class="max-w-2xl">
        <div class="rounded-2xl bg-gray-50 border border-gray-200 p-6" x-data="{
            form: {
                prenom: '{{ old('prenom') }}',
                nom: '{{ old('nom') }}',
                username: '{{ old('username') }}',
                role_id: '{{ old('role_id') }}',
                email: '{{ old('email') }}',
                password: '',
                password_confirmation: ''
            },
            errors: {},
            validate() {
                this.errors = {};
                if (!this.form.prenom || this.form.prenom.trim() === '') this.errors.prenom = 'Le prénom est requis.';
                else if (this.form.prenom.length > 100) this.errors.prenom = 'Le prénom ne doit pas dépasser 100 caractères.';
                if (!this.form.nom || this.form.nom.trim() === '') this.errors.nom = 'Le nom est requis.';
                else if (this.form.nom.length > 100) this.errors.nom = 'Le nom ne doit pas dépasser 100 caractères.';
                if (!this.form.username || this.form.username.trim() === '') this.errors.username = 'Le nom d\'utilisateur est requis.';
                else if (this.form.username.length > 50) this.errors.username = 'Le nom d\'utilisateur ne doit pas dépasser 50 caractères.';
                if (!this.form.role_id) this.errors.role_id = 'Le rôle est requis.';
                if (!this.form.email || this.form.email.trim() === '') this.errors.email = 'L\'email est requis.';
                else if (!this.form.email.includes('@')) this.errors.email = 'L\'email doit être valide.';
                if (!this.form.password || this.form.password === '') this.errors.password = 'Le mot de passe est requis.';
                else if (this.form.password.length < 8) this.errors.password = 'Le mot de passe doit contenir au moins 8 caractères.';
                if (this.form.password !== this.form.password_confirmation) this.errors.password_confirmation = 'Les mots de passe ne correspondent pas.';
                return Object.keys(this.errors).length === 0;
            }
        }" x-init="$watch('form', () => validate(), { deep: true })">
            <form method="POST" action="{{ route('users.store') }}" class="space-y-5" enctype="multipart/form-data" @submit="if (!validate()) $event.preventDefault()">
                @csrf

                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-1.5">Prénom <span class="text-red-400">*</span></label>
                        <input type="text" name="prenom" x-model="form.prenom" required
                               :class="'w-full px-4 py-2.5 bg-white border rounded-xl text-gray-900 text-sm focus:outline-none transition-colors ' + (errors.prenom ? 'border-red-500 focus:border-red-500' : 'border-gray-300 focus:border-cyan-500')"/>
                        <p x-show="errors.prenom" x-text="errors.prenom" class="mt-1 text-red-400 text-xs"></p>
                        @error('prenom')<p class="mt-1 text-red-400 text-xs">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-1.5">Nom <span class="text-red-400">*</span></label>
                        <input type="text" name="nom" x-model="form.nom" required
                               :class="'w-full px-4 py-2.5 bg-white border rounded-xl text-gray-900 text-sm focus:outline-none transition-colors ' + (errors.nom ? 'border-red-500 focus:border-red-500' : 'border-gray-300 focus:border-cyan-500')"/>
                        <p x-show="errors.nom" x-text="errors.nom" class="mt-1 text-red-400 text-xs"></p>
                        @error('nom')<p class="mt-1 text-red-400 text-xs">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-1.5">Nom d'utilisateur <span class="text-red-400">*</span></label>
                        <input type="text" name="username" x-model="form.username" required
                               :class="'w-full px-4 py-2.5 bg-white border rounded-xl text-gray-900 text-sm font-mono focus:outline-none transition-colors ' + (errors.username ? 'border-red-500 focus:border-red-500' : 'border-gray-300 focus:border-cyan-500')"/>
                        <p x-show="errors.username" x-text="errors.username" class="mt-1 text-red-400 text-xs"></p>
                        @error('username')<p class="mt-1 text-red-400 text-xs">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-1.5">Rôle <span class="text-red-400">*</span></label>
                        <select name="role_id" x-model="form.role_id" required
                                :class="'w-full px-4 py-2.5 bg-white border rounded-xl text-gray-900 text-sm focus:outline-none transition-colors ' + (errors.role_id ? 'border-red-500 focus:border-red-500' : 'border-gray-300 focus:border-cyan-500')">
                            <option value="">-- Sélectionner --</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">
                                    {{ $role->nom }}
                                </option>
                            @endforeach
                        </select>
                        <p x-show="errors.role_id" x-text="errors.role_id" class="mt-1 text-red-400 text-xs"></p>
                        @error('role_id')<p class="mt-1 text-red-400 text-xs">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-1.5">Email <span class="text-red-400">*</span></label>
                    <input type="email" name="email" x-model="form.email" required
                           :class="'w-full px-4 py-2.5 bg-white border rounded-xl text-gray-900 text-sm focus:outline-none transition-colors ' + (errors.email ? 'border-red-500 focus:border-red-500' : 'border-gray-300 focus:border-cyan-500')"/>
                    <p x-show="errors.email" x-text="errors.email" class="mt-1 text-red-400 text-xs"></p>
                    @error('email')<p class="mt-1 text-red-400 text-xs">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-1.5">Photo de profil</label>
                    <input type="file" name="photo_profile" accept="image/*"
                           class="w-full px-4 py-2 bg-white border border-gray-300 rounded-xl text-gray-900 text-sm file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-colors @error('photo_profile') border-red-500 @enderror"/>
                    @error('photo_profile')<p class="mt-1 text-red-400 text-xs">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-1.5">Mot de passe <span class="text-red-400">*</span></label>
                        <input type="password" name="password" x-model="form.password" required
                               :class="'w-full px-4 py-2.5 bg-white border rounded-xl text-gray-900 text-sm focus:outline-none transition-colors ' + (errors.password ? 'border-red-500 focus:border-red-500' : 'border-gray-300 focus:border-cyan-500')"/>
                        <p x-show="errors.password" x-text="errors.password" class="mt-1 text-red-400 text-xs"></p>
                        @error('password')<p class="mt-1 text-red-400 text-xs">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-1.5">Confirmer <span class="text-red-400">*</span></label>
                        <input type="password" name="password_confirmation" x-model="form.password_confirmation" required
                               :class="'w-full px-4 py-2.5 bg-white border rounded-xl text-gray-900 text-sm focus:outline-none transition-colors ' + (errors.password_confirmation ? 'border-red-500 focus:border-red-500' : 'border-gray-300 focus:border-cyan-500')"/>
                        <p x-show="errors.password_confirmation" x-text="errors.password_confirmation" class="mt-1 text-red-400 text-xs"></p>
                    </div>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit"
                            class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-cyan-500 hover:from-blue-700 hover:to-cyan-600 text-white font-semibold text-sm rounded-xl transition-all shadow-lg shadow-blue-500/20">
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
