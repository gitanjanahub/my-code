<div>
    <div class="content-wrapper" style="margin-left: 5% !important;">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Edit Order</h1>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a wire:navigate href="{{ route('admin.orders') }}" class="btn btn-outline-primary rounded-pill">
                            Back to Orders
                        </a>
                        <a wire:navigate href="{{ route('admin.order-create') }}" class="btn btn-outline-success rounded-pill">
                            Create New
                        </a>
                    </div>
                </div>
            </div>

            @if (session('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert" wire:poll.3s>
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-check"></i> Alert!</h5>
                    {{ session('message') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert" wire:poll.8s>
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-check"></i> Alert!</h5>
                    {{ session('error') }}
                </div>
            @endif
        </section>

        <section class="content">
            <div class="container-fluid">
                <form wire:submit.prevent="save">
                    <div class="card shadow-lg border-0">
                        <!-- Card Header -->
                        <div class="card-header bg-primary text-white rounded-top">
                            <h3 class="card-title">
                                <i class="fas fa-plus-circle"></i> Edit Order
                            </h3>
                        </div>

                        <!-- Card Body -->
                        <div class="card-body">
                            <div class="row">
                                <!-- Customer Selection -->
                                <div class="col-md-12 mb-3">
                                    <label for="user_id">Customer</label>
                                    <select wire:model="user_id" id="user_id" class="form-control rounded-pill">
                                        <option value="">Select a customer</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <hr>

                            <!-- Order Items -->
                            <h4>Order Items</h4>
                            <div class="card mb-3 p-3">
                                <div class="form-row">

                                    <div wire:sortable="updateOrderItems" class="sortable w-100">
                                        @foreach($orderItems as $index => $item)
                                            <div wire:sortable.item="{{ $index }}" class="sortable-item row mb-3">
                                                <!-- Product Selection -->
                                                <div class="col-md-2">
                                                    <label for="orderItems[{{ $index }}][product_id]">Product</label>
                                                    <select wire:model.live="orderItems.{{ $index }}.product_id" class="form-control rounded-pill" id="orderItems[{{ $index }}][product_id]" >
                                                        <option value="">Select a product</option>
                                                        @foreach($products as $product)
                                                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error("orderItems.{$index}.product_id")
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <!-- Product Attributes -->

                                                   <!-- Product Attributes -->
                                                {{-- @if(isset($orderItems[$index]['availableAttributes']) && count($orderItems[$index]['availableAttributes']) > 0) --}}

                                                @foreach($item['availableAttributes'] as $attribute)
            <div class="col-md-2">
                <label>{{ $attribute['name'] }}</label>
                <select wire:model="orderItems.{{ $index }}.attributes.{{ $attribute['id'] }}" class="form-control rounded-pill">
                    <option value="">Select {{ $attribute['name'] }}</option>
                    @foreach($attribute['values'] as $value)
                        <option value="{{ $value['id'] }}"
                            @if(isset($item['attributes'][$attribute['id']]) && $item['attributes'][$attribute['id']] == $value['id'])
                                selected
                            @endif>
                            {{ $value['value'] }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endforeach
                                                {{-- @endif --}}



                                                <!-- Quantity Input -->
                                                <div class="col-md-1">
                                                    <label for="orderItems[{{ $index }}][quantity]">Quantity</label>
                                                    <input wire:model.live="orderItems.{{ $index }}.quantity" type="number" class="form-control rounded-pill" id="orderItems[{{ $index }}][quantity]" min="1" >
                                                    @error("orderItems.{$index}.quantity")
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <!-- Unit Price -->
                                                <div class="col-md-2">
                                                    <label for="orderItems[{{ $index }}][unit_amount]">Unit Price</label>
                                                    <input wire:model="orderItems.{{ $index }}.unit_amount" type="text" class="form-control rounded-pill" id="orderItems[{{ $index }}][unit_amount]" readonly>
                                                    @error("orderItems.{$index}.unit_amount")
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <!-- Total Amount -->
                                                <div class="col-md-2">
                                                    <label for="orderItems[{{ $index }}][total_amount]">Total Amount</label>
                                                    <input wire:model="orderItems.{{ $index }}.total_amount" type="text" class="form-control rounded-pill" id="orderItems[{{ $index }}][total_amount]" readonly>
                                                    @error("orderItems.{$index}.total_amount")
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <!-- Remove Button -->
                                                <div class="col-md-1 text-center">
                                                    <button type="button" wire:click="removeProduct({{ $index }})" class="btn btn-danger btn-sm rounded-circle mt-4">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                        <!-- Add Product Button -->

                                    </div>

                                </div>

                            </div>
                            <div class="text-right mb-4">
                                <button type="button" wire:click="addProduct" class="btn btn-info rounded-pill">
                                    <i class="fas fa-plus"></i> Add Product
                                </button>
                            </div>

                            <!-- Other Order Details -->
                            <div class="row">

                                <div class="col-md-6 mb-3">
                                    <label for="payment_method">Payment Method</label>
                                    <select wire:model="payment_method" class="form-control rounded-pill">
                                        <option value="">-- Select Payment Method --</option>
                                        <option value="COD">COD</option>
                                        <option value="STRIPE">STRIPE</option>
                                    </select>
                                    @error('payment_method')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="payment_status">Payment Status</label>
                                    <select wire:model="payment_status" class="form-control rounded-pill">
                                        <option value="">-- Select Payment Status --</option>
                                        <option value="paid">Paid</option>
                                        <option value="unpaid">Unpaid</option>
                                    </select>
                                    @error('payment_status')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="status">Order Status</label>
                                    <select wire:model="status" id="status" class="form-control rounded-pill">
                                        <option value="new">New</option>
                                        <option value="processing">Processing</option>
                                        <option value="shipped">Shipped</option>
                                        <option value="delivered">Delivered</option>
                                        <option value="cancelled">Cancelled</option>
                                    </select>
                                    @error('status')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="Currency">Currency</label>
                                    <input wire:model="currency" type="text" class="form-control rounded-pill" id="currency" >
                                    @error('currency')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="shipping_amount">Shipping Amount</label>
                                    <input wire:model.live="shipping_amount" type="number" class="form-control rounded-pill" id="shipping_amount" min="0">
                                    @error('shipping_amount')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="shipping_method">Shipping Method</label>
                                    <input wire:model="shipping_method" type="text" class="form-control rounded-pill" id="shipping_method" placeholder="Shipping Method (e.g., INR)">
                                    @error('shipping_method')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="notes">Order Notes</label>
                                    <textarea wire:model="notes" class="form-control rounded-pill" id="notes"></textarea>
                                    @error('notes')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label for="notes">Grand total</label>
                                    <input type="text" wire:model="grand_total" class="form-control rounded-pill" id="grand_total" readonly>
                                    @error('grand_total')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>
                        </div>

                        <!-- Card Footer -->
                        <div class="card-footer text-center">
                            <button type="submit" class="btn btn-primary rounded-pill">
                                Update Order
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </div>
</div>
