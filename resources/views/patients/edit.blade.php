<x-app-layout>
    <x-slot name="header">Modifier le patient</x-slot>
    <x-slot name="title">Modifier le patient</x-slot>

    <div class="max-w-2xl">
        <div class="rounded-2xl bg-gray-50 border border-gray-200 p-6" x-data="{
            form: {
                prenom: '{{ old('prenom', $patient->prenom) }}',
                nom: '{{ old('nom', $patient->nom) }}',
                date_naissance: '{{ old('date_naissance', $patient->date_naissance) }}',
                sexe: '{{ old('sexe', $patient->sexe) }}',
                telephone: '{{ old('telephone', $patient->telephone) }}',
                adresse: '{{ old('adresse', $patient->adresse) }}'
            },
            errors: {},
            validate() {
                this.errors = {};
                if (!this.form.prenom || this.form.prenom.trim() === '') this.errors.prenom = 'Le prénom est requis.';
                else if (this.form.prenom.length > 255) this.errors.prenom = 'Le prénom ne doit pas dépasser 255 caractères.';
                if (!this.form.nom || this.form.nom.trim() === '') this.errors.nom = 'Le nom est requis.';
                else if (this.form.nom.length > 255) this.errors.nom = 'Le nom ne doit pas dépasser 255 caractères.';
                if (!this.form.date_naissance) this.errors.date_naissance = 'La date de naissance est requise.';
                else if (new Date(this.form.date_naissance) >= new Date()) this.errors.date_naissance = 'La date de naissance doit être antérieure à aujourd\'hui.';
                if (!this.form.sexe) this.errors.sexe = 'Le sexe est requis.';
                if (!this.form.telephone || this.form.telephone.trim() === '') this.errors.telephone = 'Le téléphone est requis.';
                else if (this.form.telephone.length > 50) this.errors.telephone = 'Le téléphone ne doit pas dépasser 50 caractères.';
                return Object.keys(this.errors).length === 0;
            }
        }" x-init="$watch('form', () => validate(), { deep: true })">
            <form method="POST" action="{{ route('patients.update', $patient) }}" class="space-y-5" @submit="if (!validate()) $event.preventDefault()">
                @csrf @method('PATCH')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-1.5">Prénom <span class="text-red-400">*</span></label>
                        <input type="text" name="prenom" x-model="form.prenom" required
                               :class="'w-full px-4 py-2.5 bg-white border rounded-xl text-gray-900 text-sm placeholder-slate-500 focus:outline-none transition-colors ' + (errors.prenom ? 'border-red-500 focus:border-red-500' : 'border-gray-300 focus:border-cyan-500')"/>
                        <p x-show="errors.prenom" x-text="errors.prenom" class="mt-1 text-red-400 text-xs"></p>
                        @error('prenom')<p class="mt-1 text-red-400 text-xs">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-1.5">Nom <span class="text-red-400">*</span></label>
                        <input type="text" name="nom" x-model="form.nom" required
                               :class="'w-full px-4 py-2.5 bg-white border rounded-xl text-gray-900 text-sm placeholder-slate-500 focus:outline-none transition-colors ' + (errors.nom ? 'border-red-500 focus:border-red-500' : 'border-gray-300 focus:border-cyan-500')"/>
                        <p x-show="errors.nom" x-text="errors.nom" class="mt-1 text-red-400 text-xs"></p>
                        @error('nom')<p class="mt-1 text-red-400 text-xs">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-1.5">Date de naissance <span class="text-red-400">*</span></label>
                        <input type="date" name="date_naissance" x-model="form.date_naissance" required
                               :class="'w-full px-4 py-2.5 bg-white border rounded-xl text-gray-900 text-sm focus:outline-none transition-colors ' + (errors.date_naissance ? 'border-red-500 focus:border-red-500' : 'border-gray-300 focus:border-cyan-500')"/>
                        <p x-show="errors.date_naissance" x-text="errors.date_naissance" class="mt-1 text-red-400 text-xs"></p>
                        @error('date_naissance')<p class="mt-1 text-red-400 text-xs">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-1.5">Sexe <span class="text-red-400">*</span></label>
                        <select name="sexe" x-model="form.sexe" required
                                :class="'w-full px-4 py-2.5 bg-white border rounded-xl text-gray-900 text-sm focus:outline-none transition-colors ' + (errors.sexe ? 'border-red-500 focus:border-red-500' : 'border-gray-300 focus:border-cyan-500')">
                            <option value="">-- Sélectionner --</option>
                            <option value="M">Masculin</option>
                            <option value="F">Féminin</option>
                        </select>
                        <p x-show="errors.sexe" x-text="errors.sexe" class="mt-1 text-red-400 text-xs"></p>
                        @error('sexe')<p class="mt-1 text-red-400 text-xs">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-1.5">Téléphone <span class="text-red-400">*</span></label>
                    <input type="text" name="telephone" x-model="form.telephone" required
                           :class="'w-full px-4 py-2.5 bg-white border rounded-xl text-gray-900 text-sm placeholder-slate-500 focus:outline-none transition-colors ' + (errors.telephone ? 'border-red-500 focus:border-red-500' : 'border-gray-300 focus:border-cyan-500')"/>
                    <p x-show="errors.telephone" x-text="errors.telephone" class="mt-1 text-red-400 text-xs"></p>
                    @error('telephone')<p class="mt-1 text-red-400 text-xs">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-1.5">Adresse</label>
                    <textarea name="adresse" x-model="form.adresse" rows="3"
                              class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-900 text-sm placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-colors resize-none"></textarea>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit"
                            class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-cyan-500 hover:from-blue-700 hover:to-cyan-600 text-white font-semibold text-sm rounded-xl transition-all shadow-lg shadow-blue-500/20">
                        Enregistrer
                    </button>
                    <a href="{{ route('patients.index') }}"
                       class="px-6 py-2.5 bg-white border border-gray-300 text-gray-700 text-sm rounded-xl hover:bg-gray-100 transition-colors">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
