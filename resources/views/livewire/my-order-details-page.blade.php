<div>
    <div class="container py-5">
        <h1 class="text-primary mb-4">Order Details</h1>

        <!-- Summary Cards -->
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 mb-4">
            <!-- Customer Card -->
            <div class="col">
                <div class="card h-100 border-warning">
                    <div class="card-body">
                        <h6 class="card-title text-uppercase text-muted">Customer</h6>
                        <p class="card-text">{{ $address->full_name }}</p>
                    </div>
                </div>
            </div>
            <!-- Order Date Card -->
            <div class="col">
                <div class="card h-100 border-info">
                    <div class="card-body">
                        <h6 class="card-title text-uppercase text-muted">Order Date</h6>
                        <p class="card-text">{{ $order->created_at->format('d-m-Y') }}</p>
                    </div>
                </div>
            </div>
            <!-- Order Status Card -->
            <div class="col">
                <div class="card h-100 border-warning">
                    <div class="card-body">
                        <h6 class="card-title text-uppercase text-muted">Order Status</h6>
                        <p class="card-text">
                            @php
                                $status_badges = [
                                    'new' => 'badge-primary',
                                    'processing' => 'badge-warning',
                                    'shipped' => 'badge-success',
                                    'delivered' => 'badge-dark',
                                    'cancelled' => 'badge-danger',
                                ];
                            @endphp
                            <span class="badge {{ $status_badges[$order->status] ?? 'badge-secondary' }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>
            <!-- Payment Status Card -->
            <div class="col">
                <div class="card h-100 border-info">
                    <div class="card-body">
                        <h6 class="card-title text-uppercase text-muted">Payment Status</h6>
                        <p class="card-text">
                            @php
                                $payment_badges = [
                                    'pending' => 'badge-primary',
                                    'paid' => 'badge-success',
                                    'failed' => 'badge-danger',
                                ];
                            @endphp
                            <span class="badge {{ $payment_badges[$order->payment_status] ?? 'badge-secondary' }}">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title">Order Items</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped table-sm">
                        <thead class="table-secondary text-center">
                            <tr>
                                <th scope="col">Product</th>
                                <th scope="col" class="text-end">Price</th>
                                <th scope="col" class="text-end">Quantity</th>
                                <th scope="col" class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order_items as $item)
                                <tr>
                                    <!-- Product Column -->
                                    <td class="d-flex align-items-center">
                                        <img src="{{ url('storage', $item->product->thumb_image) }}" alt="{{ $item->product->name }}" class="img-thumbnail me-4" style="width: 75px; height: 75px;">
                                        <div>
                                            <strong>{{ $item->product->name }}</strong>
                                            <div class="mt-2">
                                                @php
                                                    $raw_attributes = json_decode($item->attributes, true);
                                                    $attributes = is_string($raw_attributes) ? json_decode($raw_attributes, true) : $raw_attributes;
                                                @endphp
                                                @if(is_array($attributes) && count($attributes) > 0)
                                                    @foreach($attributes as $attribute)
                                                        @php
                                                            $attribute_name = \App\Models\AttributeName::find($attribute['attribute_id']);
                                                            $attribute_value = \App\Models\AttributeValue::find($attribute['attribute_value_id']);
                                                        @endphp
                                                        @if($attribute_name && $attribute_value)
                                                            <span class="badge bg-primary me-1 mb-1 d-inline-block">
                                                                {{ $attribute_name->name }}: {{ $attribute_value->value }}
                                                            </span>
                                                        @else
                                                            <span class="badge bg-danger mb-1 d-inline-block">Attribute not found</span>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    <span class="badge bg-warning">No attributes available</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <!-- Unit Price Column -->
                                    <td class="text-end">{{ Number::currency($item->unit_amount, 'INR') }}</td>
                                    <!-- Quantity Column -->
                                    <td class="text-end">{{ $item->quantity }}</td>
                                    <!-- Total Price Column -->
                                    <td class="text-end">{{ Number::currency($item->total_amount, 'INR') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

        <!-- Shipping Address and Summary -->
        <div class="row g-4">
            <!-- Shipping Address -->
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Shipping Address</h5>
                        <p>{{ $address->street_address }}, {{ $address->city }}, {{ $address->state }}, {{ $address->zip_code }}</p>
                        <p><strong>Phone:</strong> {{ $address->phone }}</p>
                    </div>
                </div>
            </div>
            <!-- Summary -->
            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Order Summary</h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Subtotal</span>
                                <span>{{ Number::currency($order->grand_total, 'INR') }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Taxes</span>
                                <span>{{ Number::currency(0, 'INR') }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Shipping</span>
                                <span>{{ Number::currency(0, 'INR') }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between fw-bold">
                                <span>Grand Total</span>
                                <span>{{ Number::currency($order->grand_total, 'INR') }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
