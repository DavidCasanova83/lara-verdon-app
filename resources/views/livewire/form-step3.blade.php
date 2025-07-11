<div class="min-h-screen bg-gradient-to-br from-green-50 to-blue-50 py-8">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Progress Bar -->
        <x-progress-bar :current-step="3" :total-steps="3" />
        
        <!-- Navigation -->
        <div class="flex justify-between items-center mb-6">
            <a href="{{ route('form.step2', $city) }}" class="flex items-center font-medium transition-colors" style="color: #3B9C92;" onmouseover="this.style.color='#2d7a72'" onmouseout="this.style.color='#3B9C92'">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Étape précédente
            </a>
            <span class="text-sm text-gray-500">Étape 3 sur 3</span>
        </div>
        
        <!-- Main Form Card -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">
                    Vos demandes
                </h1>
                <p class="text-gray-600">
                    Étape finale - Environ 2 minutes
                </p>
            </div>

            <form wire:submit.prevent="submit" class="space-y-8">
                <!-- Demandes spécifiques par ville -->
                @if(!empty($specificOptions))
                    <fieldset class="mb-8">
                        <legend class="text-xl font-semibold mb-4 text-gray-900">Demandes spécifiques ({{ ucfirst(str_replace('-', ' ', $city)) }})</legend>
                        <p class="text-sm text-gray-600 mb-4" id="specific-help">Sélection multiple possible</p>
                        <div class="flex flex-wrap gap-3 mb-4" role="group" aria-describedby="specific-help">
                            @foreach($specificOptions as $option)
                                <x-selection-button 
                                    type="button"
                                    wire:click="toggleSpecificRequest({{ json_encode($option) }})"
                                    :selected="in_array($option, $specificRequests)">
                                    {{ $option }}
                                </x-selection-button>
                            @endforeach
                        </div>
                        @if(count($specificRequests) > 0)
                            <div class="text-sm mt-2 flex items-center" style="color: #3B9C92;">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                </svg>
                                {{ count($specificRequests) }} demande(s) spécifique(s) sélectionnée(s)
                            </div>
                        @endif
                    </fieldset>
                @endif

                <!-- Demandes générales -->
                <fieldset class="mb-8">
                    <legend class="text-xl font-semibold mb-4 text-gray-900">Demandes générales</legend>
                    <p class="text-sm text-gray-600 mb-4" id="general-help">Sélection multiple possible</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-4" role="group" aria-describedby="general-help">
                        @foreach($generalOptions as $option)
                            <x-selection-button 
                                type="button"
                                wire:click="toggleGeneralRequest({{ json_encode($option) }})"
                                :selected="in_array($option, $generalRequests)"
                                class="text-left justify-start">
                                {{ $option }}
                            </x-selection-button>
                        @endforeach
                    </div>
                    @if(count($generalRequests) > 0)
                        <div class="text-sm mt-2 flex items-center" style="color: #3B9C92;">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                            </svg>
                            {{ count($generalRequests) }} demande(s) générale(s) sélectionnée(s)
                        </div>
                    @endif
                </fieldset>

                <!-- Autres demandes -->
                <div class="mb-8">
                    <label class="block text-xl font-semibold mb-4 text-gray-900">Autres demandes</label>
                    <div class="relative">
                        <textarea 
                            wire:model="otherRequest"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors text-gray-700 resize-none"
                            rows="4"
                            maxlength="500"
                            placeholder="Décrivez vos demandes spécifiques..."
                            x-data="{ count: 0 }"
                            x-init="count = $el.value.length"
                            @input="count = $el.value.length"
                            aria-describedby="other-request-help other-request-count"></textarea>
                        <div class="flex justify-between items-center mt-2">
                            <p id="other-request-help" class="text-sm text-gray-500">Optionnel - Précisez vos demandes particulières</p>
                            <span id="other-request-count" class="text-sm text-gray-500" x-text="count + ' / 500 caractères'"></span>
                        </div>
                    </div>
                    @error('otherRequest') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>

                @error('requests') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror

                <!-- Récapitulatif -->
                @if(count($specificRequests) > 0 || count($generalRequests) > 0 || $otherRequest)
                    <div class="bg-gray-50 p-6 rounded-xl border border-gray-200 mb-8">
                        <h3 class="text-lg font-semibold mb-4 text-gray-900">Récapitulatif de votre demande</h3>
                        <div class="space-y-3 text-sm">
                            @if(count($specificRequests) > 0)
                                <div>
                                    <strong class="text-gray-700">Demandes spécifiques :</strong>
                                    <span class="text-gray-600">{{ implode(', ', $specificRequests) }}</span>
                                </div>
                            @endif
                            @if(count($generalRequests) > 0)
                                <div>
                                    <strong class="text-gray-700">Demandes générales :</strong>
                                    <span class="text-gray-600">{{ implode(', ', $generalRequests) }}</span>
                                </div>
                            @endif
                            @if($otherRequest)
                                <div>
                                    <strong class="text-gray-700">Autres demandes :</strong>
                                    <span class="text-gray-600">{{ $otherRequest }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Actions -->
                <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                    <div class="flex items-center text-sm text-gray-500">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" style="color: #3B9C92;">
                            <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                        </svg>
                        Sauvegardé automatiquement
                    </div>
                    <button type="submit"
                            class="text-white font-bold py-3 px-8 rounded-lg text-lg transition-all duration-200 transform hover:scale-105 focus:ring-2 focus:outline-none flex items-center"
                            style="background-color: #3B9C92; box-shadow: 0 0 0 2px #3B9C92;"
                            onmouseover="this.style.backgroundColor='#2d7a72'"
                            onmouseout="this.style.backgroundColor='#3B9C92'"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-50 cursor-not-allowed">
                        <span wire:loading.remove>Envoyer le formulaire</span>
                        <span wire:loading>Envoi en cours...</span>
                        <svg wire:loading.remove class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
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
