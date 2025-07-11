<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\SuperAdmin\Dashboard;
use App\Livewire\SuperAdmin\FormOptions;

Route::middleware(['auth', 'super-admin'])->prefix('super-admin')->name('super-admin.')->group(function () {
    Route::get('/', Dashboard::class)->name('dashboard');
    Route::get('/form-options', FormOptions::class)->name('form-options');
});