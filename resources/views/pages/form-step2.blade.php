<x-layouts.form title="Étape 2 - {{ $city->name }}">
    @livewire('form-step2', ['city' => $city->slug])
</x-layouts.form>