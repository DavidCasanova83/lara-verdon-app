@props([
    'selected' => false,
    'disabled' => false,
    'type' => 'button',
    'ariaPressed' => null
])

<button 
    type="{{ $type }}"
    {{ $attributes->merge([
        'class' => 'selection-button min-h-[44px] px-4 py-3 text-sm font-medium rounded-lg border-2 transition-all duration-200 transform hover:scale-105 focus:ring-2 focus:ring-green-500 focus:outline-none ' . 
                   ($selected ? 'bg-green-600 text-white border-green-600' : 'bg-white text-gray-700 border-gray-300 hover:border-green-400') . 
                   ($disabled ? ' opacity-50 cursor-not-allowed' : ' cursor-pointer hover:shadow-md')
    ]) }}
    aria-pressed="{{ $ariaPressed ?? ($selected ? 'true' : 'false') }}"
    @if($disabled) disabled @endif>
    {{ $slot }}
</button>