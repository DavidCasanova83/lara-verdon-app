@props(['currentStep' => 1, 'totalSteps' => 3])

<div class="flex items-center justify-center mb-8">
    <div class="flex items-center space-x-4">
        @for ($i = 1; $i <= $totalSteps; $i++)
            <div class="flex items-center">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold transition-colors duration-200 
                        {{ $i <= $currentStep ? 'bg-green-600 text-white' : 'bg-gray-300 text-gray-600' }}">
                        {{ $i }}
                    </div>
                    <span class="ml-3 text-sm font-medium transition-colors duration-200 
                        {{ $i <= $currentStep ? 'text-green-600' : 'text-gray-600' }}">
                        @if ($i === 1)
                            Informations
                        @elseif ($i === 2)
                            Profil
                        @elseif ($i === 3)
                            Demandes
                        @endif
                    </span>
                </div>
                @if ($i < $totalSteps)
                    <div class="w-8 h-0.5 ml-4 
                        {{ $i < $currentStep ? 'bg-green-600' : 'bg-gray-300' }}">
                    </div>
                @endif
            </div>
        @endfor
    </div>
</div>