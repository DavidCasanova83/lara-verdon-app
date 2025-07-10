<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\FormOptionsService;

class FormStep1 extends Component
{
    public $city;
    public $country = 'France';
    public $otherCountry = '';
    public $department = '';
    public $departmentUnknown = false;
    public $email = '';
    public $consentNewsletter = false;
    public $consentDataProcessing = false;

    public function mount($city)
    {
        $this->city = $city;
        
        // Récupérer les données de session si elles existent
        $sessionData = session('form_data', []);
        if (!empty($sessionData)) {
            $this->country = $sessionData['country'] ?? 'France';
            $this->otherCountry = $sessionData['other_country'] ?? '';
            $this->department = $sessionData['department'] ?? '';
            $this->departmentUnknown = $sessionData['department_unknown'] ?? false;
            $this->email = $sessionData['email'] ?? '';
            $this->consentNewsletter = $sessionData['consent_newsletter'] ?? false;
            $this->consentDataProcessing = $sessionData['consent_data_processing'] ?? false;
        }
    }

    public function updatedDepartmentUnknown()
    {
        if ($this->departmentUnknown) {
            $this->department = '';
        }
    }

    public function nextStep()
    {
        $formOptionsService = app(FormOptionsService::class);
        $validDepartments = $formOptionsService->getDepartments();
        
        $this->validate([
            'email' => 'nullable|email',
            'consentDataProcessing' => 'required|accepted',
            'country' => 'required',
            'department' => [
                'required_if:country,France',
                'required_unless:departmentUnknown,true',
                'in:' . implode(',', $validDepartments)
            ]
        ], [
            'email.email' => 'Veuillez entrer une adresse email valide.',
            'consentDataProcessing.required' => 'Vous devez accepter le traitement des données.',
            'consentDataProcessing.accepted' => 'Vous devez accepter le traitement des données.',
            'country.required' => 'Veuillez sélectionner un pays.',
            'department.required_if' => 'Veuillez sélectionner un département.',
            'department.required_unless' => 'Veuillez sélectionner un département.',
            'department.in' => 'Veuillez sélectionner un département valide.'
        ]);

        // Sauvegarder en session
        session(['form_data' => [
            'city' => $this->city,
            'country' => $this->country === 'Autre' ? $this->otherCountry : $this->country,
            'other_country' => $this->otherCountry,
            'department' => $this->departmentUnknown ? 'Inconnu' : $this->department,
            'department_unknown' => $this->departmentUnknown,
            'email' => $this->email,
            'consent_newsletter' => $this->consentNewsletter,
            'consent_data_processing' => $this->consentDataProcessing
        ]]);

        return redirect()->route('form.step2', $this->city);
    }

    public function render()
    {
        $formOptionsService = app(FormOptionsService::class);
        
        return view('livewire.form-step1', [
            'countries' => $formOptionsService->getCountries(),
            'departments' => $formOptionsService->getDepartments()
        ]);
    }
}
