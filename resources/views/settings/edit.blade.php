<x-app-layout>
    <x-slot name="header">Paramètres de la clinique</x-slot>
    <x-slot name="title">Paramètres clinique</x-slot>

    <div class="max-w-4xl">
        <div class="rounded-2xl bg-white border border-gray-200 p-6 shadow-sm">
            <form method="POST" action="{{ route('settings.update') }}" class="space-y-6" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-100 pb-2 mb-4">Informations Générales</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-gray-700 text-sm font-medium mb-1.5">Nom de la clinique <span class="text-red-400">*</span></label>
                            <input type="text" name="nom_clinique" value="{{ old('nom_clinique', $settings->nom_clinique) }}" required
                                   class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-900 text-sm focus:outline-none focus:border-cyan-500 transition-colors @error('nom_clinique') border-red-500 @enderror"/>
                            @error('nom_clinique')<p class="mt-1 text-red-500 text-xs">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-medium mb-1.5">Slogan / Sous-titre</label>
                            <input type="text" name="slogan" value="{{ old('slogan', $settings->slogan) }}"
                                   class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-900 text-sm focus:outline-none focus:border-cyan-500 transition-colors @error('slogan') border-red-500 @enderror"/>
                            @error('slogan')<p class="mt-1 text-red-500 text-xs">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-100 pb-2 mb-4">Coordonnées & Contact</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-gray-700 text-sm font-medium mb-1.5">Adresse</label>
                            <input type="text" name="adresse" value="{{ old('adresse', $settings->adresse) }}"
                                   class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-900 text-sm focus:outline-none focus:border-cyan-500 transition-colors @error('adresse') border-red-500 @enderror"/>
                            @error('adresse')<p class="mt-1 text-red-500 text-xs">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-medium mb-1.5">Ville</label>
                            <input type="text" name="ville" value="{{ old('ville', $settings->ville) }}"
                                   class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-900 text-sm focus:outline-none focus:border-cyan-500 transition-colors @error('ville') border-red-500 @enderror"/>
                            @error('ville')<p class="mt-1 text-red-500 text-xs">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-medium mb-1.5">Téléphone</label>
                            <input type="text" name="telephone" value="{{ old('telephone', $settings->telephone) }}"
                                   class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-900 text-sm focus:outline-none focus:border-cyan-500 transition-colors @error('telephone') border-red-500 @enderror"/>
                            @error('telephone')<p class="mt-1 text-red-500 text-xs">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-medium mb-1.5">Adresse e-mail</label>
                            <input type="email" name="email" value="{{ old('email', $settings->email) }}"
                                   class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-900 text-sm focus:outline-none focus:border-cyan-500 transition-colors @error('email') border-red-500 @enderror"/>
                            @error('email')<p class="mt-1 text-red-500 text-xs">{{ $message }}</p>@enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-gray-700 text-sm font-medium mb-1.5">Site Web</label>
                            <input type="url" name="site_web" value="{{ old('site_web', $settings->site_web) }}" placeholder="https://example.com"
                                   class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-900 text-sm focus:outline-none focus:border-cyan-500 transition-colors @error('site_web') border-red-500 @enderror"/>
                            @error('site_web')<p class="mt-1 text-red-500 text-xs">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-100 pb-2 mb-4">Identité Visuelle</h3>
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-1.5">Logo de la clinique</label>
                        <div class="flex items-center gap-6">
                            @if($settings->logo_path)
                                <div class="relative group">
                                    <img src="{{ asset('storage/' . $settings->logo_path) }}" class="w-20 h-20 rounded-xl object-contain border border-gray-200 bg-slate-50 p-1" />
                                </div>
                            @else
                                <div class="w-20 h-20 rounded-xl bg-gray-100 flex flex-col items-center justify-center text-gray-400 border-2 border-dashed border-gray-300">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif
                            <div class="flex-1">
                                <input type="file" name="logo" accept="image/*"
                                       class="w-full px-4 py-2 bg-white border border-gray-300 rounded-xl text-gray-900 text-sm file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-colors @error('logo') border-red-500 @enderror"/>
                                <p class="text-gray-500 text-xs mt-1.5">Format supporté: JPEG, PNG, WebP. Taille maximale: 2 Mo.</p>
                                @error('logo')<p class="mt-1 text-red-500 text-xs">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 pt-4 border-t border-gray-100">
                    <button type="submit"
                            class="px-6 py-2.5 bg-blue-600 hover:bg-cyan-400 text-gray-900 font-semibold text-sm rounded-xl transition-colors shadow-lg shadow-blue-500/10">
                        Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
