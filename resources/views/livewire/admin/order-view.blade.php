<div>
    <div class="content-wrapper" style="margin-left: 5% !important;">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="">Order Details</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <a wire:navigate href="{{ route('admin.orders') }}" class="btn btn-primary rounded-pill">
                                <i class="fas fa-arrow-left"></i> Back to Orders
                            </a>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5><i class="icon fas fa-exclamation-triangle"></i> Error!</h5>
                        {{ session('error') }}
                    </div>
                @endif

                @if($order)
                    <div class="card shadow-sm">
                        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                            <h3 class="card-title">Order #{{ $order->id }}</h3>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <!-- Order Details -->
                                <div class="col-md-6">
                                    <table class="table table-striped">
                                        <tr>
                                            <th>Customer Name</th>
                                            <td>{{ $order->user->name ?? 'Guest' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Email</th>
                                            <td>{{ $order->user->email ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Order Date</th>
                                            <td>{{ $order->created_at->format('d M, Y H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Payment Method</th>
                                            <td>{{ $order->payment_method }}</td>
                                        </tr>
                                        <tr>
                                            <th>Payment Status</th>
                                            <td>{{ ucfirst($order->payment_status) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Order Status</th>
                                            <td>{{ ucfirst($order->status) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Grand Total</th>
                                            <td>{{ Number::currency($order->grand_total, 'INR') }}</td>
                                        </tr>
                                    </table>
                                </div>

                                <!-- Shipping Address -->
                                <div class="col-md-6">
                                    <h4>Shipping Address</h4>
                                    <p>
                                        {{ $order->shipping_address ?? 'N/A' }}<br>
                                        {{ $order->shipping_city ?? '' }}, {{ $order->shipping_state ?? '' }}<br>
                                        {{ $order->shipping_postal_code ?? '' }}<br>
                                        {{ $order->shipping_country ?? '' }}
                                    </p>
                                </div>
                            </div>

                            <!-- Order Items -->
                            <div class="mt-4">
                                <h4>Order Items</h4>
                                <table class="table table-bordered">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Product</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Total</th>
                                            <th>Attributes</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($order->items as $key => $item)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $item->product->name ?? 'N/A' }}</td>
                                                <td>{{ Number::currency($item->unit_amount, 'INR') }}</td>
                                                <td>{{ $item->quantity }}</td>
                                                <td>{{ Number::currency($item->total_amount, 'INR') }}</td>
                                                {{-- <td>
                                                    @if($item->itemAttributes)
                                                        @foreach($item->itemAttributes as $attribute)
                                                            <span class="badge badge-info">
                                                                {{ $attribute->attributeName->name }}: {{ $attribute->attributeValue->value }}
                                                            </span>
                                                        @endforeach
                                                    @else
                                                        N/A
                                                    @endif
                                                </td> --}}
                                                {{-- <td>
                                                    @if($item->itemAttributes)
                                                        @foreach($item->itemAttributes as $index => $attribute)
                                                            <span class="badge {{ $loop->index % 2 === 0 ? 'badge-info' : 'badge-warning' }}">
                                                                {{ $attribute->attributeName->name }}: {{ $attribute->attributeValue->value }}
                                                            </span>
                                                        @endforeach
                                                    @else
                                                        N/A
                                                    @endif
                                                </td> --}}
                                                <td>
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
                                                </td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Notes or Comments -->
                            <div class="mt-4">
                                <h4>Additional Notes</h4>
                                <p>{{ $order->notes ?? 'No additional notes provided' }}</p>
                            </div>
                        </div>

                        <!-- Card Footer with Action Buttons -->
                        <div class="card-footer">
                            <a wire:navigate href="{{ route('admin.order-edit', $order->id) }}" class="btn btn-warning rounded-pill">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a wire:click="confirmDelete({{ $order->id }})" class="btn btn-danger rounded-pill" title="Delete" data-toggle="modal" data-target="#deleteModal">
                                <i class="fas fa-trash"></i> Delete
                            </a>
                            <a wire:navigate href="{{ route('admin.orders') }}" class="btn btn-secondary rounded-pill">
                                <i class="fas fa-arrow-left"></i> Back to List
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </section>
    </div>

    <!-- Delete Confirmation Modal -->
    <div wire:ignore.self class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this order?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" wire:click="deleteOrder" data-dismiss="modal">Yes, Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>
