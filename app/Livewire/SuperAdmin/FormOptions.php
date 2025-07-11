<?php

namespace App\Livewire\SuperAdmin;

use Livewire\Component;
use App\Models\FormOption;
use App\Models\City;

class FormOptions extends Component
{
    public $selectedCategory = 'countries';
    public $selectedCitySlug = '';
    public $newOptionValue = '';
    public $newOptionKey = '';
    public $editingOption = null;

    public $categories = [
        'countries' => 'Pays (Form 1)',
        'departments' => 'Départements (Form 1)',
        'age_groups' => 'Tranches d\'âge (Form 2)',
        'general_requests' => 'Demandes générales (Form 3)',
        'specific_requests' => 'Demandes spécifiques par ville (Form 3)',
    ];

    public function mount()
    {
        $this->selectedCategory = 'countries';
    }

    public function updatedSelectedCategory()
    {
        $this->selectedCitySlug = '';
        $this->resetForm();
    }

    public function addOption()
    {
        $this->validate([
            'newOptionValue' => 'required|string|max:255',
            'newOptionKey' => 'required|string|max:255',
        ]);

        FormOption::create([
            'category' => $this->selectedCategory,
            'key' => $this->newOptionKey,
            'value' => $this->newOptionValue,
            'city_slug' => $this->selectedCitySlug ?: null,
            'sort_order' => FormOption::where('category', $this->selectedCategory)->max('sort_order') + 1,
        ]);

        $this->resetForm();
        session()->flash('success', 'Option ajoutée avec succès !');
    }

    public function editOption($id)
    {
        $this->editingOption = FormOption::findOrFail($id);
        $this->newOptionValue = $this->editingOption->value;
        $this->newOptionKey = $this->editingOption->key;
    }

    public function updateOption()
    {
        $this->validate([
            'newOptionValue' => 'required|string|max:255',
            'newOptionKey' => 'required|string|max:255',
        ]);

        $this->editingOption->update([
            'value' => $this->newOptionValue,
            'key' => $this->newOptionKey,
        ]);

        $this->resetForm();
        session()->flash('success', 'Option modifiée avec succès !');
    }

    public function deleteOption($id)
    {
        FormOption::findOrFail($id)->delete();
        session()->flash('success', 'Option supprimée avec succès !');
    }

    public function toggleOption($id)
    {
        $option = FormOption::findOrFail($id);
        $option->update(['is_active' => !$option->is_active]);
        session()->flash('success', 'Statut de l\'option mis à jour !');
    }

    public function resetForm()
    {
        $this->newOptionValue = '';
        $this->newOptionKey = '';
        $this->editingOption = null;
    }

    public function render()
    {
        $options = FormOption::where('category', $this->selectedCategory)
            ->when($this->selectedCitySlug, function ($query) {
                $query->where('city_slug', $this->selectedCitySlug);
            })
            ->when(!$this->selectedCitySlug && $this->selectedCategory === 'specific_requests', function ($query) {
                $query->whereNull('city_slug');
            })
            ->orderBy('sort_order')
            ->orderBy('value')
            ->get();

        $cities = City::orderBy('name')->get();

        return view('livewire.super-admin.form-options', compact('options', 'cities'))
            ->layout('components.layouts.super-admin');
    }
}
