<x-app-layout>
    <x-slot name="header">Nouveau patient</x-slot>
    <x-slot name="title">Nouveau patient</x-slot>

    <div class="max-w-2xl">
        <div class="rounded-2xl bg-gray-50 border border-gray-200 p-6">
            <form method="POST" action="{{ route('patients.store') }}" class="space-y-5">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-1.5">Prénom <span class="text-red-400">*</span></label>
                        <input type="text" name="prenom" value="{{ old('prenom') }}" required
                               class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-900 text-sm placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-colors @error('prenom') border-red-500 @enderror"/>
                        @error('prenom')<p class="mt-1 text-red-400 text-xs">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-1.5">Nom <span class="text-red-400">*</span></label>
                        <input type="text" name="nom" value="{{ old('nom') }}" required
                               class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-900 text-sm placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-colors @error('nom') border-red-500 @enderror"/>
                        @error('nom')<p class="mt-1 text-red-400 text-xs">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-1.5">Date de naissance <span class="text-red-400">*</span></label>
                        <input type="date" name="date_naissance" value="{{ old('date_naissance') }}" required
                               class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-900 text-sm focus:outline-none focus:border-cyan-500 transition-colors @error('date_naissance') border-red-500 @enderror"/>
                        @error('date_naissance')<p class="mt-1 text-red-400 text-xs">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-1.5">Sexe <span class="text-red-400">*</span></label>
                        <select name="sexe" required
                                class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-900 text-sm focus:outline-none focus:border-cyan-500 transition-colors @error('sexe') border-red-500 @enderror">
                            <option value="">-- Sélectionner --</option>
                            <option value="M" {{ old('sexe') === 'M' ? 'selected' : '' }}>Masculin</option>
                            <option value="F" {{ old('sexe') === 'F' ? 'selected' : '' }}>Féminin</option>
                        </select>
                        @error('sexe')<p class="mt-1 text-red-400 text-xs">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-1.5">Téléphone <span class="text-red-400">*</span></label>
                    <input type="text" name="telephone" value="{{ old('telephone') }}" required
                           class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-900 text-sm placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-colors @error('telephone') border-red-500 @enderror"/>
                    @error('telephone')<p class="mt-1 text-red-400 text-xs">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-1.5">Adresse</label>
                    <textarea name="adresse" rows="3"
                              class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-900 text-sm placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-colors resize-none">{{ old('adresse') }}</textarea>
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
