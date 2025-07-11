<div x-data="advancedStats()" class="min-h-screen bg-gradient-to-br from-blue-50 to-green-50 p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header avec titre et actions -->
        <div class="mb-8 bg-white rounded-xl shadow-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">📊 Statistiques Avancées</h1>
                    <p class="text-gray-600">Analyse des données touristiques Verdon</p>
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
                </div>
            </div>

            <!-- Filtres -->
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
                <!-- Filtre dates -->
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Période</label>
                    <div class="flex gap-2">
                        <input type="date" wire:model.live="dateFrom" 
                               class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm">
                        <input type="date" wire:model.live="dateTo"
                               class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm">
                    </div>
                </div>

                <!-- Filtre ville -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ville</label>
                    <select wire:model.live="selectedCity" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm">
                        <option value="">Toutes les villes</option>
                        @foreach($cities as $slug => $name)
                            <option value="{{ $slug }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filtre pays -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pays</label>
                    <select wire:model.live="selectedCountry"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm">
                        <option value="">Tous les pays</option>
                        @foreach($availableCountries as $country)
                            <option value="{{ $country }}">{{ $country }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filtre département -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Département</label>
                    <select wire:model.live="selectedDepartment"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm">
                        <option value="">Tous les départements</option>
                        @foreach($availableDepartments as $dept)
                            <option value="{{ $dept }}">{{ $dept }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Toggle graphiques -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Affichage</label>
                    <div class="flex gap-2">
                        <button @click="toggleAll()" 
                                class="px-3 py-1 bg-blue-100 text-blue-700 rounded text-xs font-medium hover:bg-blue-200 transition-colors">
                            Toggle All
                        </button>
                        <button @click="refreshCharts()" 
                                class="px-3 py-1 bg-green-100 text-green-700 rounded text-xs font-medium hover:bg-green-200 transition-colors">
                            Refresh
                        </button>
                    </div>
                </div>
            </div>

            <!-- Métriques rapides -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-4 text-white">
                    <div class="text-2xl font-bold">{{ number_format($totalCount) }}</div>
                    <div class="text-blue-100 text-sm">Total visiteurs</div>
                </div>
                <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-4 text-white">
                    <div class="text-2xl font-bold">{{ $totalToday }}</div>
                    <div class="text-green-100 text-sm">Aujourd'hui</div>
                </div>
                <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-lg p-4 text-white">
                    <div class="text-2xl font-bold">{{ $totalThisWeek }}</div>
                    <div class="text-yellow-100 text-sm">Cette semaine</div>
                </div>
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-4 text-white">
                    <div class="text-2xl font-bold">{{ $totalThisMonth }}</div>
                    <div class="text-purple-100 text-sm">Ce mois</div>
                </div>
            </div>
        </div>

        @if($totalCount > 0)
        <!-- Graphiques -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Évolution temporelle -->
            <div x-show="showTrends" class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">📈 Évolution (7 derniers jours)</h3>
                    <button @click="showTrends = !showTrends" class="text-gray-400 hover:text-gray-600">
                        <span x-show="showTrends">👁️</span>
                        <span x-show="!showTrends">👁️‍🗨️</span>
                    </button>
                </div>
                <div class="h-64">
                    <canvas id="trendsChart" width="400" height="256"></canvas>
                </div>
            </div>

            <!-- Top villes -->
            <div x-show="showCities" class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">🏙️ Villes populaires</h3>
                    <button @click="showCities = !showCities" class="text-gray-400 hover:text-gray-600">
                        <span x-show="showCities">👁️</span>
                        <span x-show="!showCities">👁️‍🗨️</span>
                    </button>
                </div>
                <div class="h-64">
                    <canvas id="citiesChart" width="400" height="256"></canvas>
                </div>
            </div>

            <!-- Pays d'origine -->
            <div x-show="showProfiles" class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">🌍 Pays d'origine</h3>
                    <button @click="showProfiles = !showProfiles" class="text-gray-400 hover:text-gray-600">
                        <span x-show="showProfiles">👁️</span>
                        <span x-show="!showProfiles">👁️‍🗨️</span>
                    </button>
                </div>
                <div class="h-64">
                    <canvas id="countriesChart" width="400" height="256"></canvas>
                </div>
            </div>

            <!-- Groupes d'âge -->
            <div x-show="showAgeGroups" class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">👥 Groupes d'âge</h3>
                    <button @click="showAgeGroups = !showAgeGroups" class="text-gray-400 hover:text-gray-600">
                        <span x-show="showAgeGroups">👁️</span>
                        <span x-show="!showAgeGroups">👁️‍🗨️</span>
                    </button>
                </div>
                <div class="h-64">
                    <canvas id="ageGroupsChart" width="400" height="256"></canvas>
                </div>
            </div>
        </div>

        <!-- Demandes spécifiques et départements -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
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

            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">📍 Départements français</h3>
                <div class="h-64">
                    <canvas id="departmentsChart" width="400" height="256"></canvas>
                </div>
            </div>
        </div>

        <!-- Table des données récentes -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">📝 Dernières soumissions (50 max)</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ville</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pays</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Département</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Newsletter</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($submissions as $submission)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $submission->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ ucfirst($submission->city) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $submission->country }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $submission->department ?: '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $submission->email ?: 'Non renseigné' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($submission->consent_newsletter)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">✅ Oui</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">❌ Non</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @else
        <div class="text-center py-12">
            <div class="text-gray-400 text-6xl mb-4">📊</div>
            <h3 class="text-xl font-medium text-gray-900 mb-2">Aucune donnée disponible</h3>
            <p class="text-gray-500">Modifiez vos filtres ou attendez de nouvelles soumissions.</p>
        </div>
        @endif
    </div>
</div>

@if($totalCount > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function advancedStats() {
    return {
        showCities: true,
        showProfiles: true,
        showTrends: true,
        showAgeGroups: true,
        showRequests: true,
        charts: {},

        init() {
            this.$nextTick(() => {
                this.initializeCharts();
            });
        },

        toggleAll() {
            const newState = !this.showCities;
            this.showCities = newState;
            this.showProfiles = newState;
            this.showTrends = newState;
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
            // Vérifier que les éléments canvas existent
            const canvases = ['trendsChart', 'citiesChart', 'countriesChart', 'ageGroupsChart', 'requestsChart', 'departmentsChart'];
            const existingCanvases = canvases.filter(id => document.getElementById(id));
            
            if (existingCanvases.length === 0) {
                return; // Aucun canvas disponible
            }

            const chartData = {
                cities: @json($citiesData),
                countries: @json($countriesData),
                departments: @json($departmentsData),
                trends: @json($trendsData),
                ageGroups: @json($ageGroupsData),
                specificRequests: @json($specificRequestsData)
            };

            const colors = ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#06B6D4', '#F97316', '#84CC16'];
            
            // Configuration commune pour tous les graphiques
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

            // Graphique des villes
            if (document.getElementById('citiesChart')) {
                this.charts.cities = new Chart(document.getElementById('citiesChart'), {
                    type: 'doughnut',
                    data: {
                        labels: Object.keys(chartData.cities).map(city => city.charAt(0).toUpperCase() + city.slice(1)),
                        datasets: [{
                            data: Object.values(chartData.cities),
                            backgroundColor: colors
                        }]
                    },
                    options: {
                        ...commonOptions
                    }
                });
            }

            // Graphique des pays
            if (document.getElementById('countriesChart')) {
                this.charts.countries = new Chart(document.getElementById('countriesChart'), {
                    type: 'bar',
                    data: {
                        labels: Object.keys(chartData.countries),
                        datasets: [{
                            data: Object.values(chartData.countries),
                            backgroundColor: colors[1],
                            borderColor: colors[1],
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
                    options: {
                        ...commonOptions
                    }
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
                        responsive: false,
                        maintainAspectRatio: true,
                        animation: { duration: 0 },
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
        }
    }
}
</script>
@endif