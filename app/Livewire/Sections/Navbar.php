<?php

namespace App\Livewire\Sections;

use App\Helpers\CartManagement;
use App\Models\Wishlist;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Navbar extends Component
{
    public $total_count = 0;
    public $total_wishlist_count = 0;

    public function mount()
    {
        $this->updateCartCount();
        $this->updateWishlistCount();
    }

    public function updateCartCount()
    {
        // Update cart item count from cookies
        $this->total_count = count(CartManagement::getCartItemsFromCookie());
    }

    public function updateWishlistCount()
    {
        if (Auth::check()) {
            $this->total_wishlist_count = Wishlist::where('user_id', Auth::id())->count();
        } else {
            $guest_id = session()->get('guest_id', Str::uuid()->toString());
            session()->put('guest_id', $guest_id);
            $this->total_wishlist_count = Wishlist::where('guest_id', $guest_id)->count();
        }
    }

    public function toggleWishlist($product_id)
    {
        if (Auth::check()) {
            $wishlist = Wishlist::where('user_id', Auth::id())->where('product_id', $product_id)->first();

            if ($wishlist) {
                $wishlist->delete();
            } else {
                Wishlist::create(['user_id' => Auth::id(), 'product_id' => $product_id]);
            }

            $this->total_wishlist_count = Wishlist::where('user_id', Auth::id())->count();
        } else {
            $guest_id = session()->get('guest_id', Str::uuid()->toString());
            session()->put('guest_id', $guest_id);

            $wishlist = Wishlist::where('guest_id', $guest_id)->where('product_id', $product_id)->first();

            if ($wishlist) {
                $wishlist->delete();
            } else {
                Wishlist::create(['guest_id' => $guest_id, 'product_id' => $product_id]);
            }

            $this->total_wishlist_count = Wishlist::where('guest_id', $guest_id)->count();
        }

        // Dispatch the event to notify other components
        $this->dispatch('wishlist-updated', ['count' => $this->total_wishlist_count]);
    }


    protected $listeners = [
        'update-cart-count' => 'updateCartCount',
        'update-wishlist-count' => 'updateWishlistCount',
    ];

    public function render()
    {
        return view('livewire.sections.navbar');
    }
}
