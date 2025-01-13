<?php

namespace App\Livewire\Admin;

use App\Events\OrderCreated;
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

use Illuminate\Support\Facades\Log;

#[Layout('components.layouts.adminpanel')]
#[Title('Create Order')]

class OrderCreate extends Component
{
    public $users;
    public $products;
    public $user_id;
    public $orderItems = [];
    public $payment_method;
    public $payment_status;
    public $status = 'new';
    public $shipping_amount = 0;
    public $notes;
    public $grand_total = 0;
    public $unit_amount = 0;
    public $currency;
    public $shipping_method;

    public $availableAttributes = [];

    public function mount()
    {
        $this->users = User::all();
        //$this->products = Product::all();

         // Fetch distinct product IDs only where stock_quantity is greater than 0
         $distinctProductIds = ProductVariant::select('product_id')
         ->where('stock_quantity', '>', 0)
         ->distinct()
         ->pluck('product_id');

        // Fetch product details for these IDs
        $this->products = Product::whereIn('id', $distinctProductIds)->get();

        $this->orderItems = [['product_id' => null, 'availableAttributes' => [], 'quantity' => 1, 'unit_amount' => 0, 'total_amount' => 0]];

        // Fetch global attributes (like Size, Color, etc.) only for each selected product
        $this->availableAttributes = [];
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

    // public function updatedOrderItems($value, $key)
    // {
    //     if (str_contains($key, 'product_id')) {
    //         $index = explode('.', $key)[1];
    //         $productId = $this->orderItems[$index]['product_id'] ?? null;
    //         dd($value);

    //         if ($productId) {
    //             $this->orderItems[$index]['availableAttributes'] = $this->getProductAttributes($productId);
    //         }
    //     }
    // }

    public function getProductAttributes($productId)
    {
        $attributes = ProductAttributeValue::whereHas('productVariant', function ($query) {
            $query->where('stock_quantity', '>', 0);
        })
            ->where('product_id', $productId)
            ->with(['attribute', 'attributeValue'])
            ->get()
            ->groupBy('attribute.name')
            ->map(function ($group, $attributeName) {
                return [
                    'id' => $group->first()->attribute->id,
                    'name' => $attributeName,
                    'values' => $group->map(function ($item) {
                        return [
                            'id' => $item->attributeValue->id,
                            'value' => $item->attributeValue->value,
                        ];
                    })->unique('id')->values(),
                ];
            });

        return $attributes->values();
    }



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

                            //dd($attributes);

                             // Ensure there are available attributes before assigning
                            $this->orderItems[$index]['availableAttributes'] = $attributes->isEmpty()
                                ? [] // If no attributes, assign empty array
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


    public function updatedShippingAmount()
    {
        // Recalculate grand total when shipping amount is updated
        $this->calculateGrandTotal();
    }



    private function calculateTotalAmount($index)
    {
        // Ensure total amount is updated based on unit amount and quantity
        $unitAmount = $this->orderItems[$index]['unit_amount'];
        $quantity = $this->orderItems[$index]['quantity'];

        $this->orderItems[$index]['total_amount'] = $unitAmount * $quantity;

        // Recalculate the grand total after updating the total amount
        $this->calculateGrandTotal();
    }


    private function calculateGrandTotal()
    {
        // Sum all total amounts from the order items and include shipping amount
        $this->grand_total = array_sum(array_column($this->orderItems, 'total_amount')) + $this->shipping_amount;
    }

    public function removeProduct($index)
    {
        unset($this->orderItems[$index]);
        $this->orderItems = array_values($this->orderItems);
        $this->calculateGrandTotal();
    }


    public function save()
    {
        $this->validateOrder();

        DB::beginTransaction();

        try {
            $order = $this->createOrder();
            event(new OrderCreated($order));
            $this->createOrderItems($order);

            DB::commit();

            session()->flash('message', 'Order created successfully!');
            return redirect()->route('admin.orders');
        } catch (\Exception $e) {
            DB::rollBack();

            session()->flash('error', 'Failed to create order: ' . $e->getMessage());
        }
    }

    protected function validateOrder()
    {
        $this->validate([
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
    }

    protected function createOrder()
    {
        return Order::create([
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


    protected function createOrderItems($order)
    {
        foreach ($this->orderItems as $item) {
            // Prepare attributes as JSON
            //$attributes = isset($item['attributes']) ? json_encode($item['attributes']) : null;

            $attributes = collect($item['attributes'])->map(function ($value, $key) {
                return [
                    'attribute_id' => (int) $key,
                    'attribute_value_id' => (string) $value,
                ];
            })->values()->toArray();

            // Encode the attributes twice to match the format
            $attributesJson = json_encode($attributes);
            $attributesJsonWithEscapedQuotes = json_encode($attributesJson);

            // Create order item
            $orderItem = $order->items()->create([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_amount' => $item['unit_amount'],
                'total_amount' => $item['total_amount'],
                'attributes' => $attributesJsonWithEscapedQuotes,
            ]);

            // Decrement stock for the related product variant
            ///$this->decrementStock($item['product_id'], $item['attributes'], $item['quantity']);

            $variant = ProductVariant::where('product_id', $item['product_id'])
                    ->where('attributes', $attributesJsonWithEscapedQuotes) // Match with double-encoded JSON
                    ->first();

                if ($variant) {
                    $variant->decrement('stock_quantity', $item['quantity']);
                }
        }
    }

protected function decrementStock($productId, $attributes, $quantity)
{
    // Ensure attributes are in array format
    $attributeArray = is_string($attributes) ? json_decode($attributes, true) : $attributes;

    // Find the matching product variant
    $variant = ProductVariant::where('product_id', $productId)
        ->whereJsonContains('attributes', $attributeArray)
        ->first();

    if ($variant) {
        // Decrement stock
        $variant->decrement('stock_quantity', $quantity);
    }
}


    // protected function createOrderItems($order)
    // {
    //     foreach ($this->orderItems as $item) {
    //         $orderItem = $order->items()->create([
    //             'product_id' => $item['product_id'],
    //             'quantity' => $item['quantity'],
    //             'unit_amount' => $item['unit_amount'],
    //             'total_amount' => $item['total_amount'],
    //         ]);

    //         $this->attachItemAttributes($orderItem, $item['attributes'] ?? []);
    //     }
    // }

    protected function attachItemAttributes($orderItem, $attributes)
    {
        foreach ($attributes as $attributeNameId => $attributeValueId) {
            $orderItem->itemAttributes()->create([
                'attribute_name_id' => $attributeNameId,
                'attribute_value_id' => $attributeValueId,
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.order-create');
    }
}
