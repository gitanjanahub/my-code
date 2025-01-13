<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductAttributeValue;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.adminpanel')]
#[Title('Edit Stock')]
class StockManagementEdit extends Component
{
    public $product; // The product for which stock is being managed
    public $rows = []; // Array of rows for dynamic form
    public $productAttributes; // Holds product attribute names and values

    public function mount($id)
    {
        // Fetch the product using the passed ID
        $this->product = Product::findOrFail($id);

        // Fetch product attributes grouped by attribute names
        $this->productAttributes = ProductAttributeValue::with(['attribute', 'attributeValue'])
            ->where('product_id', $this->product->id)
            ->get()
            ->groupBy('attribute.name')
            ->map(fn($items) => $items->map(fn($item) => [
                'attribute_id' => $item->attribute->id,
                'value_id' => $item->attributeValue->id,
                'value_name' => $item->attributeValue->value,
            ])->values());

        // Pre-load existing variants for editing
        $this->rows = ProductVariant::where('product_id', $this->product->id)
            ->get()
            ->map(function ($variant) {
                $selectedAttributes = $this->mapSelectedAttributes(json_decode($variant->attributes, true));
                return [
                    'selectedAttributes' => $selectedAttributes,
                    'quantity' => $variant->stock_quantity,
                    'id' => $variant->id, // Keep track of the variant ID for updates
                ];
            })
            ->toArray();
    }

    // Helper function to map JSON attributes to a structured array
    // public function mapSelectedAttributes($attributes)
    // {
    //     $mappedAttributes = [];
    //     foreach ($attributes as $attribute) {
    //         $mappedAttributes[$attribute['attribute_id']] = $attribute['attribute_value_id'];
    //     }
    //     return $mappedAttributes;
    // }

    public function addRow()
    {
        $this->rows[] = ['selectedAttributes' => [], 'quantity' => 0, 'id' => null];
    }

    public function removeRow($index)
    {
        // If it's an existing row, delete the variant from the database
        if (!is_null($this->rows[$index]['id'])) {
            ProductVariant::find($this->rows[$index]['id'])->delete();
        }
        unset($this->rows[$index]);
        $this->rows = array_values($this->rows); // Re-index array
    }

    // Edit: Ensure attributes are consistently formatted
public function mapSelectedAttributes($attributes)
{
    $mappedAttributes = [];
    foreach ($attributes as $attribute) {
        $mappedAttributes[$attribute['attribute_id']] = $attribute['attribute_value_id'];
    }
    return $mappedAttributes;
}

// Save method
public function save()
{
    $this->validate([
        'rows.*.selectedAttributes' => 'required|array|min:1',
        'rows.*.quantity' => 'required|integer|min:0',
    ]);

    foreach ($this->rows as $row) {
        // Prepare the attributes in the desired format
        $attributes = [];
        foreach ($row['selectedAttributes'] as $attributeId => $valueId) {
            $attributes[] = [
                'attribute_id' => $attributeId,
                'attribute_value_id' => $valueId,
            ];
        }

        // Manually encode the array into a JSON string with escaped quotes
        $attributesJson = json_encode($attributes); // Standard JSON encoding

        // Now wrap the JSON string into another JSON string to escape quotes
        $attributesJsonWithEscapedQuotes = json_encode($attributesJson);

        // Check for duplicates using the exact same format
        $exists = ProductVariant::where('product_id', $this->product->id)
            ->where('attributes', $attributesJsonWithEscapedQuotes)
            ->when(!is_null($row['id']), function ($query) use ($row) {
                $query->where('id', '!=', $row['id']); // Exclude the current variant being edited
            })
            ->exists();

        if ($exists) {
            session()->flash('error', 'Duplicate variant found!');
            return;
        }

        // If it's a new variant, create it
        if (is_null($row['id'])) {
            ProductVariant::create([
                'product_id' => $this->product->id,
                'attributes' => $attributesJsonWithEscapedQuotes, // Store the escaped JSON string
                'stock_quantity' => $row['quantity'],
            ]);
        } else {
            // If it's an existing variant, update it
            ProductVariant::where('id', $row['id'])->update([
                'attributes' => $attributesJson, // Update with escaped JSON string
                'stock_quantity' => $row['quantity'],
            ]);
        }
    }

    session()->flash('message', 'Stock updated successfully!');
    return redirect()->route('admin.stocks');
}








    public function render()
    {
        return view('livewire.admin.stock-management-edit');
    }
}
