<div>
    <div class="container py-5">
        <h1 class="text-center text-primary mb-4">My Orders</h1>
        <div class="card shadow-lg">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col">Order</th>
                                <th scope="col">Date</th>
                                <th scope="col">Order Status</th>
                                <th scope="col">Payment Status</th>
                                <th scope="col">Order Amount</th>
                                <th scope="col" class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)

                            @php
                                $status = '';
                                $payment_status = '';

                                if ($order->status == 'new') {
                                    $status = '<span class="badge rounded-pill bg-info text-dark px-3 py-2">New</span>';
                                }

                                if ($order->status == 'processing') {
                                    $status = '<span class="badge rounded-pill bg-warning text-dark px-3 py-2">Processing</span>';
                                }

                                if ($order->status == 'shipped') {
                                    $status = '<span class="badge rounded-pill bg-success px-3 py-2">Shipped</span>';
                                }

                                if ($order->status == 'delivered') {
                                    $status = '<span class="badge rounded-pill bg-dark px-3 py-2">Delivered</span>';
                                }

                                if ($order->status == 'cancelled') {
                                    $status = '<span class="badge rounded-pill bg-danger px-3 py-2">Cancelled</span>';
                                }

                                if ($order->payment_status == 'pending') {
                                    $payment_status = '<span class="badge rounded-pill bg-primary px-3 py-2">Pending</span>';
                                }

                                if ($order->payment_status == 'paid') {
                                    $payment_status = '<span class="badge rounded-pill bg-success px-3 py-2">Paid</span>';
                                }

                                if ($order->payment_status == 'failed') {
                                    $payment_status = '<span class="badge rounded-pill bg-danger px-3 py-2">Failed</span>';
                                }
                            @endphp

                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->created_at->format('d-m-y') }}</td>
                                <td>{!! $status !!}</td>
                                <td>{!! $payment_status !!}</td>
                                <td>{{ Number::currency($order->grand_total, 'INR') }}</td>
                                <td class="text-end">
                                    <a wire:navigate href="/my-orders/{{ $order->id }}" class="btn btn-warning btn-sm rounded-pill">View Details</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center">
                    {{ $orders->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
