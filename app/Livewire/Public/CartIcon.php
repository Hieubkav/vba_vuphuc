<?php

namespace App\Livewire\Public;

use Livewire\Component;

class CartIcon extends Component
{
    public $cartCount = 0;

    public function mount()
    {
        $this->loadCartCount();
    }

    public function loadCartCount()
    {
        // TODO: Implement cart count logic
        // Hiện tại để mặc định là 0
        $this->cartCount = 0;
    }

    protected $listeners = ['cartUpdated' => 'loadCartCount'];

    public function render()
    {
        return view('livewire.public.cart-icon');
    }
}
