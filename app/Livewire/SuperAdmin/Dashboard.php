<?php

namespace App\Livewire\SuperAdmin;

use Livewire\Component;
use App\Models\User;
use App\Models\FormSubmission;
use App\Models\City;
use App\Models\FormOption;

class Dashboard extends Component
{
    public function render()
    {
        $stats = [
            'users' => User::count(),
            'submissions' => FormSubmission::count(),
            'cities' => City::count(),
            'form_options' => FormOption::count(),
            'recent_submissions' => FormSubmission::latest()->take(5)->get(),
            'recent_users' => User::latest()->take(5)->get(),
        ];

        return view('livewire.super-admin.dashboard', compact('stats'))
            ->layout('components.layouts.super-admin');
    }
}
