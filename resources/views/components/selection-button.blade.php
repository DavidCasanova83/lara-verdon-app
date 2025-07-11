@props([
    'selected' => false,
    'disabled' => false,
    'type' => 'button',
    'ariaPressed' => null
])

<button 
    type="{{ $type }}"
    {{ $attributes->merge([
        'class' => 'selection-button min-h-[44px] px-4 py-3 text-sm font-medium rounded-lg border-2 transition-all duration-200 transform hover:scale-105 focus:ring-2 focus:outline-none ' . 
                   ($selected ? 'text-white' : 'bg-white text-gray-700 border-gray-300') . 
                   ($disabled ? ' opacity-50 cursor-not-allowed' : ' cursor-pointer hover:shadow-md')
    ]) }}
    style="{{ $selected ? 'background-color: #3B9C92; border-color: #3B9C92;' : '' }}{{ !$selected ? 'border-color: #d1d5db;' : '' }}"
    aria-pressed="{{ $ariaPressed ?? ($selected ? 'true' : 'false') }}"
    @if($disabled) disabled @endif>
    {{ $slot }}
</button>