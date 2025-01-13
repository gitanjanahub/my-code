<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Livewire\Sections\Navbar;
use App\Livewire\Sections\Topbar;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Title;

#[Title('Product Details')]
class ProductDetailsPage extends Component
{
    use LivewireAlert;

    public $slug;
    public $product;
    public $groupedVariants = [];
    public $relatedProducts = [];
    public $selectedAttributes = []; // Holds selected attributes
    public $quantity = 1; // Default quantity

    protected $listeners = [
        'reloadComponent' => 'reloadProductDetails',
    ];

    public function mount($slug)
    {
        $this->slug = $slug;
        $this->loadProduct();
    }

    public function loadProduct()
    {
        // Load product details with variants
        $this->product = Product::with('variants')->where('slug', $this->slug)->firstOrFail();

        // Fetch attribute names and values
        $attributeNames = DB::table('attribute_names')->pluck('name', 'id');
        $attributeValues = DB::table('attribute_values')->pluck('value', 'id');

        // Group product attributes dynamically
        $this->groupedVariants = [];
        foreach ($this->product->variants as $variant) {
            $attributes = json_decode($variant->attributes, true);

            foreach ($attributes as $attribute) {
                $attributeName = $attributeNames[$attribute['attribute_id']] ?? null;
                $attributeValue = $attributeValues[$attribute['attribute_value_id']] ?? null;

                if ($attributeName && $attributeValue) {
                    $this->groupedVariants[$attributeName][] = [
                        'attribute_id' => $attribute['attribute_id'],
                        'value_id' => $attribute['attribute_value_id'],
                        'value' => $attributeValue
                    ];
                }
            }
        }

        // Remove duplicate attribute values
        foreach ($this->groupedVariants as $attributeName => &$values) {
            $values = collect($values)->unique('value_id')->values()->toArray();
        }

        // Decode product images
        $this->product->image = $this->product->image ? json_decode($this->product->image, true) : [];

        // Fetch related products
        $this->relatedProducts = Product::where('category_id', $this->product->category_id)
            ->where('id', '!=', $this->product->id)
            ->where('is_active', 1)
            ->take(5)
            ->get();
    }

    public function addToCart($productId)
    {
        // Validate attribute selection
        if (count($this->selectedAttributes) < count($this->groupedVariants)) {
            $this->alert('error', 'Please select all attributes before adding to cart!', [
                'position' => 'bottom-end',
                'timer' => 5000,
                'toast' => true
            ]);
        } else {
            //dd($this->selectedAttributes);
            // Add product to cart with selected attributes and quantity
            $totalCount = CartManagement::addItemToCartWithQty($productId, $this->selectedAttributes, $this->quantity);

            // Dispatch event to update cart count
            $this->dispatch('update-cart-count', total_count: $totalCount)->to(Topbar::class);
            $this->dispatch('update-cart-count', total_count: $totalCount)->to(Navbar::class);

            // Show success alert
            $this->alert('success', 'Product added to the cart successfully!', [
                'position' => 'bottom-end',
                'timer' => 3000,
                'toast' => true
            ]);

            // Optionally reset selected attributes and quantity
            $this->selectedAttributes = [];
            $this->quantity = 1;

            // Reload product details
            $this->dispatch('reloadComponent');
        }
    }

    public function reloadProductDetails()
    {
        $this->loadProduct(); // Reload product details
    }

    public function increaseQty()
    {
        $this->quantity++;
    }

    public function decreaseQty()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function render()
    {
        return view('livewire.product-details-page', [
            'product' => $this->product,
            'groupedVariants' => $this->groupedVariants,
            'relatedProducts' => $this->relatedProducts,
        ]);
    }
}
