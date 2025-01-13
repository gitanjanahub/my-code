<?php

namespace App\Livewire\Admin;

use App\Models\AttributeName;
use App\Models\AttributeValue;
use App\Models\Product;
use App\Models\ProductVariant;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.adminpanel')]
#[Title('View Stock')]

class StockManagementView extends Component
{
    public $stocks;
    public $product;
    public $productId;

    public $rows = [];

    public $productAttributes;

    public function mount($productId)
{
    $this->productId = $productId;

    // Fetch the product details
    $this->product = Product::findOrFail($this->productId);

    // Fetch and format stock details
    $this->stocks = ProductVariant::where('product_id', $this->productId)
        ->get()
        ->map(function ($variant) {
            $attributes = json_decode($variant->attributes, true); // Decode attributes JSON

            $formattedAttributes = [];
            foreach ($attributes as $attribute) {
                // Fetch attribute name and value
                $attributeName = AttributeName::find($attribute['attribute_id']);
                $attributeValue = AttributeValue::find($attribute['attribute_value_id']);

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

    public function deleteStock($id)
    {
        $stock = ProductVariant::find($id);
        if ($stock) {
            $stock->delete();
            session()->flash('message', 'Stock entry deleted successfully!');

            // Refresh the stocks for the specific product
            $this->stocks = ProductVariant::with('product')//this product relation in productvarient model
                ->where('product_id', $this->productId)
                ->get();
        }
    }

    public function render()
    {
        return view('livewire.admin.stock-management-view');
    }
}
