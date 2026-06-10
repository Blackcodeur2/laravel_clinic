<x-app-layout>
    <x-slot name="header">Nouvelle consultation</x-slot>
    <x-slot name="title">Nouvelle consultation</x-slot>

    <div class="max-w-2xl">
        <div class="rounded-2xl bg-gray-50 border border-gray-200 p-6">
            <p class="text-gray-500 text-sm mb-6">
                Une facture vide est automatiquement créée lors de l'enregistrement de la consultation.
            </p>
            <form method="POST" action="{{ route('consultations.store') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-1.5">Patient <span class="text-red-400">*</span></label>
                    <select name="patient_id" required
                            class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-900 text-sm focus:outline-none focus:border-cyan-500 transition-colors @error('patient_id') border-red-500 @enderror">
                        <option value="">-- Sélectionner un patient --</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                {{ $patient->prenom }} {{ $patient->nom }}
                            </option>
                        @endforeach
                    </select>
                    @error('patient_id')<p class="mt-1 text-red-400 text-xs">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-1.5">Médecin responsable <span class="text-red-400">*</span></label>
                    <select name="medecin_id" required
                            class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-900 text-sm focus:outline-none focus:border-cyan-500 transition-colors @error('medecin_id') border-red-500 @enderror">
                        <option value="">-- Sélectionner un médecin --</option>
                        @foreach($medecins as $medecin)
                            <option value="{{ $medecin->id }}" {{ old('medecin_id') == $medecin->id ? 'selected' : '' }}>
                                Dr. {{ $medecin->prenom }} {{ $medecin->nom }}
                            </option>
                        @endforeach
                    </select>
                    @error('medecin_id')<p class="mt-1 text-red-400 text-xs">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-1.5">Date et heure <span class="text-red-400">*</span></label>
                    <input type="datetime-local" name="date_consultation"
                           value="{{ old('date_consultation', now()->format('Y-m-d\TH:i')) }}" required
                           class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-900 text-sm focus:outline-none focus:border-cyan-500 transition-colors @error('date_consultation') border-red-500 @enderror"/>
                    @error('date_consultation')<p class="mt-1 text-red-400 text-xs">{{ $message }}</p>@enderror
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit"
                            class="px-6 py-2.5 bg-blue-600 hover:bg-cyan-400 text-gray-900 font-semibold text-sm rounded-xl transition-colors">
                        Enregistrer
                    </button>
                    <a href="{{ route('consultations.index') }}"
                       class="px-6 py-2.5 bg-white border border-gray-300 text-gray-700 text-sm rounded-xl hover:bg-gray-100 transition-colors">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
