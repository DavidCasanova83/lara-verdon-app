<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\FormSubmission;

class Statistics extends Component
{
    public $totalCount;
    public $citiesData;
    public $profilesData;
    public $departmentsData;
    public $ageGroupsData;
    public $specificRequestsData;
    public $generalRequestsData;
    public $otherRequestsData;

    public function mount()
    {
        $this->loadStatistics();
    }

    public function loadStatistics()
    {
        $this->totalCount = FormSubmission::count();

        // Données par ville
        $this->citiesData = FormSubmission::selectRaw('city, count(*) as count')
            ->groupBy('city')
            ->pluck('count', 'city')
            ->toArray();

        // Données par profil
        $this->profilesData = FormSubmission::selectRaw('profile, count(*) as count')
            ->groupBy('profile')
            ->pluck('count', 'profile')
            ->toArray();

        // Données par département
        $this->departmentsData = FormSubmission::selectRaw('department, count(*) as count')
            ->whereNotNull('department')
            ->where('department', '!=', 'Inconnu')
            ->groupBy('department')
            ->pluck('count', 'department')
            ->toArray();

        // Données par tranche d'âge
        $this->ageGroupsData = $this->getAgeGroupsData();

        // Demandes spécifiques
        $this->specificRequestsData = $this->getSpecificRequestsData();

        // Demandes générales
        $this->generalRequestsData = $this->getGeneralRequestsData();

        // Autres demandes
        $this->otherRequestsData = $this->getOtherRequestsData();
    }

    private function getAgeGroupsData()
    {
        $submissions = FormSubmission::all();
        $ageGroups = [];

        foreach ($submissions as $submission) {
            foreach ($submission->age_groups as $ageGroup) {
                $ageGroups[$ageGroup] = ($ageGroups[$ageGroup] ?? 0) + 1;
            }
        }

        return $ageGroups;
    }

    private function getSpecificRequestsData()
    {
        $submissions = FormSubmission::all();
        $requests = [];

        foreach ($submissions as $submission) {
            foreach ($submission->specific_requests as $request) {
                $requests[$request] = ($requests[$request] ?? 0) + 1;
            }
        }

        return $requests;
    }

    private function getGeneralRequestsData()
    {
        $submissions = FormSubmission::all();
        $requests = [];

        foreach ($submissions as $submission) {
            foreach ($submission->general_requests as $request) {
                $requests[$request] = ($requests[$request] ?? 0) + 1;
            }
        }

        return $requests;
    }

    private function getOtherRequestsData()
    {
        return FormSubmission::selectRaw('other_request, count(*) as count')
            ->whereNotNull('other_request')
            ->where('other_request', '!=', '')
            ->groupBy('other_request')
            ->pluck('count', 'other_request')
            ->toArray();
    }

    public function render()
    {
        return view('livewire.statistics');
    }
}
