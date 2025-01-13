<?php

namespace App\Helpers;

use App\Models\AttributeName;
use App\Models\AttributeValue;
use App\Models\Product;
use Illuminate\Support\Facades\Cookie;

class CartManagement
{
    // Add item to cart with quantity and attributes
    static public function addItemToCartWithQty($product_id, $selectedAttributes, $qty = 1)
    {
        $cart_items = self::getCartItemsFromCookie();

        // Generate a unique identifier for the product and its attributes
        $attributeHash = self::generateAttributeHash($selectedAttributes);

        // Check if the item already exists in the cart
        $existing_item = null;
        foreach ($cart_items as $key => $item) {
            if ($item['product_id'] == $product_id && $item['attribute_hash'] == $attributeHash) {
                $existing_item = $key;
                break;
            }
        }

        if ($existing_item !== null) {
            // Update quantity and total amount if product+attribute combination exists
            $cart_items[$existing_item]['quantity'] += $qty;
            $cart_items[$existing_item]['total_amount'] = $cart_items[$existing_item]['quantity'] * $cart_items[$existing_item]['unit_amount'];
        } else {
            // Add new item to the cart
            $product = Product::where('id', $product_id)->first(['id', 'name', 'price','offer_price', 'thumb_image']);

            if ($product) {

                if($product->offer_price > 0){
                   $price = $product->offer_price;
                }else{
                    $price = $product->price;
                }

                $cart_items[] = [
                    'product_id' => $product_id,
                    'name' => $product->name,
                    'image' => $product->thumb_image ?? null,
                    'quantity' => $qty,
                    'unit_amount' => $price,
                    'total_amount' => $qty * $price,
                    'attributes' => $selectedAttributes, // Store attributes
                    'attribute_hash' => $attributeHash,  // Unique identifier for product+attributes
                ];
            }
        }

        //dd($cart_items);

        self::addCartItemsToCookie($cart_items);
        return count($cart_items);
    }

    // Generate a hash for the selected attributes to identify unique product+attribute combinations
    static public function generateAttributeHash($attributes)
    {
        ksort($attributes); // Sort attributes to maintain consistency
        return md5(json_encode($attributes));
    }

    // Add cart items to cookie
    static public function addCartItemsToCookie($cart_items)
    {
        Cookie::queue('cart_items', json_encode($cart_items), 60 * 24 * 30);
    }


    // Resolve attribute IDs to their names and values
    static public function resolveAttributes($attributes)
    {
        $resolvedAttributes = [];
        foreach ($attributes as $attributeId => $valueId) {
            $name = AttributeName::find($attributeId)?->name; // Attribute Name
            $value = AttributeValue::find($valueId)?->value;  // Attribute Value
            if ($name && $value) {
                $resolvedAttributes[] = "{$name}: {$value}";
            }
        }
        return implode(', ', $resolvedAttributes); // Format as a string
    }


    // Get all cart items from cookie
    static public function getCartItemsFromCookie()
    {
        $cart_items = json_decode(Cookie::get('cart_items'), true) ?? [];

        foreach ($cart_items as &$item) {
            if (isset($item['attributes'])) {
                $item['formatted_attributes'] = self::resolveAttributes($item['attributes']);
            }
        }

        return $cart_items;
    }


    // static public function getCartItemsFromCookie()
    // {
    //     $cart_items = json_decode(Cookie::get('cart_items'), true);

    //     if (!$cart_items) {
    //         $cart_items = [];
    //     }
    //     return $cart_items;
    // }

    // Remove item from cart
    static public function removeCartItem($product_id, $selectedAttributes)
    {
        $cart_items = self::getCartItemsFromCookie();
        $attributeHash = self::generateAttributeHash($selectedAttributes);

        foreach ($cart_items as $key => $item) {
            if ($item['product_id'] == $product_id && $item['attribute_hash'] == $attributeHash) {
                unset($cart_items[$key]);
            }
        }

        self::addCartItemsToCookie($cart_items);
        return $cart_items;
    }

    // Increment item quantity
    static public function incrementQuantityToCartItem($product_id, $selectedAttributes)
    {
        return self::updateItemQuantity($product_id, $selectedAttributes, 1);
    }

    // Decrement item quantity
    static public function decrementQuantityToCartItem($product_id, $selectedAttributes)
    {
        return self::updateItemQuantity($product_id, $selectedAttributes, -1);
    }

    // Update item quantity
    static private function updateItemQuantity($product_id, $selectedAttributes, $delta)
    {
        $cart_items = self::getCartItemsFromCookie();
        $attributeHash = self::generateAttributeHash($selectedAttributes);

        foreach ($cart_items as $key => $item) {
            if ($item['product_id'] == $product_id && $item['attribute_hash'] == $attributeHash) {
                $cart_items[$key]['quantity'] += $delta;
                if ($cart_items[$key]['quantity'] < 1) {
                    unset($cart_items[$key]);
                } else {
                    $cart_items[$key]['total_amount'] = $cart_items[$key]['quantity'] * $cart_items[$key]['unit_amount'];
                }
                break;
            }
        }

        self::addCartItemsToCookie($cart_items);
        return $cart_items;
    }

    // Calculate grand total
    static public function calculateGrandTotal($items)
    {
        return array_sum(array_column($items, 'total_amount'));
    }

    // Clear cart items from cookie
    static public function clearCartItems()
    {
        Cookie::queue(Cookie::forget('cart_items'));
    }
}
