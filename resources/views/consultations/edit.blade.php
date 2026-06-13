<x-app-layout>
    <x-slot name="header">Modifier la consultation</x-slot>
    <x-slot name="title">Modifier consultation</x-slot>

    <div class="max-w-2xl">
        <div class="rounded-2xl bg-gray-50 border border-gray-200 p-6" x-data="{
            form: {
                patient_id: '{{ old('patient_id', $consultation->patient_id) }}',
                medecin_id: '{{ old('medecin_id', $consultation->medecin_id) }}',
                date_consultation: '{{ old('date_consultation', \Carbon\Carbon::parse($consultation->date_consultation)->format('Y-m-d\TH:i')) }}'
            },
            errors: {},
            validate() {
                this.errors = {};
                if (!this.form.patient_id) this.errors.patient_id = 'Le patient est requis.';
                if (!this.form.medecin_id) this.errors.medecin_id = 'Le médecin est requis.';
                if (!this.form.date_consultation) this.errors.date_consultation = 'La date de consultation est requise.';
                return Object.keys(this.errors).length === 0;
            }
        }" x-init="$watch('form', () => validate(), { deep: true })">
            <form method="POST" action="{{ route('consultations.update', $consultation) }}" class="space-y-5" @submit="if (!validate()) $event.preventDefault()">
                @csrf @method('PATCH')

                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-1.5">Patient <span class="text-red-400">*</span></label>
                    <select name="patient_id" x-model="form.patient_id" required
                            :class="'w-full px-4 py-2.5 bg-white border rounded-xl text-gray-900 text-sm focus:outline-none transition-colors ' + (errors.patient_id ? 'border-red-500 focus:border-red-500' : 'border-gray-300 focus:border-cyan-500')">
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}">
                                {{ $patient->prenom }} {{ $patient->nom }}
                            </option>
                        @endforeach
                    </select>
                    <p x-show="errors.patient_id" x-text="errors.patient_id" class="mt-1 text-red-400 text-xs"></p>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-1.5">Médecin responsable <span class="text-red-400">*</span></label>
                    <select name="medecin_id" x-model="form.medecin_id" required
                            :class="'w-full px-4 py-2.5 bg-white border rounded-xl text-gray-900 text-sm focus:outline-none transition-colors ' + (errors.medecin_id ? 'border-red-500 focus:border-red-500' : 'border-gray-300 focus:border-cyan-500')">
                        @foreach($medecins as $medecin)
                            <option value="{{ $medecin->id }}">
                                Dr. {{ $medecin->prenom }} {{ $medecin->nom }}
                            </option>
                        @endforeach
                    </select>
                    <p x-show="errors.medecin_id" x-text="errors.medecin_id" class="mt-1 text-red-400 text-xs"></p>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-1.5">Date et heure <span class="text-red-400">*</span></label>
                    <input type="datetime-local" name="date_consultation" x-model="form.date_consultation" required
                           :class="'w-full px-4 py-2.5 bg-white border rounded-xl text-gray-900 text-sm focus:outline-none transition-colors ' + (errors.date_consultation ? 'border-red-500 focus:border-red-500' : 'border-gray-300 focus:border-cyan-500')"/>
                    <p x-show="errors.date_consultation" x-text="errors.date_consultation" class="mt-1 text-red-400 text-xs"></p>
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
