<x-layouts.form title="Étape 3 - {{ $city->name }}">
    @livewire('form-step3', ['city' => $city->slug])
</x-layouts.form>
