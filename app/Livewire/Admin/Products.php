<?php
namespace App\Livewire\Admin;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.adminpanel')]
#[Title('Products')]

class Products extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = ''; // For search functionality
    public $selectedProducts = []; // Array for selected product IDs
    public $selectAll = false; // For the "Select All" checkbox
    public $productIdToDelete = null; // For single delete
    public $showDeleteModal = false; // Control single delete modal visibility
    public $showMultipleDeleteModal = false; // Control multiple delete modal visibility

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function toggleActive($productId, $inStock)
    {
        $product = Product::find($productId);

        if ($product) {
            // Set is_active based on checkbox state
            $product->in_stock = $inStock ? 1 : 0;
            $product->save();
            session()->flash('message', 'Status Changed successfully!');
        }
    }

    // Confirm deletion for a single product
    public function confirmDelete($id)
    {
        $this->productIdToDelete = $id;
        $this->showDeleteModal = true;
    }

    // Delete a single product
    public function deleteProduct()
    {
        if ($this->productIdToDelete) {
            $product = Product::findOrFail($this->productIdToDelete);

            // Delete associated images
            if ($product->thumb_image) {
                Storage::disk('public')->delete($product->thumb_image);
            }
            if ($product->image) {
                $images = json_decode($product->image, true);
                foreach ($images as $image) {
                    Storage::disk('public')->delete($image);
                }
            }

            // Delete the product
            $product->delete();

            session()->flash('message', 'Product deleted successfully.');
            $this->resetPage();
        }

        $this->productIdToDelete = null;
        $this->showDeleteModal = false;
    }

    // Toggle "Select All" checkbox
    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedProducts = Product::pluck('id')->toArray();
        } else {
            $this->selectedProducts = [];
        }
    }

    // Handle individual checkbox updates
    public function updatedSelectedProducts()
    {
        $this->selectAll = count($this->selectedProducts) === Product::count();
    }

    // Confirm multiple deletions
    public function confirmMultipleDelete()
    {
        if (count($this->selectedProducts)) {
            $this->showMultipleDeleteModal = true;
        }
    }

    // Delete multiple selected products
    public function deleteSelectedProducts()
    {
        if (count($this->selectedProducts)) {
            Product::whereIn('id', $this->selectedProducts)->each(function ($product) {
                // Delete associated images
                if ($product->thumb_image) {
                    Storage::disk('public')->delete($product->thumb_image);
                }
                if ($product->image) {
                    $images = json_decode($product->image, true);
                    foreach ($images as $image) {
                        Storage::disk('public')->delete($image);
                    }
                }
                $product->delete();
            });

            session()->flash('message', 'Selected products deleted successfully.');
            $this->resetPage();
        }

        $this->selectedProducts = [];
        $this->showMultipleDeleteModal = false;
    }

    public function render()
    {
        $products = Product::with('category', 'brand')
            ->where('name', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return view('livewire.admin.products', [
            'products' => $products,
            'totalProductsCount' => $products->total(),
        ]);
    }
}
