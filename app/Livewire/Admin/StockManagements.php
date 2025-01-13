<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.adminpanel')]

#[Title('Stocks')]

class StockManagements extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';

    public function render()
    {
        // Get products with their variants and related attributes
        $products = Product::query()
            ->where('name', 'like', '%' . $this->search . '%')
            ->with(['variants'])
            ->paginate(10); // Pagination (10 products per page)

        // Add logic to format attributes for each variant
        foreach ($products as $product) {
            foreach ($product->variants as $variant) {
                $attributes = json_decode($variant->attributes, true); // Decode the JSON attributes

                $formattedAttributes = [];
                foreach ($attributes as $attribute) {
                    // Fetch attribute name and value
                    $attributeName = \App\Models\AttributeName::find($attribute['attribute_id']);
                    $attributeValue = \App\Models\AttributeValue::find($attribute['attribute_value_id']);

                    // Check if the attribute and attribute value exist
                    if ($attributeName && $attributeValue) {
                        // Format the attributes (e.g., Size: Small, Color: Red)
                        $formattedAttributes[] = $attributeName->name . ': ' . $attributeValue->value;
                    }
                }

                // Attach formatted attributes to the variant for easy display
                $variant->formattedAttributes = implode(', ', $formattedAttributes);
            }
        }

        return view('livewire.admin.stock-managements', compact('products'));
    }
}
