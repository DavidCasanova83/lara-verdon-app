<div class="mx-auto p-6 max-w-2xl bg-white shadow-lg rounded-lg">
    <h1 class="text-3xl font-bold mb-4">Étape 3</h1>

    <form wire:submit.prevent="submit">
        <!-- Demandes spécifiques par ville -->
        @if(!empty($specificOptions))
            <div class="mb-6">
                <p class="text-xl mb-4">Demandes spécifiques ({{ ucfirst(str_replace('-', ' ', $city)) }}) :</p>
                <div class="flex flex-wrap justify-start gap-2 md:gap-4 mb-4">
                    @foreach($specificOptions as $option)
                        <button type="button"
                                wire:click="
                                    @if(in_array('{{ $option }}', $specificRequests))
                                        $set('specificRequests', {{ json_encode(array_values(array_diff($specificRequests, [$option]))) }})
                                    @else
                                        $set('specificRequests', {{ json_encode(array_merge($specificRequests, [$option])) }})
                                    @endif
                                "
                                class="selection-button {{ in_array($option, $specificRequests) ? 'selected' : '' }}">
                            {{ $option }}
                        </button>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Demandes générales -->
        <div class="mb-6">
            <p class="text-xl mb-4">Demandes générales (Sélection multiple possible) :</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mb-4">
                @foreach($generalOptions as $option)
                    <button type="button"
                            wire:click="
                                @if(in_array('{{ $option }}', $generalRequests))
                                    $set('generalRequests', {{ json_encode(array_values(array_diff($generalRequests, [$option]))) }})
                                @else
                                    $set('generalRequests', {{ json_encode(array_merge($generalRequests, [$option])) }})
                                @endif
                            "
                            class="selection-button {{ in_array($option, $generalRequests) ? 'selected' : '' }} text-left">
                        {{ $option }}
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Autres demandes -->
        <div class="mb-6">
            <label class="block text-xl mb-4">Autres demandes :</label>
            <textarea 
                wire:model="otherRequest"
                class="border p-3 rounded w-full text-gray-700 focus:ring focus:ring-blue-200 focus:outline-none"
                rows="4"
                placeholder="Décrivez vos demandes spécifiques..."></textarea>
            @error('otherRequest') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        @error('requests') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

        <!-- Bouton de validation -->
        <button type="submit"
                class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg text-lg cursor-pointer transition-colors">
            Envoyer le formulaire
        </button>
    </form>
</div>
