<div>
    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Gestion des options de formulaires</h3>
            <p class="text-sm text-gray-500 mt-1">Ajoutez, modifiez ou supprimez les options disponibles dans les formulaires</p>
        </div>
        
        <div class="p-6">
            <!-- Category Selection -->
            <div class="mb-6">
                <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Catégorie</label>
                <select wire:model.live="selectedCategory" id="category" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @foreach($categories as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <!-- City Selection for Specific Requests -->
            @if($selectedCategory === 'specific_requests')
                <div class="mb-6">
                    <label for="city" class="block text-sm font-medium text-gray-700 mb-2">Ville (optionnel)</label>
                    <select wire:model.live="selectedCitySlug" id="city" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Toutes les villes</option>
                        @foreach($cities as $city)
                            <option value="{{ $city->slug }}">{{ $city->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <!-- Add/Edit Form -->
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <h4 class="text-md font-medium text-gray-900 mb-4">
                    {{ $editingOption ? 'Modifier l\'option' : 'Ajouter une nouvelle option' }}
                </h4>
                
                <form wire:submit.prevent="{{ $editingOption ? 'updateOption' : 'addOption' }}">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="key" class="block text-sm font-medium text-gray-700 mb-1">Clé (identifiant unique)</label>
                            <input type="text" wire:model="newOptionKey" id="key" 
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="ex: france, allemagne, 18-25">
                            @error('newOptionKey') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label for="value" class="block text-sm font-medium text-gray-700 mb-1">Valeur (texte affiché)</label>
                            <input type="text" wire:model="newOptionValue" id="value" 
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="ex: France, Allemagne, 18-25 ans">
                            @error('newOptionValue') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-2 mt-4">
                        @if($editingOption)
                            <button type="button" wire:click="resetForm" 
                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                Annuler
                            </button>
                        @endif
                        <button type="submit" 
                                class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-700">
                            {{ $editingOption ? 'Modifier' : 'Ajouter' }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Options List -->
            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                <div class="px-6 py-3 bg-gray-50 border-b border-gray-200">
                    <h4 class="text-sm font-medium text-gray-900">
                        Options existantes
                        @if($selectedCategory === 'specific_requests' && $selectedCitySlug)
                            pour {{ $cities->where('slug', $selectedCitySlug)->first()->name ?? 'Ville inconnue' }}
                        @endif
                    </h4>
                </div>
                
                <div class="divide-y divide-gray-200">
                    @forelse($options as $option)
                        <div class="p-4 flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-3 h-3 rounded-full {{ $option->is_active ? 'bg-green-400' : 'bg-red-400' }}"></div>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900">{{ $option->value }}</div>
                                    <div class="text-sm text-gray-500">
                                        Clé: {{ $option->key }}
                                        @if($option->city_slug)
                                            • Ville: {{ $cities->where('slug', $option->city_slug)->first()->name ?? $option->city_slug }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-2">
                                <button wire:click="toggleOption({{ $option->id }})" 
                                        class="text-sm {{ $option->is_active ? 'text-red-600 hover:text-red-900' : 'text-green-600 hover:text-green-900' }}">
                                    {{ $option->is_active ? 'Désactiver' : 'Activer' }}
                                </button>
                                
                                <button wire:click="editOption({{ $option->id }})" 
                                        class="text-sm text-indigo-600 hover:text-indigo-900">
                                    Modifier
                                </button>
                                
                                <button wire:click="deleteOption({{ $option->id }})" 
                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette option ?')"
                                        class="text-sm text-red-600 hover:text-red-900">
                                    Supprimer
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="mt-2 text-sm">Aucune option trouvée pour cette catégorie</p>
                            <p class="text-xs text-gray-400">Ajoutez votre première option ci-dessus</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Help Section -->
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="ml-3">
                <h4 class="text-sm font-medium text-blue-800">Aide</h4>
                <div class="mt-2 text-sm text-blue-700">
                    <ul class="space-y-1">
                        <li><strong>Pays (Form 1)</strong> : Options disponibles pour la sélection du pays de résidence</li>
                        <li><strong>Départements (Form 1)</strong> : Départements français pour la sélection géographique</li>
                        <li><strong>Tranches d'âge (Form 2)</strong> : Groupes d'âge pour la segmentation des visiteurs</li>
                        <li><strong>Demandes générales (Form 3)</strong> : Options communes à toutes les villes</li>
                        <li><strong>Demandes spécifiques (Form 3)</strong> : Options particulières à chaque ville</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>