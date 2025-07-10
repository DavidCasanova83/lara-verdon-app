<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Formulaire Touristique' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-gray-100 text-gray-900 font-atkinson">
    <header class="bg-white shadow-sm">
        <div class="container mx-auto px-4 py-2">
            <div class="flex justify-between items-center">
                <h1 class="text-xl font-bold">
                    <a href="{{ route('home') }}" class="text-primary-600 hover:text-primary-700">
                        Verdon Tourisme
                    </a>
                </h1>

                <nav class="flex items-center space-x-4" x-data="headerNav()">
                    @auth
                        <!-- Menu pour utilisateurs connectés -->
                        <div class="relative">
                            <button @click="toggleStatsMenu()"
                                class="flex items-center space-x-2 text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md">
                                <span>📊 Stats par ville</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <div x-show="statsMenuOpen" @click.away="statsMenuOpen = false" x-transition
                                class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 z-50">
                                <div class="py-1">
                                    <a href="{{ route('city-stats', 'annot') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">📍 Annot</a>
                                    <a href="{{ route('city-stats', 'entrevaux') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">📍 Entrevaux</a>
                                    <a href="{{ route('city-stats', 'colmars-les-alpes') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">📍
                                        Colmars-les-Alpes</a>
                                    <a href="{{ route('city-stats', 'saint-andre-les-alpes') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">📍 Saint andré</a>
                                    <a href="{{ route('city-stats', 'la-palud-sur-verdon') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">📍
                                        La palud</a>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('advanced-statistics') }}"
                            class="text-green-600 hover:text-green-700 px-3 py-2 rounded-md">
                            📈 Stats Globales
                        </a>

                        <div class="relative">
                            <button @click="toggleUserMenu()"
                                class="flex items-center space-x-2 text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md">
                                <span>👤 {{ Auth::user()->name }}</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <div x-show="userMenuOpen" @click.away="userMenuOpen = false" x-transition
                                class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 z-50">
                                <div class="py-1">
                                    <a href="{{ route('settings.profile') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">⚙️ Paramètres</a>
                                    <form method="POST" action="{{ route('logout') }}" class="block">
                                        @csrf
                                        <button type="submit"
                                            class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            🚪 Déconnexion
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Boutons pour utilisateurs non connectés -->
                        <a href="{{ route('login') }}"
                            class="text-blue-600 hover:text-blue-700 px-3 py-2 rounded-md border border-blue-600 hover:border-blue-700 transition-colors">
                            Connexion
                        </a>
                        <a href="{{ route('register') }}"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-md transition-colors">
                            Inscription
                        </a>
                    @endauth
                </nav>
            </div>
        </div>
    </header>

    <main class="min-h-screen">
        @if (session('success'))
            <div class="container mx-auto px-4 pt-4">
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="container mx-auto px-4 pt-4">
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            </div>
        @endif

        {{ $slot }}
    </main>

    <!-- Post-it notes toujours visible -->
    @livewire('post-it-notes')

    @livewireScripts
    
    <script>
        function headerNav() {
            return {
                statsMenuOpen: false,
                userMenuOpen: false,
                
                toggleStatsMenu() {
                    this.statsMenuOpen = !this.statsMenuOpen;
                    this.userMenuOpen = false;
                },
                
                toggleUserMenu() {
                    this.userMenuOpen = !this.userMenuOpen;
                    this.statsMenuOpen = false;
                }
            }
        }
    </script>
</body>

</html>
