<div class="container mx-auto p-6 max-w-6xl bg-white shadow-lg rounded-lg text-center">
    <h1 class="text-3xl font-bold mb-4">Statistiques détaillées</h1>
    <p class="text-xl mb-8">Nombre total de formulaires envoyés :
        <span class="text-4xl font-bold text-blue-600">{{ $totalCount }}</span>
    </p>

    @if($totalCount > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
            <div class="bg-gray-100 p-4 rounded-lg">
                <h2 class="text-lg font-semibold mb-2">Répartition par ville</h2>
                <canvas id="cityChart" width="400" height="200"></canvas>
            </div>
            <div class="bg-gray-100 p-4 rounded-lg">
                <h2 class="text-lg font-semibold mb-2">Répartition par profil</h2>
                <canvas id="profileChart" width="400" height="200"></canvas>
            </div>
            @if(!empty($departmentsData))
            <div class="bg-gray-100 p-4 rounded-lg">
                <h2 class="text-lg font-semibold mb-2">Répartition par département</h2>
                <canvas id="departmentChart" width="400" height="200"></canvas>
            </div>
            @endif
            <div class="bg-gray-100 p-4 rounded-lg">
                <h2 class="text-lg font-semibold mb-2">Répartition par tranche d'âge</h2>
                <canvas id="ageGroupChart" width="400" height="200"></canvas>
            </div>
            @if(!empty($specificRequestsData))
            <div class="bg-gray-100 p-4 rounded-lg">
                <h2 class="text-lg font-semibold mb-2">Demandes spécifiques</h2>
                <canvas id="specificRequestsChart" width="400" height="200"></canvas>
            </div>
            @endif
            @if(!empty($generalRequestsData))
            <div class="bg-gray-100 p-4 rounded-lg">
                <h2 class="text-lg font-semibold mb-2">Demandes générales</h2>
                <canvas id="generalRequestsChart" width="400" height="200"></canvas>
            </div>
            @endif
        </div>
    @else
        <p class="text-lg text-gray-600 mt-8">Aucune donnée à afficher pour le moment.</p>
    @endif

    <div class="text-center mt-8">
        <a href="{{ route('home') }}" class="text-primary-600 hover:text-primary-700 underline text-lg">
            Retour à l'accueil
        </a>
    </div>

    @if($totalCount > 0)
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Données des graphiques
            const chartData = @json([
                'cities' => $citiesData,
                'profiles' => $profilesData,
                'departments' => $departmentsData,
                'ageGroups' => $ageGroupsData,
                'specificRequests' => $specificRequestsData,
                'generalRequests' => $generalRequestsData
            ]);

            // Couleurs pour les graphiques
            const colors = ['#189187', '#0F5F5C', '#2CA08B', '#74C69D', '#F4A261', '#E76F51'];

            // Graphique des villes
            if (Object.keys(chartData.cities).length > 0) {
                new Chart(document.getElementById('cityChart'), {
                    type: 'bar',
                    data: {
                        labels: Object.keys(chartData.cities),
                        datasets: [{
                            data: Object.values(chartData.cities),
                            backgroundColor: colors[0],
                            borderColor: colors[1],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: { legend: { display: false } },
                        scales: { y: { beginAtZero: true } }
                    }
                });
            }

            // Graphique des profils
            if (Object.keys(chartData.profiles).length > 0) {
                new Chart(document.getElementById('profileChart'), {
                    type: 'pie',
                    data: {
                        labels: Object.keys(chartData.profiles),
                        datasets: [{
                            data: Object.values(chartData.profiles),
                            backgroundColor: colors
                        }]
                    },
                    options: { responsive: true }
                });
            }

            // Graphique des départements
            if (Object.keys(chartData.departments).length > 0) {
                new Chart(document.getElementById('departmentChart'), {
                    type: 'bar',
                    data: {
                        labels: Object.keys(chartData.departments),
                        datasets: [{
                            data: Object.values(chartData.departments),
                            backgroundColor: colors[2],
                            borderColor: colors[3],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
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

            // Graphique des tranches d'âge
            if (Object.keys(chartData.ageGroups).length > 0) {
                new Chart(document.getElementById('ageGroupChart'), {
                    type: 'pie',
                    data: {
                        labels: Object.keys(chartData.ageGroups),
                        datasets: [{
                            data: Object.values(chartData.ageGroups),
                            backgroundColor: colors
                        }]
                    },
                    options: { responsive: true }
                });
            }

            // Graphique des demandes spécifiques
            if (Object.keys(chartData.specificRequests).length > 0) {
                new Chart(document.getElementById('specificRequestsChart'), {
                    type: 'bar',
                    data: {
                        labels: Object.keys(chartData.specificRequests),
                        datasets: [{
                            data: Object.values(chartData.specificRequests),
                            backgroundColor: colors[4],
                            borderColor: colors[5],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: { legend: { display: false } },
                        scales: { y: { beginAtZero: true } }
                    }
                });
            }

            // Graphique des demandes générales
            if (Object.keys(chartData.generalRequests).length > 0) {
                new Chart(document.getElementById('generalRequestsChart'), {
                    type: 'bar',
                    data: {
                        labels: Object.keys(chartData.generalRequests),
                        datasets: [{
                            data: Object.values(chartData.generalRequests),
                            backgroundColor: colors[0],
                            borderColor: colors[1],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
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
        });
    </script>
    @endif
</div>
