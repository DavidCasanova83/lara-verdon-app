<div class="min-h-screen bg-gradient-to-br from-green-50 to-blue-50 py-8">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Progress Bar -->
        <x-progress-bar :current-step="2" :total-steps="3" />
        
        <!-- Navigation -->
        <div class="flex justify-between items-center mb-6">
            <a href="{{ route('form.step1', $city) }}" class="text-green-600 hover:text-green-800 flex items-center font-medium transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Étape précédente
            </a>
            <span class="text-sm text-gray-500">Étape 2 sur 3</span>
        </div>
        
        <!-- Main Form Card -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">
                    Profil du visiteur
                </h1>
                <p class="text-gray-600">
                    Étape 2 sur 3 - Environ 1 minute
                </p>
            </div>

            <form wire:submit.prevent="nextStep" class="space-y-8">
                <!-- Question 1 : Profil du visiteur -->
                <fieldset class="mb-8">
                    <legend class="text-xl font-semibold mb-4 text-gray-900">Quel est le profil du visiteur ?</legend>
                    <div class="flex flex-wrap gap-3 mb-4">
                        @foreach($profiles as $profileOption)
                            <x-selection-button 
                                type="button"
                                wire:click="$set('profile', {{ json_encode($profileOption) }})"
                                :selected="$profileOption === $profile"
                                :disabled="$profileUnknown">
                                {{ $profileOption }}
                            </x-selection-button>
                        @endforeach
                        <x-selection-button 
                            type="button"
                            wire:click="$toggle('profileUnknown')"
                            :selected="$profileUnknown"
                            class="bg-gray-100 hover:bg-gray-200 text-gray-700 border-gray-300">
                            Inconnu
                        </x-selection-button>
                    </div>
                    @error('profile') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                </fieldset>

                <!-- Question 2 : Tranches d'âge -->
                <fieldset class="mb-8">
                    <legend class="text-xl font-semibold mb-4 text-gray-900">Quelles sont les tranches d'âge ?</legend>
                    <p class="text-sm text-gray-600 mb-4" id="age-groups-help">Sélection multiple possible</p>
                    <div class="flex flex-wrap gap-3 mb-4" role="group" aria-describedby="age-groups-help">
                        @foreach($ageGroupOptions as $ageOption)
                            <x-selection-button 
                                type="button"
                                wire:click="toggleAgeGroup('{{ $ageOption }}')"
                                :selected="in_array($ageOption, $ageGroups)"
                                :disabled="$ageUnknown">
                                {{ $ageOption }}
                            </x-selection-button>
                        @endforeach
                        <x-selection-button 
                            type="button"
                            wire:click="$toggle('ageUnknown')"
                            :selected="$ageUnknown"
                            class="bg-gray-100 hover:bg-gray-200 text-gray-700 border-gray-300">
                            Inconnu
                        </x-selection-button>
                    </div>
                    @if(!$ageUnknown && count($ageGroups) > 0)
                        <div class="text-sm text-green-600 mt-2 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                            </svg>
                            {{ count($ageGroups) }} tranche(s) d'âge sélectionnée(s)
                        </div>
                    @endif
                    @error('ageGroups') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                </fieldset>

                <!-- Actions -->
                <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                    <div class="flex items-center text-sm text-gray-500">
                        <svg class="w-4 h-4 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                        </svg>
                        Sauvegardé automatiquement
                    </div>
                    <button type="submit"
                            class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-lg text-lg transition-all duration-200 transform hover:scale-105 focus:ring-2 focus:ring-green-500 focus:outline-none flex items-center"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-50 cursor-not-allowed">
                        <span wire:loading.remove>Continuer</span>
                        <span wire:loading>Traitement...</span>
                        <svg wire:loading.remove class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                        <svg wire:loading class="w-5 h-5 ml-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12a8 8 0 018-8v8z"/>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
