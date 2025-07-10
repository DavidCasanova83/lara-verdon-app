<?php

namespace App\Livewire;

use Livewire\Component;

class PostItNotes extends Component
{
    public $notes = '';
    public $isMinimized = false;

    public function mount()
    {
        // Les notes seront chargées côté client depuis localStorage
    }

    public function toggleMinimize()
    {
        $this->isMinimized = !$this->isMinimized;
    }

    public function render()
    {
        return view('livewire.post-it-notes');
    }
}
