<?php

namespace App\Livewire;

use App\Livewire\Sections\Navbar;
use App\Livewire\Sections\Topbar;
use App\Models\AttributeName;
use App\Models\AttributeValue;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Url;
use Illuminate\Support\Str;

#[Title('Products Page')]

class ProductsPage extends Component
{

    use WithPagination;

    use LivewireAlert;

    protected $paginationTheme = 'bootstrap';

    //public $categories = [];

    #[Url]//important for search value pass in url
    public $selected_categories = []; // Stores selected category IDs

    #[Url]
    public $selected_brands = [];

    //#[Url]
    public $selected_attributes = [];

    #[Url]
    public $selected_values = []; // Stores selected attribute values per attribute

    public $priceRanges; // List of price ranges

    #[Url]
    public $selectedPrices = []; // Selected price IDs

    #[Url]
    public $sorting = 'latest'; // Default sorting option

    #[Url]
    public $perPage = 10;

    #[Url]
    public $search = ''; // Holds the search term

    public $wishlistProductIds = []; // To store wishlist product IDs


    // Sync `selected_values` with the URL query string
    protected $queryString = [
        'search' => ['except' => ''], // Add search to the query string
        'selected_values' => ['except' => []],
        'sorting' => ['except' => 'latest'],  // Add sorting with default value 'latest'
        'page' => ['except' => 1],            // Reset page to 1 when needed
        'perPage' => ['except' => 10], // Default value is 10
    ];








    public function mount()
    {

        // Initialize selected values as an empty array if not set
        $this->selected_values = request()->has('selected_values') ? request()->query('selected_values') : [];
        // Initialize priceRanges as a collection
        $this->priceRanges = collect([
            ['id' => 1, 'label' => '₹1 - ₹100', 'min' => 1, 'max' => 100],
            ['id' => 2, 'label' => '₹100 - ₹500', 'min' => 100, 'max' => 500],
            ['id' => 3, 'label' => '₹500 - ₹1000', 'min' => 500, 'max' => 1000],
            ['id' => 4, 'label' => '₹1000 - ₹5000', 'min' => 1000, 'max' => 5000],
            ['id' => 5, 'label' => '₹5000 - ₹10000', 'min' => 5000, 'max' => 10000],
            ['id' => 6, 'label' => '₹10000 - ₹100000', 'min' => 10000, 'max' => 100000],
            ['id' => 7, 'label' => '₹100000 - ₹200000', 'min' => 100000, 'max' => 200000],
        ]);


        $this->priceRanges = $this->priceRanges->map(function ($range) {
            $range['count'] = Product::query()
                ->where('is_active', 1) // Only active products
                ->where(function ($query) use ($range) {
                    $query->whereBetween('offer_price', [$range['min'], $range['max']])
                        ->where('offer_price', '>', 0) // Exclude offer_price = 0
                        ->orWhere(function ($subQuery) use ($range) {
                            $subQuery->where(function ($q) {
                                $q->whereNull('offer_price') // Handle NULL offer_price
                                    ->orWhere('offer_price', 0); // Handle 0 offer_price
                            })->whereBetween('price', [$range['min'], $range['max']]);
                        });
                })
                ->select('id') // Select only unique product IDs
                ->distinct() // Ensure unique IDs are counted
                ->count();

            return $range;
        });



        // Set selected prices as an empty array initially (none selected)
        $this->selectedPrices = [];
       // $this->selected_values = [];
        //Log::info('Selected Values:', $this->selected_values);
        //$this->selected_values = $this->selected_values ?? [];

        $this->fetchWishlistProductIds();

        $this->search = request()->query('search', '');
    }

    public function toggleCheckbox($attributeId, $valueId)
    {
        if (in_array($valueId, $this->selected_values[$attributeId] ?? [])) {
            $this->selected_values[$attributeId] = array_diff($this->selected_values[$attributeId], [$valueId]);
        } else {
            $this->selected_values[$attributeId][] = $valueId;
        }

        // Remove empty arrays from selected_values
        $this->selected_values = array_filter($this->selected_values, function ($values) {
            return !empty($values);
        });
    }

    public function updatedSelectedValues()
    {
        foreach ($this->selected_values as $attributeId => $values) {
            $this->selected_values[$attributeId] = array_filter($values, function ($value) {
                return $value !== false; // Keep only checked values
            });
        }
    }


    // Optional: Custom pagination methods
    public function previousPage()
    {
        $this->previousPage();
    }

    public function nextPage()
    {
        $this->nextPage();
    }

    // public function updateSorting($sorting)
    // {
    //     $this->sorting = $sorting; // Update the selected sorting option
    // }

    // Reset page whenever sorting changes
    public function updatedSorting($value)
    {
        $this->resetPage();
    }

    public function updatePerPage($value)
    {
        $this->perPage = $value;
        $this->resetPage(); // Reset to the first page when changing items per page
    }

    // Reset pagination when search changes
    public function updatedSearch($searchTerm)
    {
        $this->search = $searchTerm;
        $this->resetPage();
    }


    // Fetch wishlist product IDs for the logged-in user or guest
// private function fetchWishlistProductIds()
// {
//     if (Auth::check()) {
//         $this->wishlistProductIds = Wishlist::where('user_id', Auth::id())
//             ->pluck('product_id')
//             ->toArray();
//     } else {
//         $guestId = session()->get('guest_id');
//         if ($guestId) {
//             $this->wishlistProductIds = Wishlist::where('guest_id', $guestId)
//                 ->pluck('product_id')
//                 ->toArray();
//         } else {
//             $this->wishlistProductIds = []; // Ensure it doesn't retain stale data
//         }
//     }

//     // Calculate the total number of wishlist items based on the logged-in user or guest
//     $total_wishlist_count = Auth::check()
//         ? Wishlist::where('user_id', Auth::id())->count()
//         : Wishlist::where('guest_id', $guestId)->count();

//     // Dispatch events to update the wishlist count in the Topbar and Navbar components
//     $this->dispatch('update-wishlist-count', total_wishlist_count: $total_wishlist_count)->to(Topbar::class);
//     $this->dispatch('update-wishlist-count', total_wishlist_count: $total_wishlist_count)->to(Navbar::class);
// }

// Handle adding or removing from wishlist
// public function toggleWishlist($productId)
// {
//     // Retrieve guest ID from session or generate a new one
//     $guestId = session()->get('guest_id', Str::uuid()->toString());
//     session()->put('guest_id', $guestId); // Ensure guest_id is in session

//     // Define conditions based on whether the user is logged in or a guest
//     $conditions = Auth::check()
//         ? ['user_id' => Auth::id(), 'product_id' => $productId]
//         : ['guest_id' => $guestId, 'product_id' => $productId];

//     // Check if the wishlist entry exists, including soft-deleted ones
//     $wishlistEntry = Wishlist::where($conditions)->withTrashed()->first();

//     if ($wishlistEntry) {
//         if ($wishlistEntry->trashed()) {
//             // If the entry is soft deleted, restore it
//             $wishlistEntry->restore();

//             $this->alert('success', 'Product added to wishlist successfully!', [
//                 'position' => 'bottom-end',
//                 'timer' => 3000,
//                 'toast' => true
//             ]);
//         } else {
//             // If the product is already in the wishlist, delete it (soft delete)
//             $wishlistEntry->delete();

//             $this->alert('success', 'Product removed from wishlist successfully!', [
//                 'position' => 'bottom-end',
//                 'timer' => 3000,
//                 'toast' => true
//             ]);
//         }
//     } else {
//         // If the product is not in the wishlist, add it
//         Wishlist::create($conditions);

//         $this->alert('success', 'Product added to wishlist successfully!', [
//             'position' => 'bottom-end',
//             'timer' => 3000,
//             'toast' => true
//         ]);
//     }


//     $this->fetchWishlistProductIds();
// }


    private function fetchWishlistProductIds()
    {
        $guestId = session()->get('guest_id') ?? request()->cookie('guest_id');

        if (!$guestId && !Auth::check()) {
            // Generate a new guest ID if none exists
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

    private function updateWishlistCount()
    {
        $total_wishlist_count = Auth::check()
            ? Wishlist::where('user_id', Auth::id())->count()
            : Wishlist::where('guest_id', session()->get('guest_id'))->count();

        // Dispatch events to update wishlist count in the UI components
        $this->dispatch('update-wishlist-count', total_wishlist_count: $total_wishlist_count)->to(Topbar::class);
        $this->dispatch('update-wishlist-count', total_wishlist_count: $total_wishlist_count)->to(Navbar::class);
    }

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
                $this->alert('success', 'Product added to wishlist successfully!', [
                    'position' => 'bottom-end',
                    'timer' => 3000,
                    'toast' => true
                ]);
            } else {
                // Soft delete the entry
                $wishlistEntry->delete();
                $this->alert('success', 'Product removed from wishlist successfully!', [
                    'position' => 'bottom-end',
                    'timer' => 3000,
                    'toast' => true
                ]);
            }
        } else {
            // Create a new wishlist entry
            Wishlist::create($conditions);
            $this->alert('success', 'Product added to wishlist successfully!', [
                'position' => 'bottom-end',
                'timer' => 3000,
                'toast' => true
            ]);
        }

        // Refresh wishlist product IDs and count
        $this->fetchWishlistProductIds();
    }


    // Add a product to the wishlist
    public function addToWishlist($productId)
    {
        if (Auth::check()) {
            // If user is authenticated
            Wishlist::create([
                'user_id' => Auth::id(),
                'product_id' => $productId,
            ]);

            $total_wishlist_count = Wishlist::where('user_id', Auth::id())->count();

        } else {
            // If guest, use guest_id (stored in session)
            $guestId = session()->get('guest_id', Str::uuid()->toString());
            session()->put('guest_id', $guestId);

            Wishlist::create([
                'guest_id' => $guestId,
                'product_id' => $productId,
            ]);

            $total_wishlist_count = Wishlist::where('guest_id', $guestId)->count();
        }

        $this->dispatch('update-wishlist-count', total_wishlist_count: $total_wishlist_count)->to(Topbar::class);
        $this->dispatch('update-wishlist-count', total_wishlist_count: $total_wishlist_count)->to(Navbar::class);

        // Show success alert
        $this->alert('success', 'Product added to the wishlist successfully!', [
            'position' => 'bottom-end',
            'timer' => 3000,
            'toast' => true
        ]);


    }

    // Remove product from the wishlist
    public function removeFromWishlist($productId)
    {
        if (Auth::check()) {
            Wishlist::where('user_id', Auth::id())->where('product_id', $productId)->delete();
        } else {
            $guestId = session()->get('guest_id');
            Wishlist::where('guest_id', $guestId)->where('product_id', $productId)->delete();
        }


    }





    public function render()
    {

        //$search = request()->query('search', '');

        $searchTerm = trim($this->search); // Sanitize search term

        $categories = Category::query()
                    ->select('id', 'name', 'slug', 'image')
                    ->active() // Ensure category is active
                    ->notDeleted() // Ensure category is not deleted
                    ->latest()
                    ->withCount(['products' => function($query) {
                        $query->where('is_active', 1) // Only count active products
                            ->whereHas('category', function($query) {
                                $query->active()->notDeleted(); // Ensure the product's category is active and not deleted
                            });
                    }])
                    ->get();


        $brands = Brand::query()
                    ->select('id', 'name', 'slug', 'image')
                    ->active() // Ensure brand is active
                    ->latest()
                    ->withCount(['products' => function($query) {
                        $query->where('is_active', 1) // Only count active products
                            ->whereHas('brand', function($query) {
                                $query->active(); // Ensure the product's brand is active
                            });
                    }])
                    ->get();




        $attributesWithCounts = AttributeName::with(['values' => function ($query) {
            $query->withCount(['products as products_count' => function ($query) {
                $query->where('is_active', 1) // Only active products
                    ->whereHas('category', function ($query) {
                        $query->active()->notDeleted(); // Only active, non-deleted categories
                    })
                    ->whereHas('brand', function ($query) {
                        $query->active(); // Only active brands
                    })
                    ->distinct(); // Ensure distinct product counts
            }]);
        }])
        ->withCount(['values as products_count' => function ($query) {
            $query->whereHas('products', function ($query) {
                $query->where('is_active', 1) // Only active products
                    ->whereHas('category', function ($query) {
                        $query->active()->notDeleted(); // Only active, non-deleted categories
                    })
                    ->whereHas('brand', function ($query) {
                        $query->active(); // Only active brands
                    })
                    ->distinct(); // Ensure distinct product counts
            });
        }])
        ->get();



        // Get total count of active products in the selected price ranges
        $totalProductCount = 0;
            foreach ($this->selectedPrices as $selectedPriceId) {
                $range = collect($this->priceRanges)->firstWhere('id', $selectedPriceId);
                if ($range) {
                    $totalProductCount += $range['count']; // Add the active product count for each selected range
                }
            }


        $productQuery = Product::query()->active();

        if(!empty($this->selected_categories)){
            $productQuery->whereIn('category_id',$this->selected_categories);
        }

        if(!empty($this->selected_brands)){
            $productQuery->whereIn('brand_id',$this->selected_brands);
        }



        if (!empty($this->selectedPrices)) {
            $productQuery->where(function ($query) {
                foreach ($this->selectedPrices as $selectedPriceRange) {
                    [$min, $max] = explode('-', $selectedPriceRange); // Parse the price range string

                    $query->orWhere(function ($subQuery) use ($min, $max) {
                        $subQuery->whereBetween('offer_price', [(int)$min, (int)$max])
                            ->where('offer_price', '>', 0);
                    })->orWhere(function ($subQuery) use ($min, $max) {
                        $subQuery->whereNull('offer_price')
                            ->orWhere('offer_price', 0)
                            ->whereBetween('price', [(int)$min, (int)$max]);
                    });
                }
            });
        }


        // Apply attribute filters if selected
        if (!empty($this->selected_values)) {
            foreach ($this->selected_values as $attributeId => $values) {
                // Remove the unchecked (false) values
                $filteredValues = array_filter($values, function ($value) {
                    return $value !== false;
                });

                // Apply the filter if there are any selected values
                if (!empty($filteredValues)) {
                    $productQuery->whereHas('attributes', function ($query) use ($attributeId, $filteredValues) {
                        $query->where('attribute_id', $attributeId)
                            ->whereIn('attribute_value_id', array_keys($filteredValues)); // Filter by selected value IDs
                    });
                }
            }
        }


        //$productQuery->withAvg('reviews', 'rating')->orderBy('reviews_avg', 'desc');

        // Apply sorting
    if ($this->sorting === 'latest') {
        $productQuery->latest();
    }

    // if ($this->sorting === 'popularity') {
    //     $productQuery->withCount('orders')->orderBy('orders_count', 'desc');
    // }

    // if ($this->sorting === 'price_low_to_high') {
    //     $productQuery->orderBy('price', 'asc');
    // }

    // if ($this->sorting === 'price') {
    //     $productQuery->orderBy('price');
    // }

    // if ($this->sorting === 'price_high_to_low') {
    //     $productQuery->orderBy('price', 'desc');
    // }

    if ($this->sorting === 'price_low_to_high') {
        $productQuery->orderByRaw('CASE WHEN offer_price > 0 THEN offer_price ELSE price END ASC');
    }

    if ($this->sorting === 'price') {
        $productQuery->orderByRaw('CASE WHEN offer_price > 0 THEN offer_price ELSE price END');
    }

    if ($this->sorting === 'price_high_to_low') {
        $productQuery->orderByRaw('CASE WHEN offer_price > 0 THEN offer_price ELSE price END DESC');
    }


    if ($this->sorting === 'name') {
        $productQuery->orderBy('name', 'asc');
    }

    // if (!empty($search)) {
    //     $productQuery->where('name', 'like', '%' . $search . '%');
    // }

    // if (!empty($searchTerm)) {
    //     $productQuery->where(function ($query) use ($searchTerm) {
    //         $query->where('name', 'LIKE', "%{$searchTerm}%")
    //             ->orWhere('description', 'LIKE', "%{$searchTerm}%")
    //             ->orWhereHas('category', function ($subQuery) use ($searchTerm) {
    //                 $subQuery->where('name', 'LIKE', "%{$searchTerm}%");
    //             })
    //             ->orWhereHas('brand', function ($subQuery) use ($searchTerm) {
    //                 $subQuery->where('name', 'LIKE', "%{$searchTerm}%");
    //             });
    //     });
    // }

    if (!empty($searchTerm)) {
        $productQuery->where(function ($query) use ($searchTerm) {
            $query->where('name', 'LIKE', "%{$searchTerm}%")
                ->orWhere('description', 'LIKE', "%{$searchTerm}%");
        });
    }




        return view('livewire.products-page',[
            'products' => $productQuery->paginate($this->perPage),
            'categories' => $categories,
            'brands' => $brands,
            'attributesWithCounts' => $attributesWithCounts,
            'totalProductCount' => $totalProductCount, // Pass total active count to the view
        ]);
    }
}
