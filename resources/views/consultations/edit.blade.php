<x-app-layout>
    <x-slot name="header">Modifier la consultation</x-slot>
    <x-slot name="title">Modifier consultation</x-slot>

    <div class="max-w-2xl">
        <div class="rounded-2xl bg-gray-50 border border-gray-200 p-6">
            <form method="POST" action="{{ route('consultations.update', $consultation) }}" class="space-y-5">
                @csrf @method('PATCH')

                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-1.5">Patient <span class="text-red-400">*</span></label>
                    <select name="patient_id" required
                            class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-900 text-sm focus:outline-none focus:border-cyan-500 transition-colors">
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" {{ old('patient_id', $consultation->patient_id) == $patient->id ? 'selected' : '' }}>
                                {{ $patient->prenom }} {{ $patient->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-1.5">Médecin responsable <span class="text-red-400">*</span></label>
                    <select name="medecin_id" required
                            class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-900 text-sm focus:outline-none focus:border-cyan-500 transition-colors">
                        @foreach($medecins as $medecin)
                            <option value="{{ $medecin->id }}" {{ old('medecin_id', $consultation->medecin_id) == $medecin->id ? 'selected' : '' }}>
                                Dr. {{ $medecin->prenom }} {{ $medecin->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-1.5">Date et heure <span class="text-red-400">*</span></label>
                    <input type="datetime-local" name="date_consultation" required
                           value="{{ old('date_consultation', \Carbon\Carbon::parse($consultation->date_consultation)->format('Y-m-d\TH:i')) }}"
                           class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-900 text-sm focus:outline-none focus:border-cyan-500 transition-colors"/>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit"
                            class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-cyan-500 hover:from-blue-700 hover:to-cyan-600 text-white font-semibold text-sm rounded-xl transition-all shadow-lg shadow-blue-500/20">
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
