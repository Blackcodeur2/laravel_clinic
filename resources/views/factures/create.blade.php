<x-app-layout>
    <x-slot name="header">Nouvelle facture</x-slot>
    <x-slot name="title">Nouvelle facture</x-slot>

    <div class="max-w-2xl">
        <div class="rounded-2xl bg-gray-50 border border-gray-200 p-6">
            <p class="text-gray-500 text-sm mb-6">
                Sélectionnez une consultation sans facture. Une facture vide sera créée automatiquement, puis vous pourrez y ajouter les lignes.
            </p>
            <form method="POST" action="{{ route('factures.store') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-1.5">Consultation <span class="text-red-400">*</span></label>
                    <select name="consultation_id" required
                            class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-900 text-sm focus:outline-none focus:border-cyan-500 transition-colors @error('consultation_id') border-red-500 @enderror">
                        <option value="">-- Sélectionner une consultation --</option>
                        @foreach($consultations as $consultation)
                            <option value="{{ $consultation->id }}" {{ old('consultation_id') == $consultation->id ? 'selected' : '' }}>
                                {{ $consultation->patient?->prenom }} {{ $consultation->patient?->nom }}
                                — {{ \Carbon\Carbon::parse($consultation->date_consultation)->format('d/m/Y') }}
                            </option>
                        @endforeach
                    </select>
                    @error('consultation_id')<p class="mt-1 text-red-400 text-xs">{{ $message }}</p>@enderror
                </div>

                @if($consultations->isEmpty())
                    <div class="flex items-center gap-3 p-4 rounded-xl bg-amber-500/10 border border-amber-500/20 text-yellow-600 text-sm">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        Toutes les consultations ont déjà une facture.
                        <a href="{{ route('consultations.create') }}" class="underline">Créer une consultation</a>
                    </div>
                @endif

                <div class="flex gap-3 pt-2">
                    <button type="submit" {{ $consultations->isEmpty() ? 'disabled' : '' }}
                            class="px-6 py-2.5 bg-blue-600 hover:bg-cyan-400 text-gray-900 font-semibold text-sm rounded-xl transition-colors disabled:opacity-40 disabled:cursor-not-allowed">
                        Créer la facture
                    </button>
                    <a href="{{ route('factures.index') }}"
                       class="px-6 py-2.5 bg-white border border-gray-300 text-gray-700 text-sm rounded-xl hover:bg-gray-100 transition-colors">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
