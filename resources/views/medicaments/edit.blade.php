<x-app-layout>
    <x-slot name="header">Modifier le médicament</x-slot>
    <x-slot name="title">Modifier médicament</x-slot>

    <div class="max-w-2xl">
        <div class="rounded-2xl bg-slate-900 border border-slate-800 p-6">
            <form method="POST" action="{{ route('medicaments.update', $medicament) }}" class="space-y-5">
                @csrf @method('PATCH')

                <div>
                    <label class="block text-slate-300 text-sm font-medium mb-1.5">Nom <span class="text-red-400">*</span></label>
                    <input type="text" name="nom" value="{{ old('nom', $medicament->nom) }}" required
                           class="w-full px-4 py-2.5 bg-slate-800 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:border-cyan-500 transition-colors"/>
                </div>

                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="block text-slate-300 text-sm font-medium mb-1.5">Prix unitaire (FCFA) <span class="text-red-400">*</span></label>
                        <input type="number" name="prix" value="{{ old('prix', $medicament->prix) }}" required step="0.01" min="0"
                               class="w-full px-4 py-2.5 bg-slate-800 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:border-cyan-500 transition-colors"/>
                    </div>
                    <div>
                        <label class="block text-slate-300 text-sm font-medium mb-1.5">Stock <span class="text-red-400">*</span></label>
                        <input type="number" name="stock" value="{{ old('stock', $medicament->stock) }}" required min="0"
                               class="w-full px-4 py-2.5 bg-slate-800 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:border-cyan-500 transition-colors"/>
                    </div>
                </div>

                <div>
                    <label class="block text-slate-300 text-sm font-medium mb-1.5">Description</label>
                    <textarea name="description" rows="3"
                              class="w-full px-4 py-2.5 bg-slate-800 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:border-cyan-500 transition-colors resize-none">{{ old('description', $medicament->description) }}</textarea>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit"
                            class="px-6 py-2.5 bg-cyan-500 hover:bg-cyan-400 text-slate-900 font-semibold text-sm rounded-xl transition-colors">
                        Enregistrer
                    </button>
                    <a href="{{ route('medicaments.index') }}"
                       class="px-6 py-2.5 bg-slate-800 border border-slate-700 text-slate-300 text-sm rounded-xl hover:bg-slate-700 transition-colors">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
