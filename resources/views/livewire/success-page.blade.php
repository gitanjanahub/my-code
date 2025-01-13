<div>
    <div class="container py-5">
        <section class="row justify-content-center">
            <div class="col-lg-10 bg-white border rounded p-4">
                <h1 class="mb-4 text-center text-primary">Thank you. Your order has been received.</h1>

                <div class="mb-4 pb-3 border-bottom">
                    <h5 class="text-muted">Shipping Information</h5>
                    <p class="mb-1"><strong>{{ $order->address->full_name }}</strong></p>
                    <p class="mb-1">{{ $order->address->street_address }}</p>
                    <p class="mb-1">{{ $order->address->city }}, {{ $order->address->state }}, {{ $order->address->zip_code }}</p>
                    <p class="mb-0">Phone: {{ $order->address->phone }}</p>
                </div>

                <div class="row g-4">
                    <div class="col-md-3">
                        <h6 class="text-muted">Order Number</h6>
                        <p class="fw-bold">{{ $order->id }}</p>
                    </div>
                    <div class="col-md-3">
                        <h6 class="text-muted">Date</h6>
                        <p class="fw-bold">{{ $order->created_at->format('d-m-Y') }}</p>
                    </div>
                    <div class="col-md-3">
                        <h6 class="text-muted">Total</h6>
                        <p class="fw-bold text-primary">{{ Number::currency($order->grand_total, 'INR') }}</p>
                    </div>
                    <div class="col-md-3">
                        <h6 class="text-muted">Payment Method</h6>
                        <p class="fw-bold">{{ $order->payment_method == 'cod' ? 'Cash on Delivery' : 'Card' }}</p>
                    </div>
                </div>

                <div class="my-4 pb-3 border-bottom">
                    <h5 class="text-muted">Order Details</h5>
                    <div class="d-flex justify-content-between">
                        <p>Subtotal</p>
                        <p>{{ Number::currency($order->grand_total, 'INR') }}</p>
                    </div>
                    <div class="d-flex justify-content-between">
                        <p>Discount</p>
                        <p>{{ Number::currency(0, 'INR') }}</p>
                    </div>
                    <div class="d-flex justify-content-between">
                        <p>Shipping</p>
                        <p>{{ Number::currency(0, 'INR') }}</p>
                    </div>
                    <div class="d-flex justify-content-between fw-bold">
                        <p>Total</p>
                        <p>{{ Number::currency($order->grand_total, 'INR') }}</p>
                    </div>
                </div>

                <div class="my-4">
                    <h5 class="text-muted">Shipping</h5>
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="text-primary" viewBox="0 0 16 16">
                                <path d="M0 3.5A1.5 1.5 0 0 1 1.5 2h9A1.5 1.5 0 0 1 12 3.5V5h1.02a1.5 1.5 0 0 1 1.17.563l1.481 1.85a1.5 1.5 0 0 1 .329.938V10.5a1.5 1.5 0 0 1-1.5 1.5H14a2 2 0 1 1-4 0H5a2 2 0 1 1-3.998-.085A1.5 1.5 0 0 1 0 10.5v-7zm1.294 7.456A1.999 1.999 0 0 1 4.732 11h5.536a2.01 2.01 0 0 1 .732-.732V3.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5v7a.5.5 0 0 0 .294.456zM12 10a2 2 0 0 1 1.732 1h.768a.5.5 0 0 0 .5-.5V8.35a.5.5 0 0 0-.11-.312l-1.48-1.85A.5.5 0 0 0 13.02 6H12v4zm-9 1a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm9 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="mb-0 fw-bold">Delivery</p>
                            <p class="mb-0 text-muted">Delivery within 24 Hours</p>
                        </div>
                        <p class="ms-auto fw-bold">{{ Number::currency(0, 'INR') }}</p>
                    </div>
                </div>

                <div class="d-flex justify-content-start gap-3">
                    <a wire:navigate href="/products" class="btn btn-outline-primary">Go back shopping</a>
                    <a wire:navigate href="/my-orders" class="btn btn-primary">View My Orders</a>
                </div>
            </div>
        </section>
    </div>
</div>
