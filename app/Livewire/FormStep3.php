<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\FormOptionsService;
use App\Models\FormSubmission;

class FormStep3 extends Component
{
    public $city;
    public $specificRequests = [];
    public $generalRequests = [];
    public $otherRequest = '';

    public function mount($city)
    {
        $this->city = $city;
        
        // Récupérer les données de session
        $sessionData = session('form_data', []);
        if (!empty($sessionData)) {
            $this->specificRequests = $sessionData['specific_requests'] ?? [];
            $this->generalRequests = $sessionData['general_requests'] ?? [];
            $this->otherRequest = $sessionData['other_request'] ?? '';
        }
    }

    public function submit()
    {
        $this->validate([
            'specificRequests' => 'array',
            'generalRequests' => 'array',
            'otherRequest' => 'nullable|string|max:500'
        ], [
            'otherRequest.max' => 'Le texte ne peut pas dépasser 500 caractères.'
        ]);

        // Vérifier qu'au moins une demande est sélectionnée
        if (empty($this->specificRequests) && empty($this->generalRequests) && empty(trim($this->otherRequest))) {
            $this->addError('requests', 'Veuillez sélectionner au moins une demande ou préciser votre demande.');
            return;
        }

        // Récupérer toutes les données de session
        $formData = array_merge(
            session('form_data', []),
            [
                'specific_requests' => $this->specificRequests,
                'general_requests' => $this->generalRequests,
                'other_request' => $this->otherRequest
            ]
        );

        // Créer la soumission
        $submission = FormSubmission::create([
            'city' => $formData['city'],
            'country' => $formData['country'],
            'department' => $formData['department'] ?? null,
            'email' => $formData['email'],
            'consent_newsletter' => $formData['consent_newsletter'],
            'consent_data_processing' => $formData['consent_data_processing'],
            'profile' => $formData['profile'],
            'age_groups' => $formData['age_groups'],
            'specific_requests' => $formData['specific_requests'],
            'general_requests' => $formData['general_requests'],
            'other_request' => $formData['other_request']
        ]);

        // Nettoyer la session
        session()->forget('form_data');

        session()->flash('success', 'Merci ! Votre formulaire a été enregistré avec succès.');
        return redirect()->route('form.step1', $this->city);
    }

    public function render()
    {
        $formOptionsService = app(FormOptionsService::class);
        
        return view('livewire.form-step3', [
            'specificOptions' => $formOptionsService->getSpecificOptions($this->city),
            'generalOptions' => $formOptionsService->getGeneralOptions()
        ]);
    }
}
