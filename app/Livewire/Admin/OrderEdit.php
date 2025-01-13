<?php

namespace App\Livewire\Admin;

use App\Models\AttributeName;
use App\Models\AttributeValue;
use App\Models\Order;
use App\Models\OrderItem;

use App\Models\Product;
use App\Models\ProductAttributeValue;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.adminpanel')]
#[Title('Edit Order')]

class OrderEdit extends Component
{

    public $order;
    public $users;
    public $products;
    public $orderItems = [];
    public $payment_method;
    public $payment_status;
    public $status;
    public $shipping_amount= 0;
    public $notes;
    public $grand_total = 0;
    public $currency;
    public $shipping_method;
    public $user_id;

    public function mount($id)
{
    // Load the order with related items and their attributes
    $this->order = Order::with('items')->findOrFail($id);

    // Load users for dropdown or other functionalities
    $this->users = User::all();

    // Fetch distinct product IDs from product variants with stock
    $distinctProductIds = ProductVariant::select('product_id')
        ->where('stock_quantity', '>', 0)
        ->distinct()
        ->pluck('product_id');

    // Fetch available products based on distinct product IDs
    $this->products = Product::whereIn('id', $distinctProductIds)->get();

    // Initialize order details
    $this->user_id = $this->order->user_id;
    $this->status = $this->order->status;
    $this->payment_method = $this->order->payment_method;
    $this->payment_status = $this->order->payment_status;
    $this->shipping_amount = $this->order->shipping_amount;
    $this->notes = $this->order->notes;
    $this->currency = $this->order->currency;
    $this->shipping_method = $this->order->shipping_method;
    $this->grand_total = $this->order->grand_total;

    $this->orderItems = $this->order->items->map(function ($item) {
        // Decode the attributes from JSON
        $attributes = json_decode($item->attributes, true) ?? [];

        // Fetch available attributes for the product
        $availableAttributes = ProductVariant::where('product_id', $item->product_id)
            ->where('stock_quantity', '>', 0)
            ->get()
            ->flatMap(function ($variant) {
                return collect(json_decode($variant->attributes, true))
                    ->map(function ($attribute) {
                        return [
                            'attribute_id' => $attribute['attribute_id'],
                            'attribute_name' => AttributeName::where('id', $attribute['attribute_id'])->value('name'),
                            'attribute_value_id' => $attribute['attribute_value_id'],
                            'attribute_value' => AttributeValue::where('id', $attribute['attribute_value_id'])->value('value'),
                        ];
                    });
            })
            ->groupBy('attribute_name')
            ->map(function ($group, $attributeName) {
                return [
                    'id' => $group->first()['attribute_id'],
                    'name' => $attributeName,
                    'values' => $group->map(function ($item) {
                        return [
                            'id' => $item['attribute_value_id'],
                            'value' => $item['attribute_value'],
                        ];
                    })->unique('id')->values(),
                ];
            });

        // Preselect the attributes based on the stored values
        $preselectedAttributes = $availableAttributes->map(function ($attribute) use ($attributes) {
            // Look for the saved value in the order's attributes
            $selectedValue = collect($attributes)->firstWhere('attribute_id', $attribute['id']);
            return [
                'id' => $attribute['id'],
                'name' => $attribute['name'],
                'selected' => $selectedValue['attribute_value_id'] ?? null, // preselect the attribute value
                'values' => $attribute['values'], // dropdown options
            ];
        });

        return [
            'id' => $item->id,
            'product_id' => $item->product_id,
            'quantity' => $item->quantity,
            'unit_amount' => $item->unit_amount,
            'total_amount' => $item->total_amount,
            'attributes' => $attributes, // decoded attributes
            'availableAttributes' => $preselectedAttributes, // attributes with preselected values
        ];
    })->toArray();

    //dd($this->orderItems);  // For debugging
}




    public function addProduct()
    {
        $this->orderItems[] = [
            'product_id' => '',
            'quantity' => 1,
            'unit_amount' => 0,
            'total_amount' => 0,
            'attributes' => [],
            'availableAttributes' => [],
        ];

        $this->calculateGrandTotal();
    }

    public function updatedShippingAmount()
    {
        // Recalculate grand total when shipping amount is updated
        $this->calculateGrandTotal();
    }

    public function removeProduct($index)
    {
        // Get the order item data before unsetting it from the array
        $orderItem = $this->orderItems[$index];

        // If the order item has an 'id' and we need to remove it from the database
        if (isset($orderItem['id'])) {
            // Delete related order item attributes
            \App\Models\OrderItemAttribute::where('order_item_id', $orderItem['id'])->delete();

            // Delete the order item itself from the OrderItems table
            \App\Models\OrderItem::destroy($orderItem['id']);
        }

        // Remove the item from the array (for the frontend)
        unset($this->orderItems[$index]);
        $this->orderItems = array_values($this->orderItems);

        // Recalculate the grand total after removal
        $this->calculateGrandTotal();
    }




    // public function removeProduct($index)
    // {
    //     unset($this->orderItems[$index]);
    //     $this->orderItems = array_values($this->orderItems);
    //     $this->calculateGrandTotal();
    // }

    public function updated($field)
    {
        if (str_contains($field, 'orderItems')) {
            preg_match('/orderItems\.(\d+)\.(quantity|product_id)/', $field, $matches);

            if (isset($matches[1])) {
                $index = $matches[1];
                $fieldName = $matches[2];

                if (!isset($this->orderItems[$index])) {
                    return; // Exit if index is not found
                }

                if ($fieldName == 'product_id') {
                    $productId = $this->orderItems[$index]['product_id'];

                    if ($productId) {
                        // Fetch the product and its attributes
                        $product = Product::find($productId);

                        if ($product) {
                            $this->orderItems[$index]['unit_amount'] = $product->offer_price > 0
                                ? $product->offer_price
                                : $product->price;

                            // Fetch product attributes
                            // $attributes = ProductAttributeValue::where('product_id', $product->id)
                            //     ->with(['attribute', 'attributeValue'])
                            //     ->get()
                            //     ->groupBy('attribute.name')
                            //     ->map(function ($groupedValues, $attributeName) {
                            //         if ($groupedValues->isEmpty()) {
                            //             return null;
                            //         }

                            //         $firstItem = $groupedValues->first();
                            //         if (!$firstItem || !$firstItem->attribute) {
                            //             return null;
                            //         }

                            //         return [
                            //             'id' => $firstItem->attribute_id,
                            //             'name' => $attributeName,
                            //             'values' => $groupedValues->map(function ($item) {
                            //                 return $item->attributeValue ? [
                            //                     'id' => $item->attributeValue->id,
                            //                     'value' => $item->attributeValue->value,
                            //                 ] : null;
                            //             })->filter()->unique('id')->values()->toArray(),
                            //         ];
                            //     })
                            //     ->filter() // Remove null values
                            //     ->values(); // Re-index the collection

                            // Fetch product attributes

                            $attributes = ProductVariant::where('product_id', $productId)
                                ->where('stock_quantity', '>', 0)
                                ->get()
                                ->flatMap(function ($variant) {
                                    return collect(json_decode($variant->attributes, true))
                                        ->map(function ($attribute) {
                                            return [
                                                'attribute_id' => $attribute['attribute_id'],
                                                'attribute_name' => AttributeName::where('id', $attribute['attribute_id'])->value('name'),
                                                'attribute_value_id' => $attribute['attribute_value_id'],
                                                'attribute_value' => AttributeValue::where('id', $attribute['attribute_value_id'])->value('value'),
                                            ];
                                        });
                                })
                                ->groupBy('attribute_name')
                                ->map(function ($group, $attributeName) {
                                    return [
                                        'id' => $group->first()['attribute_id'],
                                        'name' => $attributeName,
                                        'values' => $group->map(function ($item) {
                                            return [
                                                'id' => $item['attribute_value_id'],
                                                'value' => $item['attribute_value'],
                                            ];
                                        })->unique('id')->values(),
                                    ];
                                });

                            $this->orderItems[$index]['availableAttributes'] = $attributes->isEmpty()
                                ? []
                                : $attributes->toArray();

                            $this->calculateTotalAmount($index);
                        }
                    } else {
                        // Reset values if no product is selected
                        $this->orderItems[$index]['unit_amount'] = 0;
                        $this->orderItems[$index]['total_amount'] = 0;
                    }
                } elseif ($fieldName == 'quantity') {
                    // Recalculate total amount if quantity changes
                    $this->calculateTotalAmount($index);
                }
            }
        }
    }




    private function calculateTotalAmount($index)
    {
        $unitAmount = $this->orderItems[$index]['unit_amount'];
        $quantity = $this->orderItems[$index]['quantity'];
        $this->orderItems[$index]['total_amount'] = $unitAmount * $quantity;
        $this->calculateGrandTotal();
    }

    private function calculateGrandTotal()
    {
        $this->grand_total = array_sum(array_column($this->orderItems, 'total_amount')) + $this->shipping_amount;
    }

    public function save()
    {
        $this->validateOrder();

        DB::beginTransaction();

        try {
            $this->updateOrder();
            $this->updateOrderItems();

            DB::commit();

            session()->flash('success', 'Order updated successfully!');
            return redirect()->route('admin.orders');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to update order: ' . $e->getMessage());
        }
    }

    protected function validateOrder()
    {
        $odr= $this->validate([
            'user_id' => 'required|exists:users,id',
            'orderItems' => 'required|array|min:1',
            'orderItems.*.product_id' => 'required|exists:products,id',
            'orderItems.*.quantity' => 'required|integer|min:1',
            'orderItems.*.unit_amount' => 'required|numeric|min:0',
            'orderItems.*.total_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'payment_status' => 'required|string',
            'status' => 'required|string|in:new,processing,shipped,delivered,cancelled',
            'currency' => 'required|string|max:5',
            'shipping_amount' => 'nullable|numeric|min:0',
            'shipping_method' => 'nullable|string',
            'notes' => 'nullable|string',
            'grand_total' => 'required|numeric|min:0',
        ], [
            // Custom error messages
            'user_id.required' => 'Please select a customer.',
            'orderItems.required' => 'At least one product must be added to the order.',
            'orderItems.*.product_id.required' => 'Product is required.',
            'orderItems.*.product_id.exists' => 'Selected product does not exist.',
            'orderItems.*.quantity.required' => 'Quantity is required.',
            'orderItems.*.quantity.min' => 'Quantity must be at least 1.',
            'orderItems.*.unit_amount.required' => 'Unit amount is required.',
            'orderItems.*.total_amount.required' => 'Total amount is required.',
            'payment_method.required' => 'Payment method is required.',
            'payment_status.required' => 'Payment status is required.',
            'status.required' => 'Order status is required.',
            'currency.required' => 'Currency is required.',
            ], [
            // Custom attribute names
            'user_id' => 'customer',
            'orderItems.*.product_id' => 'product',
            'orderItems.*.quantity' => 'quantity',
            'orderItems.*.unit_amount' => 'unit amount',
            'orderItems.*.total_amount' => 'total amount',
            'payment_method' => 'payment method',
            'payment_status' => 'payment status',
            'status' => 'order status',
            'currency' => 'currency',
            ]);
            //dd($odr);
    }

    protected function updateOrder()
    {
        $this->order->update([
            'user_id' => $this->user_id,
            'grand_total' => $this->grand_total,
            'payment_method' => $this->payment_method,
            'payment_status' => $this->payment_status,
            'status' => $this->status,
            'currency' => $this->currency,
            'shipping_amount' => $this->shipping_amount ?? 0,
            'shipping_method' => $this->shipping_method,
            'notes' => $this->notes,
        ]);
    }

    // protected function updateOrderItems()
    // {
    //     foreach ($this->orderItems as $index => $item) {
    //         $orderItem = $this->order->items()->where('product_id', $item['product_id'])->first();
    //         $orderItem->update([
    //             'quantity' => $item['quantity'],
    //             'unit_amount' => $item['unit_amount'],
    //             'total_amount' => $item['total_amount'],
    //         ]);

    //         $this->updateItemAttributes($orderItem, $item['attributes']);
    //     }
    // }


    protected function updateOrderItems()
{
    foreach ($this->orderItems as $index => $item) {
        // Check if an OrderItem already exists for the given product
        $orderItem = $this->order->items()->where('product_id', $item['product_id'])->first();

        if ($orderItem) {
            // If the OrderItem exists, update it
            $orderItem->update([
                'quantity' => $item['quantity'],
                'unit_amount' => $item['unit_amount'],
                'total_amount' => $item['total_amount'],
            ]);

        } else {
            // If the OrderItem does not exist, create a new one
            $orderItem = new OrderItem([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_amount' => $item['unit_amount'],
                'total_amount' => $item['total_amount'],
                'order_id' => $this->order->id,  // Ensure the order_id is set
            ]);
            $orderItem->save();
        }

        // Update or create the item attributes for the existing or new OrderItem
        $this->updateItemAttributes($orderItem, $item['attributes']);
    }
}


    protected function updateItemAttributes($orderItem, $attributes)
    {
        $orderItem->itemAttributes()->delete();  // Remove old attributes

        foreach ($attributes as $attributeNameId => $attributeValueId) {
            $orderItem->itemAttributes()->create([
                'attribute_name_id' => $attributeNameId,
                'attribute_value_id' => $attributeValueId,
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.order-edit');
    }
}
