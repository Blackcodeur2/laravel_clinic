<x-app-layout>
    <x-slot name="header">Consultation</x-slot>
    <x-slot name="title">Détail consultation</x-slot>

    <div class="max-w-3xl space-y-6">
        <div class="rounded-2xl bg-slate-900 border border-slate-800 p-6">
            <div class="grid grid-cols-2 gap-6 mb-6">
                <div>
                    <p class="text-slate-400 text-xs mb-1">Patient</p>
                    <p class="text-white font-semibold">{{ $consultation->patient?->prenom }} {{ $consultation->patient?->nom }}</p>
                    <p class="text-slate-400 text-sm">{{ $consultation->patient?->telephone }}</p>
                </div>
                <div>
                    <p class="text-slate-400 text-xs mb-1">Médecin</p>
                    <p class="text-white font-semibold">Dr. {{ $consultation->medecin?->prenom }} {{ $consultation->medecin?->nom }}</p>
                </div>
                <div>
                    <p class="text-slate-400 text-xs mb-1">Date de consultation</p>
                    <p class="text-white">{{ \Carbon\Carbon::parse($consultation->date_consultation)->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-slate-400 text-xs mb-1">Facture</p>
                    @if($consultation->facture)
                        <a href="{{ route('factures.show', $consultation->facture) }}"
                           class="text-cyan-400 hover:text-cyan-300 font-mono font-medium transition-colors">
                            {{ $consultation->facture->numero_facture }}
                        </a>
                        <x-statut-badge :statut="$consultation->facture->statut" class="ml-2"/>
                    @else
                        <span class="text-slate-500">Pas de facture</span>
                    @endif
                </div>
            </div>

            <div class="flex gap-3">
                @can('update', $consultation)
                    <a href="{{ route('consultations.edit', $consultation) }}"
                       class="px-4 py-2.5 bg-slate-800 border border-slate-700 text-slate-300 text-sm rounded-xl hover:bg-slate-700 transition-colors">
                        Modifier
                    </a>
                @endcan
                @if($consultation->facture)
                    <a href="{{ route('factures.show', $consultation->facture) }}"
                       class="px-4 py-2.5 bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 text-sm rounded-xl hover:bg-cyan-500/20 transition-colors">
                        Gérer la facture
                    </a>
                @endif
                <a href="{{ route('consultations.index') }}"
                   class="px-4 py-2.5 bg-slate-800 border border-slate-700 text-slate-300 text-sm rounded-xl hover:bg-slate-700 transition-colors">
                    Retour
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
