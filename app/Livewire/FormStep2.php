<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\FormOptionsService;

class FormStep2 extends Component
{
    public $city;
    public $profile = '';
    public $profileUnknown = false;
    public $ageGroups = [];
    public $ageUnknown = false;

    public function mount($city)
    {
        $this->city = $city;
        
        // Récupérer les données de session
        $sessionData = session('form_data', []);
        if (!empty($sessionData)) {
            $this->profile = $sessionData['profile'] ?? '';
            $this->profileUnknown = $sessionData['profile_unknown'] ?? false;
            $this->ageGroups = $sessionData['age_groups'] ?? [];
            $this->ageUnknown = $sessionData['age_unknown'] ?? false;
        }
    }

    public function updatedAgeUnknown()
    {
        if ($this->ageUnknown) {
            $this->ageGroups = ['Inconnu'];
        } else {
            $this->ageGroups = [];
        }
    }

    public function updatedProfileUnknown()
    {
        if ($this->profileUnknown) {
            $this->profile = 'Inconnu';
        } else {
            $this->profile = '';
        }
    }

    public function toggleAgeGroup($ageOption)
    {
        if (in_array($ageOption, $this->ageGroups)) {
            $this->ageGroups = array_values(array_diff($this->ageGroups, [$ageOption]));
        } else {
            $this->ageGroups = array_merge($this->ageGroups, [$ageOption]);
        }
    }

    public function nextStep()
    {
        $this->validate([
            'profile' => 'required',
            'ageGroups' => 'required|array|min:1'
        ], [
            'profile.required' => 'Veuillez sélectionner un profil.',
            'ageGroups.required' => 'Veuillez sélectionner au moins une tranche d\'âge.',
            'ageGroups.min' => 'Veuillez sélectionner au moins une tranche d\'âge.'
        ]);

        // Sauvegarder en session
        session(['form_data' => array_merge(
            session('form_data', []),
            [
                'profile' => $this->profile,
                'profile_unknown' => $this->profileUnknown,
                'age_groups' => $this->ageGroups,
                'age_unknown' => $this->ageUnknown
            ]
        )]);

        return $this->redirectRoute('form.step3', $this->city);
    }

    public function render()
    {
        $formOptionsService = app(FormOptionsService::class);
        
        return view('livewire.form-step2', [
            'profiles' => $formOptionsService->getProfiles(),
            'ageGroupOptions' => $formOptionsService->getAgeGroups()
        ]);
    }
}
