<x-app-layout>
    <x-slot name="header">Nouveau médicament</x-slot>
    <x-slot name="title">Nouveau médicament</x-slot>

    <div class="max-w-2xl">
        <div class="rounded-2xl bg-gray-50 border border-gray-200 p-6">
            <form method="POST" action="{{ route('medicaments.store') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-1.5">Nom <span class="text-red-400">*</span></label>
                    <input type="text" name="nom" value="{{ old('nom') }}" required
                           class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-900 text-sm placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-colors @error('nom') border-red-500 @enderror"/>
                    @error('nom')<p class="mt-1 text-red-400 text-xs">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-1.5">Prix unitaire (FCFA) <span class="text-red-400">*</span></label>
                        <input type="number" name="prix" value="{{ old('prix') }}" required step="0.01" min="0"
                               class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-900 text-sm focus:outline-none focus:border-cyan-500 transition-colors @error('prix') border-red-500 @enderror"/>
                        @error('prix')<p class="mt-1 text-red-400 text-xs">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-1.5">Stock initial <span class="text-red-400">*</span></label>
                        <input type="number" name="stock" value="{{ old('stock', 0) }}" required min="0"
                               class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-900 text-sm focus:outline-none focus:border-cyan-500 transition-colors @error('stock') border-red-500 @enderror"/>
                        @error('stock')<p class="mt-1 text-red-400 text-xs">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-1.5">Description</label>
                    <textarea name="description" rows="3"
                              class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-900 text-sm placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-colors resize-none">{{ old('description') }}</textarea>
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
