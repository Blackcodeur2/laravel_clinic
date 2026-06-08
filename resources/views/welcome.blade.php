<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Logiciel de facturation et gestion pour petites cliniques de santé.">

        <title>{{ config('app.name', 'MyClinic') }} — Facturation clinique</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-800 antialiased bg-slate-50">

        {{-- Navigation --}}
        <header class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-slate-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <a href="/" class="flex items-center gap-2.5 group">
                        <x-application-logo class="h-9 w-auto object-contain transition-transform duration-300 group-hover:scale-105" />
                    </a>


                    <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-slate-600">
                        <a href="#fonctionnalites" class="hover:text-clinic-600 transition-colors">Fonctionnalités</a>
                        <a href="#avantages" class="hover:text-clinic-600 transition-colors">Avantages</a>
                        <a href="#contact" class="hover:text-clinic-600 transition-colors">Contact</a>
                    </nav>

                    <div class="flex items-center gap-3">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-clinic-600 rounded-lg hover:bg-clinic-700 transition-colors shadow-sm">
                                    <i class="bi bi-grid-1x2-fill"></i>
                                    Tableau de bord
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="hidden sm:inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-700 hover:text-clinic-600 transition-colors">
                                    <i class="bi bi-box-arrow-in-right"></i>
                                    Connexion
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-clinic-600 rounded-lg hover:bg-clinic-700 transition-colors shadow-sm">
                                        <i class="bi bi-person-plus-fill"></i>
                                        S'inscrire
                                    </a>
                                @endif
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </header>

        {{-- Hero --}}
        <section class="relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-clinic-50 via-white to-teal-50"></div>
            <div class="absolute top-0 right-0 w-1/2 h-full bg-gradient-to-l from-clinic-100/40 to-transparent"></div>
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-clinic-200/30 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-72 h-72 bg-teal-200/20 rounded-full blur-3xl"></div>

            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-32">
                <div class="text-center max-w-3xl mx-auto">
                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-clinic-100 text-clinic-700 text-sm font-medium mb-6">
                        <i class="bi bi-shield-check"></i>
                        Solution dédiée aux cliniques de santé
                    </span>
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-slate-900 leading-tight tracking-tight">
                        La facturation de votre clinique,
                        <span class="text-clinic-600">simplifiée</span>
                    </h1>
                    <p class="mt-6 text-lg sm:text-xl text-slate-600 leading-relaxed max-w-2xl mx-auto">
                        Gérez vos patients, émettez des factures, suivez les paiements et pilotez votre activité depuis une seule interface intuitive.
                    </p>
                    <div class="mt-10 flex flex-wrap justify-center gap-4">
                        @if (Route::has('register'))
                            <a href="{{ Route::has('register') ? route('register') : '#' }}" class="inline-flex items-center gap-2 px-6 py-3 text-base font-semibold text-white bg-clinic-600 rounded-xl hover:bg-clinic-700 transition-all shadow-lg shadow-clinic-600/25 hover:shadow-clinic-600/40">
                                <i class="bi bi-rocket-takeoff-fill"></i>
                                Commencer gratuitement
                            </a>
                        @endif
                        <a href="#fonctionnalites" class="inline-flex items-center gap-2 px-6 py-3 text-base font-semibold text-clinic-700 bg-white border border-clinic-200 rounded-xl hover:bg-clinic-50 transition-colors">
                            <i class="bi bi-play-circle"></i>
                            Découvrir
                        </a>
                    </div>
                    <div class="mt-12 flex flex-wrap justify-center gap-6 text-sm text-slate-500">
                        <span class="inline-flex items-center gap-2">
                            <i class="bi bi-check-circle-fill text-clinic-500"></i>
                            Sans engagement
                        </span>
                        <span class="inline-flex items-center gap-2">
                            <i class="bi bi-check-circle-fill text-clinic-500"></i>
                            Données sécurisées
                        </span>
                        <span class="inline-flex items-center gap-2">
                            <i class="bi bi-check-circle-fill text-clinic-500"></i>
                            Support réactif
                        </span>
                    </div>
                </div>
            </div>

        </section>

        {{-- Stats --}}
        <section class="bg-clinic-700 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 text-center">
                    <div>
                        <p class="text-3xl font-bold">500+</p>
                        <p class="mt-1 text-clinic-200 text-sm">Cliniques accompagnées</p>
                    </div>
                    <div>
                        <p class="text-3xl font-bold">50k+</p>
                        <p class="mt-1 text-clinic-200 text-sm">Factures émises</p>
                    </div>
                    <div>
                        <p class="text-3xl font-bold">99,9%</p>
                        <p class="mt-1 text-clinic-200 text-sm">Disponibilité</p>
                    </div>
                    <div>
                        <p class="text-3xl font-bold">24/7</p>
                        <p class="mt-1 text-clinic-200 text-sm">Accès sécurisé</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- Features --}}
        <section id="fonctionnalites" class="py-20 lg:py-28">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto">
                    <span class="inline-flex items-center gap-2 text-clinic-600 font-semibold text-sm uppercase tracking-wider">
                        <i class="bi bi-stars"></i>
                        Fonctionnalités
                    </span>
                    <h2 class="mt-3 text-3xl sm:text-4xl font-bold text-slate-900">
                        Tout ce dont votre clinique a besoin
                    </h2>
                    <p class="mt-4 text-lg text-slate-600">
                        Un outil complet pensé pour les professionnels de santé et leurs équipes administratives.
                    </p>
                </div>

                <div class="mt-16 grid sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                    <div class="group p-6 rounded-2xl bg-white border border-slate-200 hover:border-clinic-200 hover:shadow-lg hover:shadow-clinic-100/50 transition-all duration-300">
                        <span class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-clinic-100 text-clinic-600 group-hover:scale-110 transition-transform">
                            <i class="bi bi-people-fill text-xl"></i>
                        </span>
                        <h3 class="mt-4 text-lg font-semibold text-slate-900">Gestion des patients</h3>
                        <p class="mt-2 text-slate-600 text-sm leading-relaxed">Dossiers patients centralisés, historique des consultations et suivi médical simplifié.</p>
                    </div>
                    <div class="group p-6 rounded-2xl bg-white border border-slate-200 hover:border-blue-200 hover:shadow-lg hover:shadow-blue-100/50 transition-all duration-300">
                        <span class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-blue-100 text-blue-600 group-hover:scale-110 transition-transform">
                            <i class="bi bi-receipt text-xl"></i>
                        </span>
                        <h3 class="mt-4 text-lg font-semibold text-slate-900">Facturation</h3>
                        <p class="mt-2 text-slate-600 text-sm leading-relaxed">Créez et envoyez des factures en quelques clics. Modèles personnalisables à votre image.</p>
                    </div>
                    <div class="group p-6 rounded-2xl bg-white border border-slate-200 hover:border-emerald-200 hover:shadow-lg hover:shadow-emerald-100/50 transition-all duration-300">
                        <span class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 group-hover:scale-110 transition-transform">
                            <i class="bi bi-credit-card-2-front-fill text-xl"></i>
                        </span>
                        <h3 class="mt-4 text-lg font-semibold text-slate-900">Paiements & encaissements</h3>
                        <p class="mt-2 text-slate-600 text-sm leading-relaxed">Suivez les règlements, relances automatiques et état des créances en temps réel.</p>
                    </div>
                    <div class="group p-6 rounded-2xl bg-white border border-slate-200 hover:border-violet-200 hover:shadow-lg hover:shadow-violet-100/50 transition-all duration-300">
                        <span class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-violet-100 text-violet-600 group-hover:scale-110 transition-transform">
                            <i class="bi bi-calendar-check-fill text-xl"></i>
                        </span>
                        <h3 class="mt-4 text-lg font-semibold text-slate-900">Rendez-vous</h3>
                        <p class="mt-2 text-slate-600 text-sm leading-relaxed">Planifiez les consultations et évitez les conflits d'horaires au sein de votre équipe.</p>
                    </div>
                    <div class="group p-6 rounded-2xl bg-white border border-slate-200 hover:border-amber-200 hover:shadow-lg hover:shadow-amber-100/50 transition-all duration-300">
                        <span class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-amber-100 text-amber-600 group-hover:scale-110 transition-transform">
                            <i class="bi bi-bar-chart-line-fill text-xl"></i>
                        </span>
                        <h3 class="mt-4 text-lg font-semibold text-slate-900">Rapports & statistiques</h3>
                        <p class="mt-2 text-slate-600 text-sm leading-relaxed">Tableaux de bord clairs pour analyser votre activité et prendre de meilleures décisions.</p>
                    </div>
                    <div class="group p-6 rounded-2xl bg-white border border-slate-200 hover:border-rose-200 hover:shadow-lg hover:shadow-rose-100/50 transition-all duration-300">
                        <span class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-rose-100 text-rose-600 group-hover:scale-110 transition-transform">
                            <i class="bi bi-shield-lock-fill text-xl"></i>
                        </span>
                        <h3 class="mt-4 text-lg font-semibold text-slate-900">Sécurité & conformité</h3>
                        <p class="mt-2 text-slate-600 text-sm leading-relaxed">Données chiffrées, accès par rôles et sauvegardes pour protéger vos informations.</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- Benefits --}}
        <section id="avantages" class="py-20 bg-white border-y border-slate-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-2 gap-12 lg:gap-20 items-center">
                    <div>
                        <span class="inline-flex items-center gap-2 text-clinic-600 font-semibold text-sm uppercase tracking-wider">
                            <i class="bi bi-lightning-charge-fill"></i>
                            Pourquoi nous choisir
                        </span>
                        <h2 class="mt-3 text-3xl sm:text-4xl font-bold text-slate-900">
                            Gagnez du temps au quotidien
                        </h2>
                        <p class="mt-4 text-lg text-slate-600">
                            Concentrez-vous sur vos patients. Nous nous occupons de la partie administrative et financière.
                        </p>
                        <ul class="mt-8 space-y-4">
                            @foreach ([
                                'Interface simple, prise en main en moins d\'une heure',
                                'Factures conformes et exportables en PDF',
                                'Multi-utilisateurs avec droits personnalisés',
                                'Accessible depuis tout appareil connecté',
                            ] as $benefit)
                                <li class="flex items-start gap-3">
                                    <span class="flex-shrink-0 w-6 h-6 rounded-full bg-clinic-100 text-clinic-600 flex items-center justify-center mt-0.5">
                                        <i class="bi bi-check-lg text-sm font-bold"></i>
                                    </span>
                                    <span class="text-slate-700">{{ $benefit }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-6 rounded-2xl bg-clinic-50 border border-clinic-100">
                            <i class="bi bi-clock-history text-3xl text-clinic-600"></i>
                            <p class="mt-4 text-2xl font-bold text-slate-900">-60%</p>
                            <p class="text-sm text-slate-600 mt-1">de temps administratif</p>
                        </div>
                        <div class="p-6 rounded-2xl bg-emerald-50 border border-emerald-100 mt-8">
                            <i class="bi bi-currency-exchange text-3xl text-emerald-600"></i>
                            <p class="mt-4 text-2xl font-bold text-slate-900">+35%</p>
                            <p class="text-sm text-slate-600 mt-1">d'encaissements à temps</p>
                        </div>
                        <div class="p-6 rounded-2xl bg-blue-50 border border-blue-100">
                            <i class="bi bi-file-earmark-medical text-3xl text-blue-600"></i>
                            <p class="mt-4 text-2xl font-bold text-slate-900">100%</p>
                            <p class="text-sm text-slate-600 mt-1">des dossiers centralisés</p>
                        </div>
                        <div class="p-6 rounded-2xl bg-violet-50 border border-violet-100 mt-8">
                            <i class="bi bi-emoji-smile text-3xl text-violet-600"></i>
                            <p class="mt-4 text-2xl font-bold text-slate-900">4,8/5</p>
                            <p class="text-sm text-slate-600 mt-1">satisfaction utilisateurs</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- CTA --}}
        <section id="contact" class="py-20 lg:py-28">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-clinic-600 to-clinic-800 px-8 py-16 sm:px-16 sm:py-20 text-center">
                    <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23ffffff\" fill-opacity=\"0.05\"%3E%3Cpath d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-50"></div>
                    <div class="relative">
                        <i class="bi bi-heart-pulse-fill text-5xl text-clinic-200 mb-6 inline-block"></i>
                        <h2 class="text-3xl sm:text-4xl font-bold text-white">
                            Prêt à moderniser votre clinique ?
                        </h2>
                        <p class="mt-4 text-lg text-clinic-100 max-w-xl mx-auto">
                            Rejoignez les professionnels de santé qui font confiance à {{ config('app.name') }} pour leur facturation.
                        </p>
                        <div class="mt-8 flex flex-wrap justify-center gap-4">
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-8 py-3.5 text-base font-semibold text-clinic-700 bg-white rounded-xl hover:bg-clinic-50 transition-colors shadow-lg">
                                    <i class="bi bi-person-plus-fill"></i>
                                    Créer un compte
                                </a>
                            @endif
                            @if (Route::has('login'))
                                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-8 py-3.5 text-base font-semibold text-white border-2 border-white/30 rounded-xl hover:bg-white/10 transition-colors">
                                    <i class="bi bi-box-arrow-in-right"></i>
                                    Se connecter
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Footer --}}
        <footer class="bg-slate-900 text-slate-400 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="flex items-center gap-2.5">
                        <x-application-logo class="h-8 w-auto object-contain" />
                    </div>

                    <p class="text-sm text-center">
                        &copy; {{ date('Y') }} {{ config('app.name') }}. Logiciel de facturation pour cliniques de santé.
                    </p>
                    <div class="flex items-center gap-4 text-lg">
                        <a href="#" class="hover:text-clinic-400 transition-colors" aria-label="Email">
                            <i class="bi bi-envelope-fill"></i>
                        </a>
                        <a href="#" class="hover:text-clinic-400 transition-colors" aria-label="Téléphone">
                            <i class="bi bi-telephone-fill"></i>
                        </a>
                        <a href="#" class="hover:text-clinic-400 transition-colors" aria-label="Localisation">
                            <i class="bi bi-geo-alt-fill"></i>
                        </a>
                    </div>
                </div>
            </div>
        </footer>

    </body>
</html>
