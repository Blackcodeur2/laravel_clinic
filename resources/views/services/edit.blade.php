<x-app-layout>
    <x-slot name="header">Modifier le service</x-slot>
    <x-slot name="title">Modifier service</x-slot>

    <div class="max-w-2xl">
        <div class="rounded-2xl bg-gray-50 border border-gray-200 p-6" x-data="{
            form: {
                code: '{{ old('code', $service->code) }}',
                prix: '{{ old('prix', $service->prix) }}',
                nom: '{{ old('nom', $service->nom) }}',
                description: '{{ old('description', $service->description) }}'
            },
            errors: {},
            validate() {
                this.errors = {};
                if (!this.form.code || this.form.code.trim() === '') this.errors.code = 'Le code est requis.';
                else if (this.form.code.length > 100) this.errors.code = 'Le code ne doit pas dépasser 100 caractères.';
                if (!this.form.nom || this.form.nom.trim() === '') this.errors.nom = 'Le nom est requis.';
                else if (this.form.nom.length > 255) this.errors.nom = 'Le nom ne doit pas dépasser 255 caractères.';
                if (!this.form.prix || this.form.prix === '') this.errors.prix = 'Le prix est requis.';
                else if (parseFloat(this.form.prix) < 0) this.errors.prix = 'Le prix doit être supérieur ou égal à 0.';
                return Object.keys(this.errors).length === 0;
            }
        }" x-init="$watch('form', () => validate(), { deep: true })">
            <form method="POST" action="{{ route('services.update', $service) }}" class="space-y-5" @submit="if (!validate()) $event.preventDefault()">
                @csrf @method('PATCH')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-1.5">Code <span class="text-red-400">*</span></label>
                        <input type="text" name="code" x-model="form.code" required
                               :class="'w-full px-4 py-2.5 bg-white border rounded-xl text-gray-900 text-sm font-mono focus:outline-none transition-colors ' + (errors.code ? 'border-red-500 focus:border-red-500' : 'border-gray-300 focus:border-cyan-500')"/>
                        <p x-show="errors.code" x-text="errors.code" class="mt-1 text-red-400 text-xs"></p>
                        @error('code')<p class="mt-1 text-red-400 text-xs">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-1.5">Prix (FCFA) <span class="text-red-400">*</span></label>
                        <input type="number" name="prix" x-model="form.prix" required step="0.01" min="0"
                               :class="'w-full px-4 py-2.5 bg-white border rounded-xl text-gray-900 text-sm focus:outline-none transition-colors ' + (errors.prix ? 'border-red-500 focus:border-red-500' : 'border-gray-300 focus:border-cyan-500')"/>
                        <p x-show="errors.prix" x-text="errors.prix" class="mt-1 text-red-400 text-xs"></p>
                    </div>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-1.5">Nom du service <span class="text-red-400">*</span></label>
                    <input type="text" name="nom" x-model="form.nom" required
                           :class="'w-full px-4 py-2.5 bg-white border rounded-xl text-gray-900 text-sm focus:outline-none transition-colors ' + (errors.nom ? 'border-red-500 focus:border-red-500' : 'border-gray-300 focus:border-cyan-500')"/>
                    <p x-show="errors.nom" x-text="errors.nom" class="mt-1 text-red-400 text-xs"></p>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-1.5">Description</label>
                    <textarea name="description" x-model="form.description" rows="3"
                              class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-900 text-sm focus:outline-none focus:border-cyan-500 transition-colors resize-none"></textarea>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit"
                            class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-cyan-500 hover:from-blue-700 hover:to-cyan-600 text-white font-semibold text-sm rounded-xl transition-all shadow-lg shadow-blue-500/20">
                        Enregistrer
                    </button>
                    <a href="{{ route('services.index') }}"
                       class="px-6 py-2.5 bg-white border border-gray-300 text-gray-700 text-sm rounded-xl hover:bg-gray-100 transition-colors">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
