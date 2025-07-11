<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\FormSubmission;
use App\Models\City;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CityStatistics extends Component
{
    use WithPagination;
    
    public $citySlug;
    public $city;
    
    // Filtres
    public $dateFrom;
    public $dateTo;
    public $selectedCountry = '';
    public $selectedDepartment = '';
    public $selectedAgeGroup = '';
    
    // Tri
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';
    
    // Recherche
    public $search = '';
    
    // Pagination
    public $perPage = 20;
    
    protected $queryString = [
        'search' => ['except' => ''],
        'sortBy' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
        'selectedCountry' => ['except' => ''],
        'selectedDepartment' => ['except' => ''],
        'perPage' => ['except' => 20],
    ];

    public function mount($citySlug)
    {
        $this->citySlug = $citySlug;
        $this->city = City::where('slug', $citySlug)->firstOrFail();
        
        // Par défaut : 3 derniers mois
        $this->dateTo = Carbon::now()->format('Y-m-d');
        $this->dateFrom = Carbon::now()->subMonths(3)->format('Y-m-d');
    }

    public function exportCsv()
    {
        $submissions = $this->getFilteredSubmissions();
        
        $filename = 'verdon_' . $this->citySlug . '_statistics_' . date('Y-m-d_H-i-s') . '.csv';
        
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
        $this->selectedCountry = '';
        $this->selectedDepartment = '';
        $this->selectedAgeGroup = '';
        $this->dateFrom = Carbon::now()->subMonths(3)->format('Y-m-d');
        $this->dateTo = Carbon::now()->format('Y-m-d');
        $this->search = '';
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedSelectedCountry()
    {
        $this->resetPage();
    }

    public function updatedSelectedDepartment()
    {
        $this->resetPage();
    }

    public function updatedDateFrom()
    {
        $this->resetPage();
    }

    public function updatedDateTo()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }


    public function render()
    {
        // Requête optimisée avec pagination et filtres
        $query = FormSubmission::where('city', $this->citySlug);

        // Appliquer les filtres
        if ($this->dateFrom) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }
        if ($this->dateTo) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }
        if ($this->selectedCountry) {
            $query->where('country', $this->selectedCountry);
        }
        if ($this->selectedDepartment) {
            $query->where('department', $this->selectedDepartment);
        }
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('email', 'like', '%' . $this->search . '%')
                  ->orWhere('country', 'like', '%' . $this->search . '%')
                  ->orWhere('department', 'like', '%' . $this->search . '%')
                  ->orWhere('profile', 'like', '%' . $this->search . '%')
                  ->orWhere('other_request', 'like', '%' . $this->search . '%');
            });
        }

        // Appliquer le tri
        $query->orderBy($this->sortBy, $this->sortDirection);

        // Pagination
        $submissions = $query->paginate($this->perPage);

        // Statistiques générales (requêtes séparées optimisées)
        $totalCount = FormSubmission::where('city', $this->citySlug)->count();
        $totalToday = FormSubmission::where('city', $this->citySlug)
            ->whereDate('created_at', Carbon::today())
            ->count();
        $totalThisWeek = FormSubmission::where('city', $this->citySlug)
            ->whereBetween('created_at', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ])
            ->count();
        $totalThisMonth = FormSubmission::where('city', $this->citySlug)
            ->whereBetween('created_at', [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth()
            ])
            ->count();

        // Calculer la croissance hebdomadaire
        $lastWeekCount = FormSubmission::where('city', $this->citySlug)
            ->whereBetween('created_at', [
                Carbon::now()->subWeeks(2)->startOfWeek(),
                Carbon::now()->subWeek()->endOfWeek()
            ])
            ->count();
        
        $weeklyGrowth = $lastWeekCount > 0 ? 
            round((($totalThisWeek - $lastWeekCount) / $lastWeekCount) * 100, 1) : 
            ($totalThisWeek > 0 ? 100 : 0);

        // Options pour les filtres (optimisées)
        $availableCountries = FormSubmission::where('city', $this->citySlug)
            ->distinct()
            ->pluck('country')
            ->filter()
            ->sort()
            ->values();
            
        $availableDepartments = FormSubmission::where('city', $this->citySlug)
            ->where('country', 'France')
            ->distinct()
            ->pluck('department')
            ->filter()
            ->sort()
            ->values();

        return view('livewire.city-statistics', [
            'totalCount' => $totalCount,
            'totalToday' => $totalToday,
            'totalThisWeek' => $totalThisWeek,
            'totalThisMonth' => $totalThisMonth,
            'weeklyGrowth' => $weeklyGrowth,
            'availableCountries' => $availableCountries,
            'availableDepartments' => $availableDepartments,
            'submissions' => $submissions
        ]);
    }
}