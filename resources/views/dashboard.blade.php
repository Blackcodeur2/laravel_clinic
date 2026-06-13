<x-app-layout>
    <x-slot name="header">Tableau de bord</x-slot>
    <x-slot name="title">Tableau de bord</x-slot>

    @php
        $totalAlerts = $alertExpired->count() + $alertNearExpiration->count() + $alertLowStock->count() + $alertOutOfStock->count();
        $isCritical = $alertExpired->count() > 0 || $alertOutOfStock->count() > 0;
        $panelBorderClass   = $isCritical ? 'border-red-300 bg-red-50'      : 'border-amber-300 bg-amber-50';
        $panelHeaderClass   = $isCritical ? 'bg-red-100/70 hover:bg-red-100' : 'bg-amber-100/70 hover:bg-amber-100';
        $panelTitleColor    = $isCritical ? 'text-red-800'   : 'text-amber-800';
        $panelBadgeBg       = $isCritical ? 'bg-red-500'     : 'bg-amber-500';
        $panelSubtextColor  = $isCritical ? 'text-red-600'   : 'text-amber-600';
        $panelIcon          = $isCritical ? '🚨' : '⚠️';
        $panelChevronColor  = $isCritical ? 'text-red-600'   : 'text-amber-600';
    @endphp

    {{-- ===== Medication Alerts Panel ===== --}}
    @if($totalAlerts > 0)
    <div x-data="{ open: true }" class="mb-6">
        <div class="rounded-2xl border overflow-hidden shadow-sm {{ $panelBorderClass }}">

            {{-- Header / Toggle --}}
            <button @click="open = !open" type="button"
                    class="w-full flex items-center justify-between px-5 py-4 text-left focus:outline-none transition-colors {{ $panelHeaderClass }}">
                <div class="flex items-center gap-3">
                    <span class="text-xl">{{ $panelIcon }}</span>
                    <div>
                        <p class="font-bold text-sm {{ $panelTitleColor }}">
                            Alertes médicaments
                            <span class="ml-2 inline-flex items-center justify-center w-5 h-5 rounded-full text-xs font-bold text-white {{ $panelBadgeBg }}">{{ $totalAlerts }}</span>
                        </p>
                        <p class="text-xs {{ $panelSubtextColor }}" x-text="open ? 'Cliquez pour masquer les détails' : 'Cliquez pour afficher les détails'"></p>
                    </div>
                </div>
                <svg class="w-5 h-5 transition-transform {{ $panelChevronColor }}"
                     :class="open ? 'rotate-180' : ''"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>


            {{-- Body --}}
            <div x-show="open" x-transition class="px-5 pb-5 pt-2 space-y-4">

                {{-- Expired --}}
                @if($alertExpired->count() > 0)
                <div>
                    <p class="text-xs font-bold text-red-700 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                        <span>🔴</span> Médicaments périmés ({{ $alertExpired->count() }}) — dispensation bloquée
                    </p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($alertExpired as $med)
                            <a href="{{ route('medicaments.edit', $med) }}"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-red-500 text-white text-xs font-semibold hover:bg-red-600 transition-colors shadow-sm">
                                <i class="bi bi-capsule"></i>
                                {{ $med->nom }}
                                <span class="opacity-80">({{ $med->date_peremption->format('d/m/Y') }})</span>
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Out of Stock --}}
                @if($alertOutOfStock->count() > 0)
                <div>
                    <p class="text-xs font-bold text-red-700 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                        <span>🔴</span> Rupture de stock ({{ $alertOutOfStock->count() }})
                    </p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($alertOutOfStock as $med)
                            <a href="{{ route('medicaments.edit', $med) }}"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-red-400 text-white text-xs font-semibold hover:bg-red-500 transition-colors shadow-sm">
                                <i class="bi bi-box-seam"></i>
                                {{ $med->nom }}
                                <span class="opacity-80">(stock: 0)</span>
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Near Expiration --}}
                @if($alertNearExpiration->count() > 0)
                <div>
                    <p class="text-xs font-bold text-amber-700 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                        <span>🟠</span> Proche de la péremption — moins de 30 jours ({{ $alertNearExpiration->count() }})
                    </p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($alertNearExpiration as $med)
                            <a href="{{ route('medicaments.edit', $med) }}"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-amber-500 text-white text-xs font-semibold hover:bg-amber-600 transition-colors shadow-sm">
                                <i class="bi bi-calendar-x"></i>
                                {{ $med->nom }}
                                <span class="opacity-80">({{ $med->date_peremption->format('d/m/Y') }})</span>
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Low Stock --}}
                @if($alertLowStock->count() > 0)
                <div>
                    <p class="text-xs font-bold text-amber-700 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                        <span>🟡</span> Stock critique (≤ seuil d'alerte) ({{ $alertLowStock->count() }})
                    </p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($alertLowStock as $med)
                            <a href="{{ route('medicaments.edit', $med) }}"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-yellow-400 text-yellow-900 text-xs font-semibold hover:bg-yellow-500 transition-colors shadow-sm">
                                <i class="bi bi-exclamation-triangle"></i>
                                {{ $med->nom }}
                                <span class="opacity-70">(stock: {{ $med->stock }} / seuil: {{ $med->stock_alerte }})</span>
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="pt-1 border-t border-gray-200/50">
                    <a href="{{ route('medicaments.index') }}"
                       class="text-xs font-medium text-blue-600 hover:text-blue-800 transition-colors flex items-center gap-1">
                        <i class="bi bi-arrow-right-circle"></i>
                        Gérer le catalogue des médicaments
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- KPI Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">

        {{-- Total Patients --}}
        <div class="relative overflow-hidden rounded-2xl bg-gray-50 border border-gray-200 p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Patients</p>
                    <p class="text-gray-900 text-3xl font-bold mt-1">{{ number_format($totalPatients) }}</p>
                </div>
                <div class="w-11 h-11 rounded-xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
            <div class="absolute -bottom-4 -right-4 w-24 h-24 rounded-full bg-blue-500/5"></div>
        </div>

        {{-- Consultations aujourd'hui --}}
        <div class="relative overflow-hidden rounded-2xl bg-gray-50 border border-gray-200 p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Consultations aujourd'hui</p>
                    <p class="text-gray-900 text-3xl font-bold mt-1">{{ number_format($consultationsToday) }}</p>
                </div>
                <div class="w-11 h-11 rounded-xl bg-violet-500/10 border border-violet-500/20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
            <div class="absolute -bottom-4 -right-4 w-24 h-24 rounded-full bg-violet-500/5"></div>
        </div>

        {{-- Factures impayées --}}
        <div class="relative overflow-hidden rounded-2xl bg-gray-50 border border-gray-200 p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Factures impayées</p>
                    <p class="text-gray-900 text-3xl font-bold mt-1">{{ number_format($unpaidInvoicesCount) }}</p>
                </div>
                <div class="w-11 h-11 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
            <div class="absolute -bottom-4 -right-4 w-24 h-24 rounded-full bg-amber-500/5"></div>
        </div>

        {{-- Recettes du mois --}}
        <div class="relative overflow-hidden rounded-2xl bg-gray-50 border border-gray-200 p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Recettes du mois</p>
                    <p class="text-gray-900 text-3xl font-bold mt-1">{{ number_format($monthlyRevenue, 0, ',', ' ') }} <span class="text-lg text-gray-500">FCFA</span></p>
                </div>
                <div class="w-11 h-11 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="absolute -bottom-4 -right-4 w-24 h-24 rounded-full bg-emerald-500/5"></div>
        </div>
    </div>


    {{-- Charts Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        {{-- Evolution des recettes --}}
        <div class="col-span-1 lg:col-span-2 rounded-2xl bg-white border border-gray-200 shadow-sm p-6">
            <h3 class="text-gray-900 font-bold text-base mb-4 flex items-center gap-2">
                <i class="bi bi-graph-up text-blue-500 text-lg"></i>
                Évolution des encaissements & restes impayés (6 derniers mois)
            </h3>
            <div id="revenue-chart" style="min-height: 300px;"></div>
        </div>

        {{-- Modes de paiement --}}
        <div class="col-span-1 rounded-2xl bg-white border border-gray-200 shadow-sm p-6 flex flex-col justify-between">
            <h3 class="text-gray-900 font-bold text-base mb-4 flex items-center gap-2">
                <i class="bi bi-wallet2 text-emerald-500 text-lg"></i>
                Modes de paiement
            </h3>
            <div id="payment-chart" class="flex-1 flex items-center justify-center" style="min-height: 280px;"></div>
        </div>
    </div>

    {{-- Doctor Activity Chart --}}
    <div class="grid grid-cols-1 gap-6 mb-8">
        <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-6">
            <h3 class="text-gray-900 font-bold text-base mb-4 flex items-center gap-2">
                <i class="bi bi-people text-violet-500 text-lg"></i>
                Activité des médecins (Consultations ce mois-ci)
            </h3>
            <div id="doctor-chart" style="min-height: 250px;"></div>
        </div>
    </div>

    {{-- Tables --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

        {{-- Dernières factures --}}
        <div class="rounded-2xl bg-gray-50 border border-gray-200 overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                <h2 class="text-gray-900 font-semibold">Dernières factures</h2>
                <a href="{{ route('factures.index') }}"
                   class="text-blue-600 hover:text-cyan-300 text-sm font-medium transition-colors">
                    Voir tout →
                </a>
            </div>
            <div class="divide-y divide-slate-800">
                @forelse($latestInvoices as $facture)
                    <div class="flex items-center gap-4 px-6 py-3.5 hover:bg-white transition-colors">
                        <div class="flex-1 min-w-0">
                            <p class="text-gray-900 text-sm font-medium truncate">
                                {{ $facture->consultation?->patient?->prenom }} {{ $facture->consultation?->patient?->nom }}
                            </p>
                            <p class="text-gray-400 text-xs">{{ $facture->numero_facture }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-gray-900 text-sm font-semibold">{{ number_format($facture->montant_total, 0, ',', ' ') }} F</p>
                            <x-statut-badge :statut="$facture->statut"/>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-400 text-sm text-center py-8">Aucune facture</p>
                @endforelse
            </div>
        </div>

        {{-- Derniers patients --}}
        <div class="rounded-2xl bg-gray-50 border border-gray-200 overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                <h2 class="text-gray-900 font-semibold">Nouveaux patients</h2>
                <a href="{{ route('patients.index') }}"
                   class="text-blue-600 hover:text-cyan-300 text-sm font-medium transition-colors">
                    Voir tout →
                </a>
            </div>
            <div class="divide-y divide-slate-800">
                @forelse($latestPatients as $patient)
                    <div class="flex items-center gap-4 px-6 py-3.5 hover:bg-white transition-colors">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center text-gray-900 text-xs font-bold flex-shrink-0">
                            {{ strtoupper(substr($patient->prenom, 0, 1)) }}{{ strtoupper(substr($patient->nom, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-gray-900 text-sm font-medium truncate">{{ $patient->prenom }} {{ $patient->nom }}</p>
                            <p class="text-gray-400 text-xs">{{ $patient->telephone }}</p>
                        </div>
                        <span class="text-gray-400 text-xs">{{ $patient->created_at->diffForHumans() }}</span>
                    </div>
                @empty
                    <p class="text-gray-400 text-sm text-center py-8">Aucun patient</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ApexCharts Script CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Theme colors
            const colors = {
                primary: '#2563eb', // Blue-600
                danger: '#f43f5e',  // Rose-500
                success: '#059669', // Emerald-600
                violet: '#8b5cf6'   // Violet-600
            };

            // 1. Revenue & Unpaid Evolution (Area Chart)
            const revenueOptions = {
                chart: {
                    type: 'area',
                    height: 300,
                    toolbar: { show: false },
                    zoom: { enabled: false },
                    fontFamily: 'Inter, sans-serif'
                },
                series: [{
                    name: 'Montant encaissé',
                    data: @json($revenueTotals)
                }, {
                    name: 'Reste impayé',
                    data: @json($unpaidTotals)
                }],
                xaxis: {
                    categories: @json($revenueMonths),
                    labels: {
                        style: { colors: '#6b7280', fontSize: '11px' }
                    }
                },
                yaxis: {
                    labels: {
                        formatter: function (val) {
                            return new Intl.NumberFormat('fr-FR').format(val) + ' F';
                        },
                        style: { colors: '#6b7280', fontSize: '11px' }
                    }
                },
                colors: [colors.primary, colors.danger],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.35,
                        opacityTo: 0.05,
                        stops: [0, 100]
                    }
                },
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                dataLabels: { enabled: false },
                grid: {
                    borderColor: '#e5e7eb',
                    strokeDashArray: 4
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return new Intl.NumberFormat('fr-FR').format(val) + ' FCFA';
                        }
                    }
                }
            };
            new ApexCharts(document.querySelector("#revenue-chart"), revenueOptions).render();

            // 2. Payments Breakdown (Donut Chart)
            const paymentOptions = {
                chart: {
                    type: 'donut',
                    height: 280,
                    fontFamily: 'Inter, sans-serif'
                },
                series: @json($paymentTotals),
                labels: @json($paymentLabels),
                colors: ['#059669', '#2563eb', '#8b5cf6', '#eab308', '#ef4444'],
                legend: {
                    position: 'bottom',
                    fontSize: '12px',
                    labels: { colors: '#374151' }
                },
                dataLabels: { enabled: false },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return new Intl.NumberFormat('fr-FR').format(val) + ' FCFA';
                        }
                    }
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '70%',
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: 'Total',
                                    formatter: function (w) {
                                        const total = w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                        return new Intl.NumberFormat('fr-FR').format(total) + ' F';
                                    },
                                    style: { fontSize: '14px', fontWeight: 'bold', color: '#111827' }
                                }
                            }
                        }
                    }
                }
            };
            new ApexCharts(document.querySelector("#payment-chart"), paymentOptions).render();

            // 3. Doctors Activity (Horizontal Bar Chart)
            const doctorOptions = {
                chart: {
                    type: 'bar',
                    height: 250,
                    toolbar: { show: false },
                    fontFamily: 'Inter, sans-serif'
                },
                series: [{
                    name: 'Consultations',
                    data: @json($doctorCounts)
                }],
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                        horizontal: true,
                        barHeight: '50%',
                        distributed: true
                    }
                },
                colors: ['#8b5cf6', '#3b82f6', '#10b981', '#f59e0b', '#ef4444'],
                legend: { show: false },
                dataLabels: {
                    enabled: true,
                    style: { fontSize: '12px', colors: ['#fff'] }
                },
                xaxis: {
                    categories: @json($doctorLabels),
                    labels: {
                        show: true,
                        style: { colors: '#6b7280', fontSize: '11px' }
                    }
                },
                yaxis: {
                    labels: {
                        style: { colors: '#6b7280', fontSize: '12px' }
                    }
                },
                grid: {
                    borderColor: '#e5e7eb',
                    strokeDashArray: 4
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return val + " consultation(s)";
                        }
                    }
                }
            };
            new ApexCharts(document.querySelector("#doctor-chart"), doctorOptions).render();
        });
    </script>
</x-app-layout>
