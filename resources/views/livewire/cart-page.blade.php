<div>
    <div class="container-fluid">
        <div class="row px-xl-5">
            <!-- Cart Items Table -->
            <div class="col-lg-8 table-responsive mb-5">
                <table class="table table-light table-borderless table-hover text-center mb-0">
                    <thead class="thead-dark">
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Remove</th>
                        </tr>
                    </thead>
                    <tbody class="align-middle">
                        @forelse($cart_items as $item)
                            <tr>
                                <!-- Product Details -->
                                <td class="align-middle text-left">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ url('storage', $item['image']) }}" alt="{{ $item['name'] }}"
                                            class="mr-3" style="width: 50px; height: 50px; object-fit: cover;">
                                        <div>
                                            <span class="font-weight-bold d-block">{{ $item['name'] }}</span>
                                            @if(!empty($item['formatted_attributes']))
                                                <div>
                                                    @foreach(explode(', ', $item['formatted_attributes']) as $attribute)
                                                        <span class="badge badge-primary text-black px-2 py-1 my-1">
                                                            {{ $attribute }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <!-- Price -->
                                <td class="align-middle">{{ Number::currency($item['unit_amount'], 'INR') }}</td>

                                <!-- Quantity -->
                                <td class="align-middle">
                                    <div class="input-group quantity mx-auto" style="width: 120px;">
                                        <div class="input-group-btn">
                                            <button class="btn btn-sm btn-primary btn-minus"
                                                wire:click="decreaseQty({{ $item['product_id'] }}, {{ json_encode($item['attributes']) }})">
                                                <i class="fa fa-minus"></i>
                                            </button>
                                        </div>
                                        <input type="text" class="form-control form-control-sm bg-secondary border-0 text-center"
                                            value="{{ $item['quantity'] }}" readonly>
                                        <div class="input-group-btn">
                                            <button class="btn btn-sm btn-primary btn-plus"
                                                wire:click="increaseQty({{ $item['product_id'] }}, {{ json_encode($item['attributes']) }})">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </td>

                                <!-- Total -->
                                <td class="align-middle">{{ Number::currency($item['total_amount'], 'INR') }}</td>

                                <!-- Remove -->
                                <td class="align-middle">
                                    <button class="btn btn-sm btn-danger"
                                        wire:click="removeItem({{ $item['product_id'] }}, {{ json_encode($item['attributes']) }})">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-4xl font-semibold text-slate-500">
                                    No items available in cart!
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Cart Summary -->
            <div class="col-lg-4">
                <h5 class="section-title position-relative text-uppercase mb-3">
                    <span class="bg-secondary pr-3">Cart Summary</span>
                </h5>
                <div class="bg-light p-30 mb-5">
                    <div class="border-bottom pb-2">
                        <div class="d-flex justify-content-between mb-3">
                            <h6>Subtotal</h6>
                            <h6>{{ Number::currency($grand_total, 'INR') }}</h6>
                        </div>
                        <div class="d-flex justify-content-between">
                            <h6 class="font-weight-medium">Shipping</h6>
                            <h6 class="font-weight-medium">{{ Number::currency($shipping, 'INR') }}</h6>
                        </div>
                    </div>
                    <div class="pt-2">
                        <div class="d-flex justify-content-between mt-2">
                            <h5>Total</h5>
                            <h5>{{ Number::currency($grand_total + $shipping, 'INR') }}</h5>
                        </div>
                        @if($cart_items)
                            <a href="/checkout" class="btn btn-block btn-primary font-weight-bold my-3 py-3">
                                Proceed To Checkout
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
