<x-app-layout>
    <x-slot name="header">Nouveau médicament</x-slot>
    <x-slot name="title">Nouveau médicament</x-slot>

    <div class="max-w-2xl">
        <div class="rounded-2xl bg-gray-50 border border-gray-200 p-6" x-data="{
            form: {
                nom: '{{ old('nom') }}',
                prix: '{{ old('prix') }}',
                stock: '{{ old('stock', 0) }}',
                description: '{{ old('description') }}',
                date_peremption: '{{ old('date_peremption') }}',
                stock_alerte: '{{ old('stock_alerte', 15) }}'
            },
            errors: {},
            validate() {
                this.errors = {};
                if (!this.form.nom || this.form.nom.trim() === '') this.errors.nom = 'Le nom est requis.';
                else if (this.form.nom.length > 255) this.errors.nom = 'Le nom ne doit pas dépasser 255 caractères.';
                if (!this.form.prix || this.form.prix === '') this.errors.prix = 'Le prix est requis.';
                else if (parseFloat(this.form.prix) < 0) this.errors.prix = 'Le prix doit être supérieur ou égal à 0.';
                if (this.form.stock === '' || this.form.stock === null) this.errors.stock = 'Le stock est requis.';
                else if (parseInt(this.form.stock) < 0) this.errors.stock = 'Le stock doit être supérieur ou égal à 0.';
                if (this.form.date_peremption && new Date(this.form.date_peremption) < new Date().setHours(0,0,0,0)) this.errors.date_peremption = 'La date de péremption doit être ultérieure ou égale à aujourd\'hui.';
                if (this.form.stock_alerte && parseInt(this.form.stock_alerte) < 1) this.errors.stock_alerte = 'Le seuil d\'alerte doit être supérieur à 0.';
                return Object.keys(this.errors).length === 0;
            }
        }" x-init="$watch('form', () => validate(), { deep: true })">
            <form method="POST" action="{{ route('medicaments.store') }}" class="space-y-5" @submit="if (!validate()) $event.preventDefault()">
                @csrf

                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-1.5">Nom <span class="text-red-400">*</span></label>
                    <input type="text" name="nom" x-model="form.nom" required
                           :class="'w-full px-4 py-2.5 bg-white border rounded-xl text-gray-900 text-sm placeholder-slate-500 focus:outline-none transition-colors ' + (errors.nom ? 'border-red-500 focus:border-red-500' : 'border-gray-300 focus:border-cyan-500')"/>
                    <p x-show="errors.nom" x-text="errors.nom" class="mt-1 text-red-400 text-xs"></p>
                    @error('nom')<p class="mt-1 text-red-400 text-xs">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-1.5">Prix unitaire (FCFA) <span class="text-red-400">*</span></label>
                        <input type="number" name="prix" x-model="form.prix" required step="0.01" min="0"
                               :class="'w-full px-4 py-2.5 bg-white border rounded-xl text-gray-900 text-sm focus:outline-none transition-colors ' + (errors.prix ? 'border-red-500 focus:border-red-500' : 'border-gray-300 focus:border-cyan-500')"/>
                        <p x-show="errors.prix" x-text="errors.prix" class="mt-1 text-red-400 text-xs"></p>
                        @error('prix')<p class="mt-1 text-red-400 text-xs">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-1.5">Stock initial <span class="text-red-400">*</span></label>
                        <input type="number" name="stock" x-model="form.stock" required min="0"
                               :class="'w-full px-4 py-2.5 bg-white border rounded-xl text-gray-900 text-sm focus:outline-none transition-colors ' + (errors.stock ? 'border-red-500 focus:border-red-500' : 'border-gray-300 focus:border-cyan-500')"/>
                        <p x-show="errors.stock" x-text="errors.stock" class="mt-1 text-red-400 text-xs"></p>
                        @error('stock')<p class="mt-1 text-red-400 text-xs">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-1.5">Description</label>
                    <textarea name="description" x-model="form.description" rows="3"
                              class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-900 text-sm placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-colors resize-none"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-1.5">Date de péremption</label>
                        <input type="date" name="date_peremption" x-model="form.date_peremption" min="{{ date('Y-m-d') }}"
                               :class="'w-full px-4 py-2.5 bg-white border rounded-xl text-gray-900 text-sm focus:outline-none transition-colors ' + (errors.date_peremption ? 'border-red-500 focus:border-red-500' : 'border-gray-300 focus:border-cyan-500')"/>
                        <p x-show="errors.date_peremption" x-text="errors.date_peremption" class="mt-1 text-red-400 text-xs"></p>
                        @error('date_peremption')<p class="mt-1 text-red-400 text-xs">{{ $message }}</p>@enderror
                        <p class="mt-1 text-gray-400 text-xs">Laisser vide si pas de date connue</p>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-1.5">Seuil d'alerte stock</label>
                        <input type="number" name="stock_alerte" x-model="form.stock_alerte" min="1"
                               :class="'w-full px-4 py-2.5 bg-white border rounded-xl text-gray-900 text-sm focus:outline-none transition-colors ' + (errors.stock_alerte ? 'border-red-500 focus:border-red-500' : 'border-gray-300 focus:border-cyan-500')"/>
                        <p x-show="errors.stock_alerte" x-text="errors.stock_alerte" class="mt-1 text-red-400 text-xs"></p>
                        @error('stock_alerte')<p class="mt-1 text-red-400 text-xs">{{ $message }}</p>@enderror
                        <p class="mt-1 text-gray-400 text-xs">Alerte si stock ≤ ce seuil</p>
                    </div>
                </div>


                <div class="flex gap-3 pt-2">
                    <button type="submit"
                            class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-cyan-500 hover:from-blue-700 hover:to-cyan-600 text-white font-semibold text-sm rounded-xl transition-all shadow-lg shadow-blue-500/20">
                        Enregistrer
                    </button>
                    <a href="{{ route('medicaments.index') }}"
                       class="px-6 py-2.5 bg-white border border-gray-300 text-gray-700 text-sm rounded-xl hover:bg-gray-100 transition-colors">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
