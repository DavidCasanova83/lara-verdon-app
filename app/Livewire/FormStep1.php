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
        \Log::info('FormStep1::nextStep called', [
            'country' => $this->country,
            'department' => $this->department,
            'departmentUnknown' => $this->departmentUnknown,
            'email' => $this->email
        ]);

        try {
            // Validation simplifiée
            $rules = [
                'email' => 'nullable|email',
                'country' => 'required',
            ];

            $messages = [
                'email.email' => 'Veuillez entrer une adresse email valide.',
                'country.required' => 'Veuillez sélectionner un pays.',
            ];

            // Validation département seulement si France ET pas inconnu
            if ($this->country === 'France' && !$this->departmentUnknown) {
                $formOptionsService = app(FormOptionsService::class);
                $validDepartments = $formOptionsService->getDepartments();
                
                $rules['department'] = 'required|in:' . implode(',', $validDepartments);
                $messages['department.required'] = 'Veuillez sélectionner un département.';
                $messages['department.in'] = 'Veuillez sélectionner un département valide.';
            }

            $this->validate($rules, $messages);

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

            \Log::info('FormStep1: Validation passed, redirecting to step2');
            return redirect()->route('form.step2', $this->city);
            
        } catch (\Exception $e) {
            \Log::error('FormStep1 Error: ' . $e->getMessage());
            session()->flash('error', 'Erreur: ' . $e->getMessage());
        }
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
