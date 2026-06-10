@props(['statut'])

@php
$config = match($statut) {
    'PAYEE' => ['text' => 'Payée', 'class' => 'bg-emerald-500/10 text-green-600 border-emerald-500/20'],
    'PARTIELLEMENT_PAYEE' => ['text' => 'Partielle', 'class' => 'bg-amber-500/10 text-yellow-600 border-amber-500/20'],
    default => ['text' => 'Impayée', 'class' => 'bg-red-500/10 text-red-400 border-red-500/20'],
};
@endphp

<span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium border {{ $config['class'] }}">
    {{ $config['text'] }}
</span>
