<x-layouts.app>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-4xl font-extrabold text-center mb-6">
            🗺️ Sélectionnez une destination
        </h1>
        <p class="text-center mb-8 text-lg">
            Choisissez votre bureau d'information pour accéder au formulaire.
        </p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 place-items-center w-fit mx-auto">
            @foreach ($cities as $city)
                <a href="{{ route('form.step1', $city->slug) }}" class="city-card"
                    style="background-image: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('https://picsum.photos/300/300?random={{ $loop->index }}')">

                    <div class="city-card-overlay"></div>
                    <h2 class="city-card-content">{{ $city->name }}</h2>
                </a>
            @endforeach
        </div>

        <div class="text-center mt-8 space-x-4">
            <a href="{{ route('advanced-statistics') }}"
                class="text-green-600 hover:text-green-700 text-lg font-semibold">
                📊 Statistiques Avancées
            </a>
        </div>
    </div>
</x-layouts.app>
