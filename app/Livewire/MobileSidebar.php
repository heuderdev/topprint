<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class MobileSidebar extends Component
{
    public $isOpen = false;

    #[On('toggle-mobile-menu')]
    public function toggle()
    {
        $this->isOpen = !$this->isOpen;
    }

    public function close()
    {
        $this->isOpen = false;
    }

    public function render()
    {
        return view('livewire.mobile-sidebar');
    }
}
