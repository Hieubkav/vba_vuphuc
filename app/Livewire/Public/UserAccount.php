<?php

namespace App\Livewire\Public;

use Livewire\Component;

class UserAccount extends Component
{
    public $isLoggedIn = false;
    public $user = null;

    public function mount()
    {
        $this->checkAuthStatus();
    }

    public function checkAuthStatus()
    {
        $this->isLoggedIn = auth('student')->check();
        $this->user = auth('student')->user();
    }

    public function render()
    {
        return view('livewire.public.user-account');
    }
}
