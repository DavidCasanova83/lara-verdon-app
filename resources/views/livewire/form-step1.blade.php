<div class="min-h-screen bg-gradient-to-br from-green-50 to-blue-50 py-8">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Progress Bar -->
        <x-progress-bar :current-step="1" :total-steps="3" />

        <!-- Main Form Card -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">
                    Informations géographiques
                </h1>
                <p class="text-gray-600">
                    Étape 1 sur 3 - Environ 2 minutes
                </p>
            </div>

            @if (session()->has('message'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('message') }}
                </div>
            @endif
            
            @if (session()->has('success'))
                <div id="success-message" class="bg-gradient-to-r from-green-400 to-green-500 text-white px-6 py-4 rounded-xl mb-6 shadow-lg animate-pulse transition-opacity duration-500">
                    <div class="flex items-center justify-center">
                        <svg class="w-8 h-8 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <div class="text-center">
                            <div class="text-lg font-bold">{{ session('success') }}</div>
                            <div class="text-sm opacity-90 mt-1">Votre demande a été transmise avec succès !</div>
                        </div>
                        <button onclick="hideSuccessMessage()" class="ml-4 text-white hover:text-gray-200 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <script>
                    // Launch confetti on success
                    document.addEventListener('DOMContentLoaded', function() {
                        if (typeof window.launchConfetti === 'function') {
                            // Launch confetti immediately
                            window.launchConfetti();
                            
                            // Launch a second wave after 1 second
                            setTimeout(() => {
                                window.launchConfetti();
                            }, 1000);
                        }
                        
                        // Auto-hide success message after 5 seconds
                        setTimeout(() => {
                            hideSuccessMessage();
                        }, 5000);
                    });
                    
                    function hideSuccessMessage() {
                        const successMessage = document.getElementById('success-message');
                        if (successMessage) {
                            successMessage.style.opacity = '0';
                            setTimeout(() => {
                                successMessage.style.display = 'none';
                            }, 500);
                        }
                    }
                </script>
            @endif
            

            @if (session()->has('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form wire:submit.prevent="nextStep" class="space-y-8">
                <!-- Question 1 : Pays de résidence -->
                <fieldset class="mb-8">
                    <legend class="text-xl font-semibold mb-4 text-gray-900">Quel est le pays de résidence ?</legend>
                    <div class="flex flex-wrap gap-3 mb-4">
                        @foreach ($countries as $countryOption)
                            <x-selection-button type="button" wire:click="$set('country', '{{ $countryOption }}')"
                                :selected="$countryOption === $country" class="selection-button">
                                {{ $countryOption }}
                            </x-selection-button>
                        @endforeach
                    </div>

                    @if ($country === 'Autre')
                        <div class="mt-4 animate-fade-in">
                            <label class="block text-lg font-medium text-gray-700 mb-2">Veuillez préciser le pays
                                :</label>
                            <input type="text" wire:model="otherCountry"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                placeholder="Entrez le pays...">
                        </div>
                    @endif
                </fieldset>

                <!-- Question 2 : Département (si France) -->
                @if ($country === 'France')
                    <fieldset class="mb-8 animate-fade-in">
                        <legend class="text-xl font-semibold mb-4 text-gray-900">Préciser le département</legend>
                        <div class="mb-4">
                            <x-selection-button type="button"
                                wire:click="$set('departmentUnknown', {{ !$departmentUnknown }})" :selected="$departmentUnknown">
                                Inconnu
                            </x-selection-button>
                        </div>
                        <select wire:model="department"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors text-gray-700 {{ $departmentUnknown ? 'opacity-50 cursor-not-allowed' : '' }}"
                            {{ $departmentUnknown ? 'disabled' : '' }} aria-describedby="department-help">
                            <option value="">Sélectionnez un département...</option>
                            @foreach ($departments as $dept)
                                <option value="{{ $dept }}">{{ $dept }}</option>
                            @endforeach
                        </select>
                        <p id="department-help" class="text-sm text-gray-500 mt-2">Sélectionnez 'Inconnu' si vous ne
                            connaissez pas le département</p>
                    </fieldset>
                @endif

                <!-- Email -->
                <div class="mb-8">
                    <label class="block text-lg font-semibold mb-2 text-gray-900">Informations utilisateurs
                        (optionnel)</label>
                    <input type="email" wire:model="email"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors text-gray-700"
                        placeholder="contact@verdontourisme.com (optionnel)" aria-describedby="email-error">
                    @error('email')
                        <span id="email-error" class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Consentements -->
                <fieldset class="mb-8">
                    <legend class="text-lg font-semibold mb-4 text-gray-900">Consentements</legend>
                    <div class="space-y-4">
                        <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                            <label class="flex items-start space-x-3 cursor-pointer">
                                <input type="checkbox" wire:model="consentNewsletter"
                                    class="w-5 h-5 text-green-500 border-gray-300 rounded focus:ring-2 focus:ring-green-500 mt-0.5">
                                <span class="text-sm text-gray-700 leading-tight">
                                    La personne souhaite recevoir la <span class="font-bold">newsletter</span> et des
                                    informations sur les événements.
                                </span>
                            </label>
                        </div>

                        <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                            <label class="flex items-start space-x-3 cursor-pointer">
                                <input type="checkbox" wire:model="consentDataProcessing"
                                    class="w-5 h-5 text-green-500 border-gray-300 rounded focus:ring-2 focus:ring-green-500 mt-0.5">
                                <span class="text-sm text-gray-700 leading-tight">
                                    J'accepte que mes données soient traitées conformément à la
                                    <a href="#"
                                        class="text-green-600 hover:text-green-800 underline font-medium">politique de
                                        confidentialité RGPD</a>.
                                    <span class="text-red-500">*</span>
                                </span>
                            </label>
                            @error('consentDataProcessing')
                                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </fieldset>

                <!-- Actions -->
                <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                    <div class="flex items-center text-sm text-gray-500">
                        <svg class="w-4 h-4 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" />
                        </svg>
                        Sauvegardé automatiquement
                    </div>
                    <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-lg text-lg transition-all duration-200 transform hover:scale-105 focus:ring-2 focus:ring-green-500 focus:outline-none flex items-center"
                        wire:loading.attr="disabled" wire:loading.class="opacity-50 cursor-not-allowed">
                        <span wire:loading.remove>Continuer</span>
                        <span wire:loading>Traitement...</span>
                        <svg wire:loading.remove class="w-5 h-5 ml-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                        <svg wire:loading class="w-5 h-5 ml-2 animate-spin" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 12a8 8 0 018-8v8z" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
