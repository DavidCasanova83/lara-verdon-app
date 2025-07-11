<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\FormSubmission;
use App\Models\City;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdvancedStatistics extends Component
{
    // Filtres
    public $dateFrom;
    public $dateTo;
    public $selectedCity = '';
    public $selectedCountry = '';
    public $selectedDepartment = '';
    public $selectedAgeGroup = '';

    // État des graphiques
    public $showCities = true;
    public $showProfiles = true;
    public $showTrends = true;
    public $showAgeGroups = true;
    public $showRequests = true;

    public function mount()
    {
        // Par défaut : 3 derniers mois
        $this->dateTo = Carbon::now()->format('Y-m-d');
        $this->dateFrom = Carbon::now()->subMonths(3)->format('Y-m-d');
    }

    public function exportCsv()
    {
        $submissions = $this->getFilteredSubmissions();
        
        $filename = 'verdon_statistics_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($submissions) {
            $file = fopen('php://output', 'w');
            
            // Headers CSV
            fputcsv($file, [
                'ID',
                'Date de soumission',
                'Ville visitée',
                'Pays d\'origine',
                'Département',
                'Email',
                'Groupes d\'âge',
                'Demandes spécifiques',
                'Demandes générales',
                'Newsletter',
                'RGPD'
            ]);

            foreach ($submissions as $submission) {
                fputcsv($file, [
                    $submission->id,
                    $submission->created_at->format('d/m/Y H:i'),
                    $submission->city,
                    $submission->country,
                    $submission->department,
                    $submission->email ?: 'Non renseigné',
                    is_array($submission->age_groups) ? implode(', ', $submission->age_groups) : '',
                    is_array($submission->specific_requests) ? implode(', ', $submission->specific_requests) : '',
                    is_array($submission->general_requests) ? implode(', ', $submission->general_requests) : '',
                    $submission->consent_newsletter ? 'Oui' : 'Non',
                    $submission->consent_data_processing ? 'Oui' : 'Non'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function resetFilters()
    {
        $this->selectedCity = '';
        $this->selectedCountry = '';
        $this->selectedDepartment = '';
        $this->selectedAgeGroup = '';
        $this->dateFrom = Carbon::now()->subMonths(3)->format('Y-m-d');
        $this->dateTo = Carbon::now()->format('Y-m-d');
    }

    private function getFilteredSubmissions()
    {
        $query = FormSubmission::query();

        // Filtres de date
        if ($this->dateFrom) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }
        if ($this->dateTo) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }

        // Filtres par champs
        if ($this->selectedCity) {
            $query->where('city', $this->selectedCity);
        }
        if ($this->selectedCountry) {
            $query->where('country', $this->selectedCountry);
        }
        if ($this->selectedDepartment) {
            $query->where('department', $this->selectedDepartment);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function render()
    {
        $submissions = $this->getFilteredSubmissions();
        
        // Statistiques générales
        $totalCount = $submissions->count();
        $totalToday = $submissions->where('created_at', '>=', Carbon::today())->count();
        $totalThisWeek = $submissions->where('created_at', '>=', Carbon::now()->startOfWeek())->count();
        $totalThisMonth = $submissions->where('created_at', '>=', Carbon::now()->startOfMonth())->count();

        // Données pour graphiques
        $citiesData = $submissions->groupBy('city')
            ->map->count()
            ->sortDesc()
            ->take(10);

        $countriesData = $submissions->groupBy('country')
            ->map->count()
            ->sortDesc()
            ->take(10);

        $departmentsData = $submissions->where('country', 'France')
            ->groupBy('department')
            ->map->count()
            ->sortDesc()
            ->take(10);

        // Évolution temporelle (7 derniers jours)
        $trendsData = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $count = $submissions->where('created_at', '>=', $date->startOfDay())
                               ->where('created_at', '<=', $date->endOfDay())
                               ->count();
            $trendsData->put($date->format('d/m'), $count);
        }

        // Groupes d'âge
        $ageGroupsData = collect();
        foreach ($submissions as $submission) {
            if ($submission->age_groups) {
                foreach ($submission->age_groups as $ageGroup) {
                    $ageGroupsData->put($ageGroup, ($ageGroupsData->get($ageGroup, 0) + 1));
                }
            }
        }
        $ageGroupsData = $ageGroupsData->sortDesc();

        // Demandes spécifiques les plus populaires
        $specificRequestsData = collect();
        foreach ($submissions as $submission) {
            if ($submission->specific_requests) {
                foreach ($submission->specific_requests as $request) {
                    $specificRequestsData->put($request, ($specificRequestsData->get($request, 0) + 1));
                }
            }
        }
        $specificRequestsData = $specificRequestsData->sortDesc()->take(8);

        // Profils visiteurs (newsletter vs non-newsletter)
        $profilesData = [
            'Abonnés newsletter' => $submissions->where('consent_newsletter', true)->count(),
            'Non abonnés' => $submissions->where('consent_newsletter', false)->count(),
        ];

        // Options pour les filtres
        $cities = City::orderBy('name')->pluck('name', 'slug');
        $availableCountries = $submissions->pluck('country')->unique()->sort()->values();
        $availableDepartments = $submissions->where('country', 'France')
            ->pluck('department')->unique()->sort()->values();

        return view('livewire.advanced-statistics', [
            'totalCount' => $totalCount,
            'totalToday' => $totalToday,
            'totalThisWeek' => $totalThisWeek,
            'totalThisMonth' => $totalThisMonth,
            'citiesData' => $citiesData,
            'countriesData' => $countriesData,
            'departmentsData' => $departmentsData,
            'trendsData' => $trendsData,
            'ageGroupsData' => $ageGroupsData,
            'specificRequestsData' => $specificRequestsData,
            'profilesData' => $profilesData,
            'cities' => $cities,
            'availableCountries' => $availableCountries,
            'availableDepartments' => $availableDepartments,
            'submissions' => $submissions->take(50) // Limitation pour l'affichage
        ]);
    }
}