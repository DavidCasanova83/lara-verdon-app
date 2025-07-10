<div class="mx-auto p-6 max-w-2xl bg-white shadow-lg rounded-lg">
    <h1 class="text-3xl font-bold mb-4">Étape 1</h1>

    <form wire:submit.prevent="nextStep">
        <!-- Question 1 : Pays de résidence -->
        <div class="mb-6">
            <p class="text-xl mb-4">Quel est le pays de résidence ?</p>
            <div class="flex flex-wrap justify-start gap-2 md:gap-4 mb-4">
                @foreach($countries as $countryOption)
                    <button type="button"
                            wire:click="$set('country', '{{ $countryOption }}')"
                            class="selection-button {{ $countryOption === $country ? 'selected' : '' }}">
                        {{ $countryOption }}
                    </button>
                @endforeach
            </div>

            @if($country === 'Autre')
                <div class="mb-4">
                    <label class="block text-lg">Veuillez préciser le pays :</label>
                    <input type="text"
                           wire:model="otherCountry"
                           class="border p-2 rounded w-full"
                           placeholder="Entrez le pays...">
                </div>
            @endif
        </div>

        <!-- Question 2 : Département (si France) -->
        @if($country === 'France')
            <div class="mb-6">
                <p class="text-xl mb-2">Préciser le département</p>
                <div class="flex flex-wrap gap-2 mb-2">
                    <button type="button"
                            wire:click="$set('departmentUnknown', {{ !$departmentUnknown }})"
                            class="selection-button {{ $departmentUnknown ? 'selected' : '' }}">
                        Inconnu
                    </button>
                </div>
                <select wire:model="department"
                        class="border p-2 rounded w-full mb-4 text-gray-700 focus:ring focus:ring-blue-200 focus:outline-none"
                        {{ $departmentUnknown ? 'disabled' : '' }}>
                    <option value="">Sélectionnez un département...</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept }}">{{ $dept }}</option>
                    @endforeach
                </select>
            </div>
        @endif

        <!-- Email -->
        <div class="mb-6">
            <label class="block text-lg font-semibold mb-2">Informations utilisateurs (optionnel)</label>
            <input type="email"
                   wire:model="email"
                   class="border p-3 rounded w-full text-gray-700 focus:ring focus:ring-blue-200 focus:outline-none"
                   placeholder="contact@verdontourisme.com (optionnel)">
            @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Consentements -->
        <div class="bg-gray-100 p-4 rounded-lg mb-4">
            <label class="flex items-start space-x-3">
                <input type="checkbox" wire:model="consentNewsletter" class="w-6 h-6 text-blue-500 border-gray-300 rounded">
                <span class="text-sm text-gray-700 leading-tight">
                    La personne souhaite recevoir la <span class="font-extrabold">newsletter</span> et des informations sur les événements.
                </span>
            </label>
        </div>

        <div class="bg-gray-100 p-4 rounded-lg mb-6">
            <label class="flex items-start space-x-3">
                <input type="checkbox" wire:model="consentDataProcessing" class="w-6 h-6 text-blue-500 border-gray-300 rounded">
                <span class="text-sm text-gray-700 leading-tight">
                    J'accepte que mes données soient traitées conformément à la
                    <a href="#" class="text-blue-500 hover:underline">politique de confidentialité RGPD</a>.
                </span>
            </label>
            @error('consentDataProcessing') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Bouton de validation -->
        <button type="submit"
                class="w-full bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg text-lg cursor-pointer transition-colors">
            Suivant
        </button>
    </form>
</div>
