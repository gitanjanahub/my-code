<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Livewire\Sections\Navbar;
use App\Livewire\Sections\Topbar;
use Livewire\Attributes\Title;
use Livewire\Attributes\On;
use Livewire\Component;

#[Title('Cart Page')]
class CartPage extends Component
{
    public $cart_items = [];
    public $grand_total;
    public $shipping = 0;

    public function mount()
    {
        $this->refreshCart();
    }

    #[On('forceRefresh')]
    public function refreshCart()
    {
        $this->cart_items = CartManagement::getCartItemsFromCookie();
        $this->grand_total = CartManagement::calculateGrandTotal($this->cart_items);
    }

    public function removeItem($product_id, $attributes)
    {
        $this->cart_items = CartManagement::removeCartItem($product_id, $attributes);

        $this->updateCartCount();

        // Emit refresh event
        $this->dispatch('forceRefresh');
    }

    public function increaseQty($product_id, $attributes)
    {
        $this->cart_items = CartManagement::incrementQuantityToCartItem($product_id, $attributes);
        $this->refreshCart();

        // Emit refresh event
        $this->dispatch('forceRefresh');
    }

    public function decreaseQty($product_id, $attributes)
    {
        $this->cart_items = CartManagement::decrementQuantityToCartItem($product_id, $attributes);
        $this->refreshCart();

        // Emit refresh event
        $this->dispatch('forceRefresh');
    }

    private function updateCartCount()
    {
        $cart_count = count($this->cart_items);

        // Dispatch cart count updates to Topbar and Navbar
        $this->dispatch('update-cart-count', total_count: $cart_count)->to(Topbar::class);
        $this->dispatch('update-cart-count', total_count: $cart_count)->to(Navbar::class);
        $this->refreshCart();
    }

    public function render()
    {
        return view('livewire.cart-page', [
            'cart_items' => $this->cart_items,
            'grand_total' => $this->grand_total,
        ]);
    }
}
