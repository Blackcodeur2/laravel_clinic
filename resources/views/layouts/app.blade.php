<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name', 'MyClinic') }} — MyClinic</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-slate-950 font-sans antialiased" x-data="{ sidebarOpen: false }">

    {{-- ==================== SIDEBAR ==================== --}}
    <div class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 border-r border-slate-800 flex flex-col"
         :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
         x-transition>

        {{-- Logo --}}
        <div class="flex items-center gap-3 px-6 py-5 border-b border-slate-800">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-cyan-400 to-blue-600 flex items-center justify-center shadow-lg shadow-blue-500/30">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                          d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
            </div>
            <div>
                <span class="text-white font-bold text-lg leading-none">MyClinic</span>
                <p class="text-slate-400 text-xs mt-0.5">Système de facturation</p>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">

            <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')"
                        icon="chart-bar">
                Tableau de bord
            </x-nav-link>

            <div class="pt-3 pb-1 px-3">
                <p class="text-slate-500 text-xs font-semibold uppercase tracking-widest">Facturation</p>
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
                <p class="text-slate-500 text-xs font-semibold uppercase tracking-widest">Catalogue</p>
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

            @if(auth()->user()->isAdmin())
                <div class="pt-3 pb-1 px-3">
                    <p class="text-slate-500 text-xs font-semibold uppercase tracking-widest">Administration</p>
                </div>
                <x-nav-link href="{{ route('users.index') }}" :active="request()->routeIs('users.*')"
                            icon="users">
                    Utilisateurs
                </x-nav-link>
            @endif
        </nav>

        {{-- User area --}}
        <div class="border-t border-slate-800 p-4">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-violet-500 to-purple-700 flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                    {{ strtoupper(substr(auth()->user()->prenom, 0, 1)) }}{{ strtoupper(substr(auth()->user()->nom, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-white text-sm font-medium truncate">{{ auth()->user()->prenom }} {{ auth()->user()->nom }}</p>
                    <p class="text-slate-400 text-xs truncate">{{ auth()->user()->role?->nom }}</p>
                </div>
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
        <header class="sticky top-0 z-30 bg-slate-950/80 backdrop-blur border-b border-slate-800 px-4 sm:px-6 lg:px-8 h-16 flex items-center gap-4">
            {{-- Mobile menu toggle --}}
            <button @click="sidebarOpen = !sidebarOpen"
                    class="lg:hidden text-slate-400 hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <div class="flex-1">
                @isset($header)
                    <h1 class="text-white font-semibold text-lg">{{ $header }}</h1>
                @endisset
            </div>

            <div class="flex items-center gap-2">
                <span class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-cyan-500/10 text-cyan-400 border border-cyan-500/20">
                    <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 animate-pulse"></span>
                    En ligne
                </span>
            </div>
        </header>

        {{-- Page content --}}
        <main class="flex-1 px-4 sm:px-6 lg:px-8 py-8">

            {{-- Flash messages --}}
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                     x-transition:leave="transition ease-in duration-300"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-2"
                     class="mb-6 flex items-center gap-3 px-4 py-3 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error') || $errors->has('error'))
                <div class="mb-6 flex items-center gap-3 px-4 py-3 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 text-sm">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ session('error') ?? $errors->first('error') }}
                </div>
            @endif

            {{ $slot }}
        </main>
    </div>

</body>
</html>
