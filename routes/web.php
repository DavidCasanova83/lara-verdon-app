<?php

use App\Http\Controllers\CityController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\StatisticsController;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;

// Tourist Form Routes
Route::get('/', [CityController::class, 'index'])->name('home');
Route::get('/{city}/form1', [FormController::class, 'step1'])->name('form.step1');
Route::get('/{city}/form2', [FormController::class, 'step2'])->name('form.step2');
Route::get('/{city}/form3', [FormController::class, 'step3'])->name('form.step3');
Route::get('/statistiques', [StatisticsController::class, 'index'])->name('statistics');
Route::get('/statistiques-avancees', function() {
    return view('pages.advanced-statistics');
})->name('advanced-statistics');

// Routes protégées pour les statistiques par ville
Route::middleware(['auth'])->group(function () {
    Route::get('/stats-ville/{city}', function($city) {
        return view('pages.city-statistics', ['citySlug' => $city]);
    })->name('city-stats');
});

// Original Laravel routes
Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

require __DIR__.'/auth.php';
