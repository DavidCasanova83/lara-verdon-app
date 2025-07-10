<div x-data="cityStats()" class="min-h-screen bg-gradient-to-br from-blue-50 to-green-50 p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header avec titre et actions -->
        <div class="mb-8 bg-white rounded-xl shadow-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">📍 Statistiques {{ $city->name }}</h1>
                    <p class="text-gray-600">Analyse détaillée des visiteurs de {{ $city->name }}</p>
                    <div class="mt-2 flex items-center space-x-4 text-sm text-gray-500">
                        <span>🏛️ Bureau d'information touristique</span>
                        <span>•</span>
                        <span>📊 Données en temps réel</span>
                    </div>
                </div>
                <div class="flex gap-3">
                    <button wire:click="exportCsv" 
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium flex items-center gap-2 transition-colors">
                        📄 Exporter CSV
                    </button>
                    <button wire:click="resetFilters"
                            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        🔄 Reset Filtres
                    </button>
                    <a href="{{ route('form.step1', $citySlug) }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        📝 Nouveau formulaire
                    </a>
                </div>
            </div>

            <!-- Filtres -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
                <!-- Filtre dates -->
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Période d'analyse</label>
                    <div class="flex gap-2">
                        <input type="date" wire:model.live="dateFrom" 
                               class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        <input type="date" wire:model.live="dateTo"
                               class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    </div>
                </div>

                <!-- Filtre pays -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pays d'origine</label>
                    <select wire:model.live="selectedCountry"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        <option value="">Tous les pays</option>
                        @foreach($availableCountries as $country)
                            <option value="{{ $country }}">{{ $country }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filtre département -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Département FR</label>
                    <select wire:model.live="selectedDepartment"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        <option value="">Tous les départements</option>
                        @foreach($availableDepartments as $dept)
                            <option value="{{ $dept }}">{{ $dept }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Contrôles graphiques -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Actions</label>
                    <div class="space-y-1">
                        <button @click="refreshCharts()" 
                                class="w-full px-3 py-1 bg-blue-100 text-blue-700 rounded text-xs font-medium hover:bg-blue-200 transition-colors">
                            🔄 Actualiser graphiques
                        </button>
                        <button @click="toggleAll()" 
                                class="w-full px-3 py-1 bg-purple-100 text-purple-700 rounded text-xs font-medium hover:bg-purple-200 transition-colors">
                            👁️ Toggle visibilité
                        </button>
                    </div>
                </div>
            </div>

            <!-- Métriques rapides avec croissance -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-4 text-white">
                    <div class="text-2xl font-bold">{{ number_format($totalCount) }}</div>
                    <div class="text-blue-100 text-sm">Total visiteurs</div>
                    <div class="text-blue-200 text-xs mt-1">Depuis le début</div>
                </div>
                <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-4 text-white">
                    <div class="text-2xl font-bold">{{ $totalToday }}</div>
                    <div class="text-green-100 text-sm">Aujourd'hui</div>
                    <div class="text-green-200 text-xs mt-1">{{ Carbon\Carbon::now()->format('d/m/Y') }}</div>
                </div>
                <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-lg p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold">{{ $totalThisWeek }}</div>
                            <div class="text-yellow-100 text-sm">Cette semaine</div>
                        </div>
                        @if($weeklyGrowth > 0)
                            <div class="text-yellow-100 text-xs">↗️ +{{ $weeklyGrowth }}%</div>
                        @elseif($weeklyGrowth < 0)
                            <div class="text-yellow-100 text-xs">↘️ {{ $weeklyGrowth }}%</div>
                        @else
                            <div class="text-yellow-100 text-xs">➡️ 0%</div>
                        @endif
                    </div>
                </div>
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-4 text-white">
                    <div class="text-2xl font-bold">{{ $totalThisMonth }}</div>
                    <div class="text-purple-100 text-sm">Ce mois</div>
                    <div class="text-purple-200 text-xs mt-1">{{ Carbon\Carbon::now()->format('F Y') }}</div>
                </div>
            </div>
        </div>

        @if($totalCount > 0)
        <!-- Graphiques spécifiques à la ville -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Évolution temporelle -->
            <div x-show="showTrends" class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">📈 Évolution (30 derniers jours)</h3>
                    <button @click="showTrends = !showTrends" class="text-gray-400 hover:text-gray-600">
                        <span x-show="showTrends">👁️</span>
                        <span x-show="!showTrends">👁️‍🗨️</span>
                    </button>
                </div>
                <div class="h-64">
                    <canvas id="trendsChart" width="400" height="256"></canvas>
                </div>
            </div>

            <!-- Pays d'origine -->
            <div x-show="showCountries" class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">🌍 Pays d'origine</h3>
                    <button @click="showCountries = !showCountries" class="text-gray-400 hover:text-gray-600">
                        <span x-show="showCountries">👁️</span>
                        <span x-show="!showCountries">👁️‍🗨️</span>
                    </button>
                </div>
                <div class="h-64">
                    <canvas id="countriesChart" width="400" height="256"></canvas>
                </div>
            </div>

            <!-- Groupes d'âge -->
            <div x-show="showAgeGroups" class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">👥 Profils d'âge</h3>
                    <button @click="showAgeGroups = !showAgeGroups" class="text-gray-400 hover:text-gray-600">
                        <span x-show="showAgeGroups">👁️</span>
                        <span x-show="!showAgeGroups">👁️‍🗨️</span>
                    </button>
                </div>
                <div class="h-64">
                    <canvas id="ageGroupsChart" width="400" height="256"></canvas>
                </div>
            </div>

            <!-- Demandes spécifiques -->
            <div x-show="showRequests" class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">🎯 Demandes populaires</h3>
                    <button @click="showRequests = !showRequests" class="text-gray-400 hover:text-gray-600">
                        <span x-show="showRequests">👁️</span>
                        <span x-show="!showRequests">👁️‍🗨️</span>
                    </button>
                </div>
                <div class="h-64">
                    <canvas id="requestsChart" width="400" height="256"></canvas>
                </div>
            </div>
        </div>

        <!-- Départements français -->
        @if($departmentsData->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">📍 Départements français</h3>
                <div class="h-64">
                    <canvas id="departmentsChart" width="400" height="256"></canvas>
                </div>
            </div>

            <!-- Profils newsletter -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">📧 Abonnements newsletter</h3>
                <div class="h-64">
                    <canvas id="profilesChart" width="400" height="256"></canvas>
                </div>
            </div>
        </div>
        @endif

        <!-- Table détaillée des soumissions -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">📋 Toutes les soumissions ({{ $totalCount }} max 100 affichées)</h3>
                <div class="text-sm text-gray-500">Triées par date décroissante</div>
            </div>
            
            @if($submissions->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Heure</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Origine</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Profil</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Intérêts</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Newsletter</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($submissions as $submission)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $submission->created_at->format('d/m/Y') }}</div>
                                <div class="text-sm text-gray-500">{{ $submission->created_at->format('H:i') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $submission->country }}</div>
                                @if($submission->department)
                                    <div class="text-sm text-gray-500">{{ $submission->department }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($submission->email)
                                    <div class="text-sm text-gray-900">{{ $submission->email }}</div>
                                @else
                                    <span class="text-sm text-gray-400 italic">Non renseigné</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($submission->age_groups)
                                    <div class="text-sm text-gray-900">
                                        @foreach($submission->age_groups as $age)
                                            <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full mr-1 mb-1">{{ $age }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($submission->specific_requests)
                                    <div class="text-sm text-gray-900">
                                        @foreach(array_slice($submission->specific_requests, 0, 3) as $request)
                                            <span class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full mr-1 mb-1">{{ $request }}</span>
                                        @endforeach
                                        @if(count($submission->specific_requests) > 3)
                                            <span class="text-xs text-gray-500">+{{ count($submission->specific_requests) - 3 }} autres</span>
                                        @endif
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($submission->consent_newsletter)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">✅ Oui</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">❌ Non</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-8 text-gray-500">
                <div class="text-4xl mb-2">📭</div>
                <p>Aucune soumission trouvée pour cette période</p>
            </div>
            @endif
        </div>
        @else
        <div class="text-center py-12">
            <div class="text-gray-400 text-6xl mb-4">📊</div>
            <h3 class="text-xl font-medium text-gray-900 mb-2">Aucun visiteur enregistré pour {{ $city->name }}</h3>
            <p class="text-gray-500 mb-4">Commencez par créer votre premier formulaire ou modifiez vos filtres.</p>
            <a href="{{ route('form.step1', $citySlug) }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                📝 Créer le premier formulaire
            </a>
        </div>
        @endif
    </div>
</div>

@if($totalCount > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function cityStats() {
    return {
        showTrends: true,
        showCountries: true,
        showAgeGroups: true,
        showRequests: true,
        charts: {},

        init() {
            this.$nextTick(() => {
                this.initializeCharts();
            });
        },

        toggleAll() {
            const newState = !this.showTrends;
            this.showTrends = newState;
            this.showCountries = newState;
            this.showAgeGroups = newState;
            this.showRequests = newState;
        },

        refreshCharts() {
            this.$nextTick(() => {
                Object.values(this.charts).forEach(chart => {
                    if (chart && typeof chart.destroy === 'function') {
                        chart.destroy();
                    }
                });
                this.charts = {};
                setTimeout(() => {
                    this.initializeCharts();
                }, 100);
            });
        },

        initializeCharts() {
            const chartData = {
                countries: @json($countriesData),
                departments: @json($departmentsData),
                trends: @json($trendsData),
                ageGroups: @json($ageGroupsData),
                specificRequests: @json($specificRequestsData),
                profiles: @json($profilesData)
            };

            const colors = ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#06B6D4', '#F97316', '#84CC16'];
            
            const commonOptions = {
                responsive: false,
                maintainAspectRatio: true,
                animation: { duration: 0 },
                interaction: { intersect: false }
            };

            // Graphique des tendances
            if (document.getElementById('trendsChart')) {
                this.charts.trends = new Chart(document.getElementById('trendsChart'), {
                    type: 'line',
                    data: {
                        labels: Object.keys(chartData.trends),
                        datasets: [{
                            label: 'Visiteurs',
                            data: Object.values(chartData.trends),
                            borderColor: colors[0],
                            backgroundColor: colors[0] + '20',
                            tension: 0.4,
                            fill: true
                        }]
                    },
                    options: {
                        ...commonOptions,
                        plugins: { legend: { display: false } },
                        scales: { y: { beginAtZero: true } }
                    }
                });
            }

            // Graphique des pays
            if (document.getElementById('countriesChart')) {
                this.charts.countries = new Chart(document.getElementById('countriesChart'), {
                    type: 'doughnut',
                    data: {
                        labels: Object.keys(chartData.countries),
                        datasets: [{
                            data: Object.values(chartData.countries),
                            backgroundColor: colors
                        }]
                    },
                    options: { ...commonOptions }
                });
            }

            // Graphique des groupes d'âge
            if (document.getElementById('ageGroupsChart')) {
                this.charts.ageGroups = new Chart(document.getElementById('ageGroupsChart'), {
                    type: 'polarArea',
                    data: {
                        labels: Object.keys(chartData.ageGroups),
                        datasets: [{
                            data: Object.values(chartData.ageGroups),
                            backgroundColor: colors
                        }]
                    },
                    options: { ...commonOptions }
                });
            }

            // Graphique des demandes
            if (document.getElementById('requestsChart')) {
                this.charts.requests = new Chart(document.getElementById('requestsChart'), {
                    type: 'bar',
                    data: {
                        labels: Object.keys(chartData.specificRequests),
                        datasets: [{
                            data: Object.values(chartData.specificRequests),
                            backgroundColor: colors[4],
                            borderColor: colors[4],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        ...commonOptions,
                        plugins: { legend: { display: false } },
                        scales: { 
                            y: { beginAtZero: true },
                            x: { 
                                ticks: { 
                                    maxRotation: 45,
                                    minRotation: 45 
                                } 
                            } 
                        }
                    }
                });
            }

            // Graphique des départements
            if (document.getElementById('departmentsChart')) {
                this.charts.departments = new Chart(document.getElementById('departmentsChart'), {
                    type: 'bar',
                    data: {
                        labels: Object.keys(chartData.departments),
                        datasets: [{
                            data: Object.values(chartData.departments),
                            backgroundColor: colors[5],
                            borderColor: colors[5],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        ...commonOptions,
                        plugins: { legend: { display: false } },
                        scales: { y: { beginAtZero: true } }
                    }
                });
            }

            // Graphique des profils
            if (document.getElementById('profilesChart')) {
                this.charts.profiles = new Chart(document.getElementById('profilesChart'), {
                    type: 'pie',
                    data: {
                        labels: Object.keys(chartData.profiles),
                        datasets: [{
                            data: Object.values(chartData.profiles),
                            backgroundColor: [colors[1], colors[3]]
                        }]
                    },
                    options: { ...commonOptions }
                });
            }
        }
    }
}
</script>
@endif