<x-layouts.form title="Étape 2 - {{ $city->name }}">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            @livewire('form-step2', ['city' => $city->slug])
        </div>
    </div>
</x-layouts.form>