<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Mail\OrderPlaced;
use App\Models\Address;
use App\Models\Order;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Title;
use Livewire\Component;
use Stripe\Checkout\Session;
use Stripe\Stripe;

#[Title('Checkout Page')]

class CheckoutPage extends Component
{

    public $first_name;
    public $last_name;
    public $email;
    public $phone;
    public $street_address;
    public $city;
    public $state;
    public $zip_code;
    public $country;
    public $payment_method;

    public function mount(){//for didn't access checkout page directly

        $cart_items = CartManagement::getCartItemsFromCookie();
        //dd($cart_items);
        if(count($cart_items) == 0){
            return redirect('/products');
        }

    }

    // public function placeOrder(){
    //   //dd('testing');
    //   $this->validate([
    //     'first_name'=>'required',
    //     'last_name'=>'required',
    //     'email' => 'email|max:255',
    //     'phone'=>'required',
    //     'street_address'=>'required',
    //     'city'=>'required',
    //     'state'=>'required',
    //     'zip_code'=>'required',
    //     'country'=>'required',
    //     'payment_method'=>'required'
    //   ]);



    //   $cart_items = CartManagement::getCartItemsFromCookie();

    //   dd($cart_items);

    //   $line_items = [];

    //   foreach($cart_items as $item){
    //         $line_items[] = [
    //             'price_data' => [
    //                 'currency' => 'inr',
    //                 'unit_amount' => $item['unit_amount']*100,
    //                 'product_data' => [
    //                     'name' => $item['name']
    //                 ]
    //             ],
    //             'quantity' => $item['quantity']
    //         ];
    //     }

    //     $order = new Order();
    //     $order->user_id = auth()->user()->id;
    //     $order->grand_total = CartManagement::calculateGrandTotal($cart_items);
    //     $order->payment_method = $this->payment_method;
    //     $order->payment_status = 'pending';
    //     $order->status = 'new';
    //     $order->currency = 'inr';
    //     $order->shipping_amount = 0;
    //     $order->shipping_method = 'none';
    //     $order->notes = 'Order placed by '.auth()->user()->name;

    //     $address = new Address();
    //     $address->first_name = $this->first_name;
    //     $address->last_name = $this->last_name;
    //     $address->email = $this->email;
    //     $address->phone = $this->phone;
    //     $address->street_address = $this->street_address;
    //     $address->city = $this->city;
    //     $address->state = $this->state;
    //     $address->zip_code = $this->zip_code;

    //     $redirect_url = '';

    //     if($this->payment_method == 'stripe'){
    //        Stripe::setApiKey(env('STRIPE_SECRET'));
    //        $sessionCheckout = Session::create([
    //           'payment_method_types' => ['card'],
    //           'customer_email' => auth()->user()->email,
    //           'line_items' => $line_items,
    //           'mode' => 'payment',
    //           'success_url' => route('success').'?session_id={CHECKOUT_SESSION_ID}',//success route name cancel also
    //           'cancel_url' => route('cancel')
    //        ]);

    //        $redirect_url = $sessionCheckout->url;

    //     }else{
    //         $redirect_url = route('success');
    //     }

    //     $order->save();
    //     $address->order_id = $order->id;
    //     $address->save();
    //     //$order->items()->createMany($cart_items);//items defined in order model for relationship

    //     // $order->items()->createMany(collect($cart_items)->map(function ($item) {
    //     //     return [
    //     //         'product_id' => $item['product_id'],
    //     //         'quantity' => $item['quantity'],
    //     //         'unit_amount' => $item['unit_amount'],
    //     //         'total_amount' => $item['total_amount'],
    //     //     ];
    //     // })->toArray());

    //     $order->items()->createMany(
    //         collect($cart_items)->map(function ($item) {
    //             return [
    //                 'product_id' => $item['product_id'],
    //                 'quantity' => $item['quantity'],
    //                 'unit_amount' => $item['unit_amount'],
    //                 'total_amount' => $item['total_amount'],
    //                 'attributes' => json_encode(
    //                     collect($item['attributes'])->map(function ($value, $key) {
    //                         return [
    //                             'attribute_id' => $key, // Assuming $key is the attribute ID
    //                             'attribute_value_id' => $value, // Value corresponds to attribute value ID
    //                         ];
    //                     })->values()->toArray()
    //                 ), // Encode attributes as an array of objects
    //             ];
    //         })->toArray()
    //     );



    //     CartManagement::clearCartItems();

    //     Mail::to(request()->user())->send(new OrderPlaced($order));//orderplaced is a mail class.using mailtrap for test

    //     return redirect($redirect_url);

    // }


    public function placeOrder()
    {
        $this->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'email|max:255',
            'phone' => 'required',
            'street_address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip_code' => 'required',
            'country' => 'required',
            'payment_method' => 'required'
        ]);

        $cart_items = CartManagement::getCartItemsFromCookie();

        $line_items = [];
        foreach ($cart_items as $item) {
            $line_items[] = [
                'price_data' => [
                    'currency' => 'inr',
                    'unit_amount' => $item['unit_amount'] * 100,
                    'product_data' => ['name' => $item['name']]
                ],
                'quantity' => $item['quantity']
            ];
        }

        $order = new Order();
        $order->user_id = auth()->user()->id;
        $order->grand_total = CartManagement::calculateGrandTotal($cart_items);
        $order->payment_method = $this->payment_method;
        $order->payment_status = 'pending';
        $order->status = 'new';
        $order->currency = 'inr';
        $order->shipping_amount = 0;
        $order->shipping_method = 'none';
        $order->notes = 'Order placed by ' . auth()->user()->name;

        $address = new Address();
        $address->first_name = $this->first_name;
        $address->last_name = $this->last_name;
        $address->email = $this->email;
        $address->phone = $this->phone;
        $address->street_address = $this->street_address;
        $address->city = $this->city;
        $address->state = $this->state;
        $address->zip_code = $this->zip_code;

        $redirect_url = '';
        if ($this->payment_method == 'stripe') {
            Stripe::setApiKey(env('STRIPE_SECRET'));
            $sessionCheckout = Session::create([
                'payment_method_types' => ['card'],
                'customer_email' => auth()->user()->email,
                'line_items' => $line_items,
                'mode' => 'payment',
                'success_url' => route('success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('cancel')
            ]);
            $redirect_url = $sessionCheckout->url;
        } else {
            $redirect_url = route('success');
        }

        $order->save();
        $address->order_id = $order->id;
        $address->save();

        $order->items()->createMany(
            collect($cart_items)->map(function ($item) {
                $attributes = collect($item['attributes'])->map(function ($value, $key) {
                    return [
                        'attribute_id' => (int) $key, // Ensure integer IDs
                        'attribute_value_id' => (string) $value, // Ensure string values
                    ];
                })->values()->toArray();

                // Encode the attributes twice to escape quotes
                $attributesJson = json_encode($attributes); // Standard JSON encoding
                $attributesJsonWithEscapedQuotes = json_encode($attributesJson); // Double-encode for escaping quotes

                return [
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_amount' => $item['unit_amount'],
                    'total_amount' => $item['total_amount'],
                    'attributes' => $attributesJsonWithEscapedQuotes, // Escaped attributes JSON
                ];
            })->toArray()
        );

        // Reduce stock quantity in product_variants
        foreach ($cart_items as $item) {
            $attributes = collect($item['attributes'])->map(function ($value, $key) {
                return [
                    'attribute_id' => (int) $key,
                    'attribute_value_id' => (string) $value,
                ];
            })->values()->toArray();

            // Encode the attributes twice to match the format
            $attributesJson = json_encode($attributes);
            $attributesJsonWithEscapedQuotes = json_encode($attributesJson);

            $variant = ProductVariant::where('product_id', $item['product_id'])
                ->where('attributes', $attributesJsonWithEscapedQuotes) // Match with double-encoded JSON
                ->first();

            if ($variant) {
                $variant->decrement('stock_quantity', $item['quantity']);
            }
        }


        CartManagement::clearCartItems();

        Mail::to(request()->user())->send(new OrderPlaced($order));

        return redirect($redirect_url);
    }


    public function render()
    {

        $cart_items = CartManagement::getCartItemsFromCookie();
        $grand_total = CartManagement::calculateGrandTotal($cart_items);

        return view('livewire.checkout-page',[
            'cart_items' => $cart_items,
            'grand_total' => $grand_total
        ]);
    }
}
