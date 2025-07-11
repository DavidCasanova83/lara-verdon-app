<div class="min-h-screen bg-gradient-to-br from-blue-50 to-green-50 p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header avec titre et actions -->
        <div class="mb-8 bg-white rounded-xl shadow-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">📍 {{ $city->name }} - Formulaires détaillés</h1>
                    <p class="text-gray-600">Liste complète des formulaires soumis pour {{ $city->name }}</p>
                    <div class="mt-2 flex items-center space-x-4 text-sm text-gray-500">
                        <span>🏛️ Bureau d'information touristique</span>
                        <span>•</span>
                        <span>📊 {{ $totalCount }} formulaires au total</span>
                    </div>
                </div>
                <div class="flex gap-3">
                    <button wire:click="exportCsv" 
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium flex items-center gap-2 transition-colors">
                        📄 Exporter CSV
                    </button>
                    <button wire:click="resetFilters"
                            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        🔄 Reset Filtres
                    </button>
                    <a href="{{ route('form.step1', $citySlug) }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        📝 Nouveau formulaire
                    </a>
                </div>
            </div>

            <!-- Métriques rapides -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-4 text-white">
                    <div class="text-2xl font-bold">{{ number_format($totalCount) }}</div>
                    <div class="text-blue-100 text-sm">Total formulaires</div>
                </div>
                <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-4 text-white">
                    <div class="text-2xl font-bold">{{ $totalToday }}</div>
                    <div class="text-green-100 text-sm">Aujourd'hui</div>
                </div>
                <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-lg p-4 text-white">
                    <div class="text-2xl font-bold">{{ $totalThisWeek }}</div>
                    <div class="text-yellow-100 text-sm">Cette semaine</div>
                </div>
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-4 text-white">
                    <div class="flex items-center gap-2">
                        <div class="text-2xl font-bold">{{ $weeklyGrowth }}%</div>
                        @if($weeklyGrowth > 0)
                            <span class="text-green-200">📈</span>
                        @elseif($weeklyGrowth < 0)
                            <span class="text-red-200">📉</span>
                        @else
                            <span class="text-gray-200">➡️</span>
                        @endif
                    </div>
                    <div class="text-purple-100 text-sm">Croissance</div>
                </div>
            </div>

            <!-- Filtres et recherche -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4 mb-6">
                <!-- Recherche -->
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Recherche</label>
                    <input type="text" wire:model.live.debounce.300ms="search" 
                           placeholder="Email, pays, département, profil..."
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                </div>

                <!-- Filtre dates -->
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Période</label>
                    <div class="flex gap-2">
                        <input type="date" wire:model.live="dateFrom" 
                               class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        <input type="date" wire:model.live="dateTo"
                               class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    </div>
                </div>

                <!-- Filtre pays -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pays</label>
                    <select wire:model.live="selectedCountry"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        <option value="">Tous les pays</option>
                        @foreach($availableCountries as $country)
                            <option value="{{ $country }}">{{ $country }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filtre département -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Département</label>
                    <select wire:model.live="selectedDepartment"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        <option value="">Tous les départements</option>
                        @foreach($availableDepartments as $dept)
                            <option value="{{ $dept }}">{{ $dept }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Contrôles de pagination -->
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <label class="text-sm font-medium text-gray-700">Afficher :</label>
                    <select wire:model.live="perPage" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <span class="text-sm text-gray-500">par page</span>
                </div>
                <div class="text-sm text-gray-500">
                    {{ $submissions->count() }} sur {{ $submissions->total() }} résultats
                </div>
            </div>
        </div>

        @if($submissions->count() > 0)
        <!-- Table des formulaires -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" 
                                wire:click="sortBy('created_at')">
                                Date
                                @if($sortBy === 'created_at')
                                    <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" 
                                wire:click="sortBy('email')">
                                Email
                                @if($sortBy === 'email')
                                    <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" 
                                wire:click="sortBy('profile')">
                                Profil
                                @if($sortBy === 'profile')
                                    <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" 
                                wire:click="sortBy('country')">
                                Pays
                                @if($sortBy === 'country')
                                    <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Département
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Âges
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Demandes spécifiques
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Demandes générales
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Newsletter
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Autre demande
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($submissions as $submission)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="flex flex-col">
                                    <span class="font-medium">{{ $submission->created_at->format('d/m/Y') }}</span>
                                    <span class="text-gray-500 text-xs">{{ $submission->created_at->format('H:i') }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="flex flex-col">
                                    <span class="font-medium">{{ $submission->email ?: 'Non renseigné' }}</span>
                                    <span class="text-gray-500 text-xs">ID: {{ $submission->id }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @if($submission->profile === 'Famille') bg-green-100 text-green-800
                                    @elseif($submission->profile === 'Couple') bg-pink-100 text-pink-800
                                    @elseif($submission->profile === 'Groupe d\'amis') bg-blue-100 text-blue-800
                                    @elseif($submission->profile === 'Seul') bg-gray-100 text-gray-800
                                    @else bg-yellow-100 text-yellow-800
                                    @endif">
                                    {{ $submission->profile }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="flex items-center">
                                    <span class="mr-2">
                                        @if($submission->country === 'France') 🇫🇷
                                        @elseif($submission->country === 'Belgique') 🇧🇪
                                        @elseif($submission->country === 'Allemagne') 🇩🇪
                                        @elseif($submission->country === 'Italie') 🇮🇹
                                        @elseif($submission->country === 'Espagne') 🇪🇸
                                        @elseif($submission->country === 'Suisse') 🇨🇭
                                        @elseif($submission->country === 'Pays-Bas') 🇳🇱
                                        @elseif($submission->country === 'Angleterre') 🇬🇧
                                        @else 🌍
                                        @endif
                                    </span>
                                    {{ $submission->country }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $submission->department ?: '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                @if($submission->age_groups)
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($submission->age_groups as $age)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-800">
                                                {{ $age }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                @if($submission->specific_requests)
                                    <div class="flex flex-wrap gap-1 max-w-xs">
                                        @foreach($submission->specific_requests as $request)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-100 text-orange-800">
                                                {{ $request }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                @if($submission->general_requests)
                                    <div class="flex flex-wrap gap-1 max-w-xs">
                                        @foreach($submission->general_requests as $request)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-teal-100 text-teal-800">
                                                {{ $request }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($submission->consent_newsletter)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        ✅ Oui
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        ❌ Non
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 max-w-xs">
                                @if($submission->other_request)
                                    <div class="truncate" title="{{ $submission->other_request }}">
                                        {{ $submission->other_request }}
                                    </div>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="bg-white rounded-xl shadow-lg p-4">
            {{ $submissions->links() }}
        </div>
        @else
        <div class="text-center py-12 bg-white rounded-xl shadow-lg">
            <div class="text-gray-400 text-6xl mb-4">📋</div>
            <h3 class="text-xl font-medium text-gray-900 mb-2">Aucun formulaire trouvé</h3>
            <p class="text-gray-500">Modifiez vos filtres ou attendez de nouvelles soumissions pour {{ $city->name }}.</p>
        </div>
        @endif
    </div>
</div>