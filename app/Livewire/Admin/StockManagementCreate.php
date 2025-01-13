<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductAttributeValue;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.adminpanel')]

#[Title('Create Stock')]

class StockManagementCreate extends Component
{
    public $product; // The product for which stock is being managed
    public $rows = []; // Array of rows for dynamic form
    public $productAttributes; // Holds product attribute names and values

    public function mount(Product $product)
    {
        $this->product = $product;

        // Fetch product attributes and their IDs with values
        // $this->productAttributes = ProductAttributeValue::with(['attribute', 'attributeValue'])
        //     ->where('product_id', $product->id)
        //     ->get()
        //     ->groupBy('attribute.name')
        //     ->map(fn($items) => $items->pluck('attributeValue.value', 'attributeValue.id')); // Map id => value pairs

         // Fetch product attributes with attribute ID, value ID, and value name
            $this->productAttributes = ProductAttributeValue::with(['attribute', 'attributeValue'])
            ->where('product_id', $product->id)
            ->get()
            ->groupBy('attribute.name')
            ->map(fn($items) => $items->map(fn($item) => [
                'attribute_id' => $item->attribute->id,
                'value_id' => $item->attributeValue->id,
                'value_name' => $item->attributeValue->value,
            ]));
    }


    public function addRow()
    {
        $this->rows[] = ['selectedAttributes' => [], 'quantity' => 0];
    }

    public function removeRow($index)
    {
        unset($this->rows[$index]);
        $this->rows = array_values($this->rows); // Re-index array
    }

    public function save()
{
    $this->validate([
        'rows.*.selectedAttributes' => 'required|array|min:1',
        'rows.*.quantity' => 'required|integer|min:1',
    ]);

    foreach ($this->rows as $row) {
        $selectedAttributes = $row['selectedAttributes'];

        // Prepare attributes array with attribute_id and attribute_value_id
        $attributes = [];
        foreach ($selectedAttributes as $attributeName => $valueId) {
            $attribute = $this->productAttributes[$attributeName]
                ->firstWhere('value_id', $valueId);

            $attributes[] = [
                'attribute_id' => $attribute['attribute_id'],
                'attribute_value_id' => $valueId,
            ];
        }

        //dd(json_encode($attributes));

        // Check if the variant already exists
        $exists = ProductVariant::where('product_id', $this->product->id)
            ->where('attributes', json_encode($attributes))
            ->exists();

        if ($exists) {
            session()->flash('error', 'Variant already exists!');
            return;
        }

        // Create the new variant
        ProductVariant::create([
            'product_id' => $this->product->id,
            'attributes' => json_encode($attributes), // Store both IDs
            'stock_quantity' => $row['quantity'],
        ]);
    }

    session()->flash('message', 'Stock added successfully!');
    return redirect()->route('admin.stocks');
}



    // public function save()
    // {
    //     $this->validate([
    //         'rows.*.selectedAttributes' => 'required|array|min:1',
    //         'rows.*.quantity' => 'required|integer|min:1',
    //     ]);

    //     foreach ($this->rows as $row) {
    //         $exists = ProductVariant::where('product_id', $this->product->id)
    //             ->where('attributes', json_encode($row['selectedAttributes']))
    //             ->exists();

    //         if ($exists) {
    //             session()->flash('error', 'Variant already exists!');
    //             return;
    //         }

    //         ProductVariant::create([
    //             'product_id' => $this->product->id,
    //             'attributes' => json_encode($row['selectedAttributes']),
    //             'stock_quantity' => $row['quantity'],
    //         ]);
    //     }

    //     session()->flash('message', 'Stock added successfully!');
    //     return redirect()->route('admin.stocks');
    // }

    public function render()
    {
        return view('livewire.admin.stock-management-create');
    }
}


