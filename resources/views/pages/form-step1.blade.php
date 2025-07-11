<x-layouts.form title="Étape 1 - {{ $city->name }}">
    @livewire('form-step1', ['city' => $city->slug])
</x-layouts.form>
