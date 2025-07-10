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
            <h1 class="text-xl font-bold">
                <a href="{{ route('home') }}" class="text-primary-600 hover:text-primary-700">
                    Verdon Tourisme
                </a>
            </h1>
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
</body>
</html>
