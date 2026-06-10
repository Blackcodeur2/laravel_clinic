@if(isset($clinicSettings) && $clinicSettings->logo_path)
    <img src="{{ asset('storage/' . $clinicSettings->logo_path) }}" {{ $attributes->merge(['alt' => $clinicSettings->nom_clinique, 'class' => 'h-20 w-auto object-contain']) }}>
@else
    <div class="flex items-center gap-3">
        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-cyan-400 to-blue-600 flex items-center justify-center shadow-lg shadow-blue-500/30">
            <svg class="w-7 h-7 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                      d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
        </div>
        <div class="text-left">
            <span class="text-gray-900 font-bold text-2xl tracking-tight block leading-none">{{ $clinicSettings->nom_clinique ?? 'MyClinic' }}</span>
            <span class="text-gray-500 text-xs mt-1 block">{{ ($clinicSettings->slogan ?? '') ?: 'Système de facturation' }}</span>
        </div>
    </div>
@endif


