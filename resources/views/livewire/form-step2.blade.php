<div class="mx-auto p-6 max-w-2xl bg-white shadow-lg rounded-lg">
    <h1 class="text-3xl font-bold mb-4">Étape 2</h1>

    <form wire:submit.prevent="nextStep">
        <!-- Question 1 : Profil du visiteur -->
        <div class="mb-6">
            <p class="text-xl mb-4">Quel est le profil du visiteur ?</p>
            <div class="flex flex-wrap justify-start gap-2 md:gap-4 mb-4">
                @foreach($profiles as $profileOption)
                    <button type="button"
                            wire:click="$set('profile', '{{ $profileOption }}')"
                            class="selection-button {{ $profileOption === $profile ? 'selected' : '' }}"
                            {{ $profileUnknown ? 'disabled' : '' }}>
                        {{ $profileOption }}
                    </button>
                @endforeach
                <button type="button"
                        wire:click="$toggle('profileUnknown')"
                        class="selection-button {{ $profileUnknown ? 'selected' : '' }}">
                    Inconnu
                </button>
            </div>
            @error('profile') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Question 2 : Tranches d'âge -->
        <div class="mb-6">
            <p class="text-xl mb-4">Quelles sont les tranches d'âge ? (Sélection multiple possible)</p>
            <div class="flex flex-wrap justify-start gap-2 md:gap-4 mb-4">
                @foreach($ageGroupOptions as $ageOption)
                    <button type="button"
                            wire:click="
                                @if(in_array('{{ $ageOption }}', $ageGroups))
                                    $set('ageGroups', {{ json_encode(array_values(array_diff($ageGroups, [$ageOption]))) }})
                                @else
                                    $set('ageGroups', {{ json_encode(array_merge($ageGroups, [$ageOption])) }})
                                @endif
                            "
                            class="selection-button {{ in_array($ageOption, $ageGroups) ? 'selected' : '' }}"
                            {{ $ageUnknown ? 'disabled' : '' }}>
                        {{ $ageOption }}
                    </button>
                @endforeach
                <button type="button"
                        wire:click="$toggle('ageUnknown')"
                        class="selection-button {{ $ageUnknown ? 'selected' : '' }}">
                    Inconnu
                </button>
            </div>
            @error('ageGroups') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Bouton de validation -->
        <button type="submit"
                class="w-full bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg text-lg cursor-pointer transition-colors">
            Suivant
        </button>
    </form>
</div>
