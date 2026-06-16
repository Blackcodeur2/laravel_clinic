<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0A1628">
    <title>{{ $title ?? config('app.name', 'MyClinic') }} — MyClinic</title>
     @laravelPWA
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-gray-100 font-sans antialiased" x-data="{ sidebarOpen: false }"
      @if(session('success')) data-flash-success="{{ session('success') }}" @endif
      @if(session('error') || $errors->has('error')) data-flash-error="{{ session('error') ?? $errors->first('error') }}" @endif>
    <x-splash-screen />

    {{-- ==================== SIDEBAR ==================== --}}
    <div class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 border-r border-slate-800 flex flex-col"
         :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
         x-transition>

        {{-- Logo --}}
        <div class="flex items-center gap-3 px-6 py-5 border-b border-slate-800">
            @if($clinicSettings->logo_path)
                <img src="{{ asset('storage/' . $clinicSettings->logo_path) }}" alt="{{ $clinicSettings->nom_clinique }}" class="w-9 h-9 rounded-xl object-cover shadow-lg shadow-blue-500/30" />
            @else
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-cyan-400 to-blue-600 flex items-center justify-center shadow-lg shadow-blue-500/30">
                    <svg class="w-5 h-5 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                              d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </div>
            @endif
            <div>
                <span class="text-white font-bold text-lg leading-none">{{ $clinicSettings->nom_clinique }}</span>
                <p class="text-slate-400 text-xs mt-0.5">{{ $clinicSettings->slogan ?: 'Système de facturation' }}</p>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">

            @if(!auth()->user()->isCaissier())
                <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')"
                            icon="chart-bar">
                    Tableau de bord
                </x-nav-link>
            @endif

            <div class="pt-3 pb-1 px-3">
                <p class="text-slate-400 text-xs font-semibold uppercase tracking-widest">Facturation</p>
            </div>

            <x-nav-link href="{{ route('consultations.index') }}" :active="request()->routeIs('consultations.*')"
                        icon="clipboard-document-list">
                Consultations
            </x-nav-link>

            <x-nav-link href="{{ route('factures.index') }}" :active="request()->routeIs('factures.*')"
                        icon="document-text">
                Factures
            </x-nav-link>

            <div class="pt-3 pb-1 px-3">
                <p class="text-slate-400 text-xs font-semibold uppercase tracking-widest">Catalogue</p>
            </div>

            <x-nav-link href="{{ route('patients.index') }}" :active="request()->routeIs('patients.*')"
                        icon="user-group">
                Patients
            </x-nav-link>

            <x-nav-link href="{{ route('services.index') }}" :active="request()->routeIs('services.*')"
                        icon="beaker">
                Services médicaux
            </x-nav-link>

            <x-nav-link href="{{ route('medicaments.index') }}" :active="request()->routeIs('medicaments.*')"
                        icon="archive-box">
                Médicaments
            </x-nav-link>

            @if(auth()->user()->isAdmin() || auth()->user()->isResponsable())
                <div class="pt-3 pb-1 px-3">
                    <p class="text-slate-400 text-xs font-semibold uppercase tracking-widest">Administration</p>
                </div>
                <x-nav-link href="{{ route('users.index') }}" :active="request()->routeIs('users.*')"
                            icon="users">
                    Utilisateurs
                </x-nav-link>
                <x-nav-link href="{{ route('reports.monthly') }}" :active="request()->routeIs('reports.*')"
                            icon="chart-bar">
                    Rapports d'activité
                </x-nav-link>
                <x-nav-link href="{{ route('settings.edit') }}" :active="request()->routeIs('settings.*')"
                            icon="cog-6-tooth">
                    Paramètres clinique
                </x-nav-link>
            @endif
        </nav>

        {{-- User area --}}
        <div class="border-t border-slate-800 p-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 flex-1 min-w-0 group hover:opacity-80 transition-opacity" title="Mon Profil">
                    @if(auth()->user()->photo_profile)
                        <img src="{{ asset('storage/' . auth()->user()->photo_profile) }}" class="w-9 h-9 rounded-full object-cover flex-shrink-0 group-hover:ring-2 group-hover:ring-violet-400 transition-all" />
                    @else
                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-violet-500 to-purple-700 flex items-center justify-center text-white text-sm font-bold flex-shrink-0 group-hover:ring-2 group-hover:ring-violet-400 transition-all">
                            {{ strtoupper(substr(auth()->user()->prenom, 0, 1)) }}{{ strtoupper(substr(auth()->user()->nom, 0, 1)) }}
                        </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <p class="text-white text-sm font-medium truncate group-hover:text-violet-300 transition-colors">{{ auth()->user()->prenom }} {{ auth()->user()->nom }}</p>
                        <p class="text-slate-400 text-xs truncate">{{ auth()->user()->role?->nom }}</p>
                    </div>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="text-slate-400 hover:text-red-400 transition-colors"
                            title="Se déconnecter">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Overlay for mobile --}}
    <div class="fixed inset-0 z-40 bg-black/60 lg:hidden"
         x-show="sidebarOpen"
         x-transition:enter="transition-opacity ease-linear duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="sidebarOpen = false"
         style="display:none;">
    </div>

    {{-- ==================== MAIN CONTENT ==================== --}}
    <div class="lg:pl-64 flex flex-col min-h-screen">

        {{-- Top bar --}}
        <header class="sticky top-0 z-30 bg-gray-100/80 backdrop-blur border-b border-gray-200 px-4 sm:px-6 lg:px-8 h-16 flex items-center gap-4">
            {{-- Mobile menu toggle --}}
            <button @click="sidebarOpen = !sidebarOpen"
                    class="lg:hidden text-gray-500 hover:text-gray-900 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <div class="flex-1">
                @isset($header)
                    <h1 class="text-gray-900 font-semibold text-lg">{{ $header }}</h1>
                @endisset
            </div>

            <div class="flex items-center gap-2">
                <span class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-blue-600/10 text-blue-600 border border-cyan-500/20">
                    <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 animate-pulse"></span>
                    En ligne
                </span>
            </div>
        </header>

        {{-- Page content --}}
        <main class="flex-1 px-4 sm:px-6 lg:px-8 py-8">

            {{ $slot }}
        </main>
    </div>
</body>
</html>
