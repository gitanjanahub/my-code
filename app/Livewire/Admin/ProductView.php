<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use App\Models\ProductAttributeValue;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.adminpanel')]
#[Title('View Product')]

class ProductView extends Component
{

    public $product;

    public $rows = [];

    public $productAttributes;

    public $productToDelete = null;

    // Mount method to load the brand by ID
    public function mount($productId)
    {
        // Fetch the product with its variants
        $this->product = Product::with(['category', 'brand', 'variants'])->findOrFail($productId);

        // Pre-load existing variants for display
        $this->rows = ProductVariant::where('product_id', $this->product->id)
            ->get()
            ->map(function ($variant) {
                $attributes = json_decode($variant->attributes, true); // Decode attributes JSON

                $formattedAttributes = [];
                foreach ($attributes as $attribute) {
                    // Fetch attribute name and value
                    $attributeName = \App\Models\AttributeName::find($attribute['attribute_id']);
                    $attributeValue = \App\Models\AttributeValue::find($attribute['attribute_value_id']);

                    if ($attributeName && $attributeValue) {
                        $formattedAttributes[] = "{$attributeName->name}: {$attributeValue->value}";
                    }
                }

                return [
                    'attributes' => implode(', ', $formattedAttributes), // Format as string
                    'quantity' => $variant->stock_quantity,
                    'id' => $variant->id, // Keep track of variant ID
                ];
            })
            ->toArray();
    }




    public function mapSelectedAttributes($attributes)
{
    $mappedAttributes = [];

    foreach ($attributes as $attribute) {
        $attributeGroup = $this->productAttributes->firstWhere('attribute_id', $attribute['attribute_id']);

        if ($attributeGroup) {
            // Find the value name based on value ID
            $value = collect($attributeGroup)->firstWhere('value_id', $attribute['attribute_value_id']);

            if ($value) {
                $mappedAttributes[$attributeGroup[0]['value_name']] = $value['value_name'];
            }
        }
    }

    return $mappedAttributes;
}






    public function confirmDelete($id)
    {
        $this->productToDelete = $id;
    }

    public function deleteProduct()
    {
        if ($this->productToDelete) {
            $product = Product::find($this->productToDelete);

            if ($product) {

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


                $product->delete(); // Use soft delete

                session()->flash('message', 'Product deleted successfully!');

                return redirect()->route('admin.products');

                //$this->resetPage();
            } else {
                session()->flash('error', 'Product not found!');
            }

            $this->productToDelete = null;
        }
    }

    public function render()
    {
        return view('livewire.admin.product-view');
    }
}
