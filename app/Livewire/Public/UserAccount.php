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
        // TODO: Implement authentication logic
        // Hiện tại để mặc định là chưa đăng nhập
        $this->isLoggedIn = false;
        $this->user = null;
    }

    public function render()
    {
        return view('livewire.public.user-account');
    }
}
