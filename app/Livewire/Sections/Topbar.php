<?php

namespace App\Livewire\Sections;

use App\Helpers\CartManagement;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Component;

class Topbar extends Component
{
    public $total_count = 0;              // Total cart items
    public $total_wishlist_count = 0;    // Total wishlist items

    public $search = '';

    public function mount()
    {
        $this->updateCartCount();
        $this->updateWishlistCount();
        // Initialize the search query from the URL if present
        $this->search = request()->query('search', '');
    }

    public function updateCartCount()
    {
        // Fetch cart items from cookies
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

    #[On('update-cart-count')]
    public function handleCartUpdate($total_count)
    {
        $this->total_count = $total_count;
    }

    #[On('update-wishlist-count')]
    public function handleWishlistUpdate($total_wishlist_count)
    {
        $this->total_wishlist_count = $total_wishlist_count;
    }

    // public function searchProducts()
    // {
    //     if (!empty($this->searchQuery)) {
    //         return redirect()->to('/products?search=' . urlencode($this->searchQuery));
    //     }
    // }

    public function searchProducts()
    {
        if (!empty($this->search)) {
            return redirect()->to('/products?search=' . urlencode($this->search));
        }

        // Optionally redirect to the products page without a search term
        return redirect()->to('/products');
    }


    public function render()
    {
        return view('livewire.sections.topbar');
    }
}
