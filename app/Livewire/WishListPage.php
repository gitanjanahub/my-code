<?php

namespace App\Livewire;

use App\Livewire\Sections\Navbar;
use App\Livewire\Sections\Topbar;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Support\Str;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

#[Title('Wishlist Page')]

class WishListPage extends Component
{
    use WithPagination;

    use LivewireAlert;

    #[Url]
    public $perPage = 10;

    public $wishlistProductIds = []; // Stores product IDs for the wishlist
    protected $paginationTheme = 'bootstrap'; // Bootstrap theme for pagination

    // Mount function to fetch wishlist product IDs
    public function mount()
    {
        $this->fetchWishlistProductIds();
    }

    // Fetch wishlist product IDs for authenticated users or guests
    private function fetchWishlistProductIds()
    {
        $guestId = session()->get('guest_id') ?? request()->cookie('guest_id');

        // If no guest ID exists and the user is not authenticated, generate a new one
        if (!$guestId && !Auth::check()) {
            $guestId = (string) Str::uuid();
            session()->put('guest_id', $guestId);
            cookie()->queue('guest_id', $guestId, 43200); // Persist for 30 days
        }

        if (Auth::check()) {
            // Fetch wishlist for authenticated user
            $this->wishlistProductIds = Wishlist::where('user_id', Auth::id())
                ->pluck('product_id')
                ->toArray();
        } else {
            // Fetch wishlist for guest
            $this->wishlistProductIds = Wishlist::where('guest_id', $guestId)
                ->pluck('product_id')
                ->toArray();
        }

        // Update total wishlist count
        $this->updateWishlistCount();
    }

    // Update wishlist count
    private function updateWishlistCount()
    {
        $total_wishlist_count = Auth::check()
            ? Wishlist::where('user_id', Auth::id())->count()
            : Wishlist::where('guest_id', session()->get('guest_id'))->count();

        // Dispatch events to update wishlist count in UI components
        $this->dispatch('update-wishlist-count', total_wishlist_count: $total_wishlist_count)->to(Topbar::class);
        $this->dispatch('update-wishlist-count', total_wishlist_count: $total_wishlist_count)->to(Navbar::class);
    }

    // Toggle wishlist (add/remove product)
    // public function toggleWishlist($productId)
    // {
    //     // Retrieve guest ID from session or cookie, or generate a new one
    //     $guestId = session()->get('guest_id') ?? request()->cookie('guest_id');
    //     if (!$guestId) {
    //         $guestId = (string) Str::uuid();
    //         session()->put('guest_id', $guestId);
    //         cookie()->queue('guest_id', $guestId, 43200); // Persist for 30 days
    //     }

    //     // Define conditions for wishlist entry
    //     $conditions = Auth::check()
    //         ? ['user_id' => Auth::id(), 'product_id' => $productId]
    //         : ['guest_id' => $guestId, 'product_id' => $productId];

    //     // Check for existing wishlist entry
    //     $wishlistEntry = Wishlist::where($conditions)->withTrashed()->first();

    //     if ($wishlistEntry) {
    //         if ($wishlistEntry->trashed()) {
    //             // Restore if soft deleted
    //             $wishlistEntry->restore();
    //             $this->alert('success', 'Product added to wishlist successfully!', [
    //                 'position' => 'bottom-end',
    //                 'timer' => 3000,
    //                 'toast' => true
    //             ]);
    //         } else {
    //             // Soft delete the entry
    //             $wishlistEntry->delete();
    //             $this->alert('success', 'Product removed from wishlist successfully!', [
    //                 'position' => 'bottom-end',
    //                 'timer' => 3000,
    //                 'toast' => true
    //             ]);
    //         }
    //     } else {
    //         // Create a new wishlist entry
    //         Wishlist::create($conditions);
    //         $this->alert('success', 'Product added to wishlist successfully!', [
    //             'position' => 'bottom-end',
    //             'timer' => 3000,
    //             'toast' => true
    //         ]);
    //     }

    //     // Refresh wishlist product IDs and count
    //     $this->fetchWishlistProductIds();
    // }

    public function toggleWishlist($productId)
{
    // Retrieve guest ID from session or cookie, or generate a new one
    $guestId = session()->get('guest_id') ?? request()->cookie('guest_id');
    if (!$guestId) {
        $guestId = (string) Str::uuid();
        session()->put('guest_id', $guestId);
        cookie()->queue('guest_id', $guestId, 43200); // Persist for 30 days
    }

    // Define conditions for wishlist entry
    $conditions = Auth::check()
        ? ['user_id' => Auth::id(), 'product_id' => $productId]
        : ['guest_id' => $guestId, 'product_id' => $productId];

    // Check for existing wishlist entry
    $wishlistEntry = Wishlist::where($conditions)->withTrashed()->first();

    if ($wishlistEntry) {
        if ($wishlistEntry->trashed()) {
            // Restore if soft deleted
            $wishlistEntry->restore();
            // Update wishlistProductIds array
            $this->wishlistProductIds[] = $productId;
            $this->alert('success', 'Product added to wishlist successfully!', [
                'position' => 'bottom-end',
                'timer' => 3000,
                'toast' => true
            ]);
        } else {
            // Soft delete the entry
            $wishlistEntry->delete();
            // Remove the product ID from the wishlistProductIds array
            $this->wishlistProductIds = array_diff($this->wishlistProductIds, [$productId]);
            $this->alert('success', 'Product removed from wishlist successfully!', [
                'position' => 'bottom-end',
                'timer' => 3000,
                'toast' => true
            ]);
        }
    } else {
        // Create a new wishlist entry
        Wishlist::create($conditions);
        // Add the product ID to the wishlistProductIds array
        $this->wishlistProductIds[] = $productId;
        $this->alert('success', 'Product added to wishlist successfully!', [
            'position' => 'bottom-end',
            'timer' => 3000,
            'toast' => true
        ]);
    }

    // Update total wishlist count
    $this->updateWishlistCount();
}


public function render()
{
    // Paginate the wishlist products
    $wishlistProducts = Product::whereIn('id', $this->wishlistProductIds)
        ->paginate($this->perPage);  // You can adjust the number as needed for pagination

    // Get the total wishlist count (independent of pagination)
    $totalWishlistCount = count($this->wishlistProductIds);

    return view('livewire.wish-list-page', [
        'wishlistProducts' => $wishlistProducts,
        'totalWishlistCount' => $totalWishlistCount,  // Send the total count to the view
    ]);
}

}
