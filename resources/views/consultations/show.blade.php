<x-app-layout>
    <x-slot name="header">Consultation</x-slot>
    <x-slot name="title">Détail consultation</x-slot>

    <div class="max-w-3xl space-y-6">
        <div class="rounded-2xl bg-gray-50 border border-gray-200 p-6">
            <div class="grid grid-cols-2 gap-6 mb-6">
                <div>
                    <p class="text-gray-500 text-xs mb-1">Patient</p>
                    <p class="text-gray-900 font-semibold">{{ $consultation->patient?->prenom }} {{ $consultation->patient?->nom }}</p>
                    <p class="text-gray-500 text-sm">{{ $consultation->patient?->telephone }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-xs mb-1">Médecin</p>
                    <p class="text-gray-900 font-semibold">Dr. {{ $consultation->medecin?->prenom }} {{ $consultation->medecin?->nom }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-xs mb-1">Date de consultation</p>
                    <p class="text-gray-900">{{ \Carbon\Carbon::parse($consultation->date_consultation)->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-xs mb-1">Facture</p>
                    @if($consultation->facture)
                        <a href="{{ route('factures.show', $consultation->facture) }}"
                           class="text-blue-600 hover:text-cyan-300 font-mono font-medium transition-colors">
                            {{ $consultation->facture->numero_facture }}
                        </a>
                        <x-statut-badge :statut="$consultation->facture->statut" class="ml-2"/>
                    @else
                        <span class="text-gray-400">Pas de facture</span>
                    @endif
                </div>
            </div>

            <div class="flex gap-3">
                @can('update', $consultation)
                    <a href="{{ route('consultations.edit', $consultation) }}"
                       class="px-4 py-2.5 bg-white border border-gray-300 text-gray-700 text-sm rounded-xl hover:bg-gray-100 transition-colors">
                        Modifier
                    </a>
                @endcan
                @if($consultation->facture)
                    <a href="{{ route('factures.show', $consultation->facture) }}"
                       class="px-4 py-2.5 bg-blue-600/10 border border-cyan-500/20 text-blue-600 text-sm rounded-xl hover:bg-blue-600/20 transition-colors">
                        Gérer la facture
                    </a>
                @endif
                <a href="{{ route('consultations.index') }}"
                   class="px-4 py-2.5 bg-white border border-gray-300 text-gray-700 text-sm rounded-xl hover:bg-gray-100 transition-colors">
                    Retour
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
