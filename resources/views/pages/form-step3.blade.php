<x-layouts.app title="Étape 3 - {{ $city->name }}">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            @livewire('form-step3', ['city' => $city->slug])
        </div>
    </div>
</x-layouts.app>