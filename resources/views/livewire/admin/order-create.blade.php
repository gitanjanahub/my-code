<div>
    <div class="content-wrapper" style="margin-left: 5% !important;">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-4">
                    <div class="col-sm-6">
                        <h1>Create Order</h1>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a wire:navigate href="{{ route('admin.orders') }}" class="btn btn-outline-primary rounded-pill">
                            <i class="fas fa-arrow-left"></i> Back to Orders
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
                <div class="alert alert-danger alert-dismissible fade show" role="alert" wire:poll.9s>
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
                        <div class="card-header bg-primary text-white rounded-top">
                            <h3 class="card-title"><i class="fas fa-plus-circle"></i> Create New Order</h3>
                        </div>

                        <div class="card-body">

                            <div class="form-group">
                                <label>Select Customer:</label>
                                <select wire:model="user_id" class="form-control rounded-pill">
                                    <option value="">-- Select User --</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <hr>

                            <!-- Order Items -->
                            <h4>Order Items</h4>

                            @foreach($orderItems as $index => $item)
    <div class="card mb-3 p-3">
        <div class="form-row">

            <!-- Product Selection -->
            <div class="col-md-2">
                <label>Product</label>
                <select wire:model.live="orderItems.{{ $index }}.product_id" class="form-control rounded-pill">
                    <option value="">-- Select Product --</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}"
                            @if(in_array($product->id, array_column($orderItems, 'product_id'))) disabled @endif>
                                {{ $product->name }}
                        </option>
                    @endforeach
                </select>
                @error("orderItems.{$index}.product_id")
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <!-- Dynamic Attributes (Only Show Once for Each Product) -->
            @if(!empty($item['availableAttributes']))
                @foreach($item['availableAttributes'] as $attribute)
                    <div class="col-md-2">
                        <label>{{ $attribute['name'] }}</label>
                        <select wire:model="orderItems.{{ $index }}.attributes.{{ $attribute['id'] }}" class="form-control rounded-pill">
                            <option value="">-- Select {{ $attribute['name'] }} --</option>
                            @foreach($attribute['values'] as $value)
                                <option value="{{ $value['id'] }}">{{ $value['value'] }}</option>
                            @endforeach
                        </select>
                    </div>
                @endforeach
            @endif

            <!-- Quantity -->
            <div class="col-md-1">
                <label>Quantity</label>
                <input type="number" wire:model.live="orderItems.{{ $index }}.quantity" min="1" class="form-control rounded-pill">
                @error("orderItems.{$index}.quantity")
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <!-- Unit Amount -->
            <div class="col-md-2 mb-3">
                <label class="font-weight-bold">Unit Amount</label>
                <input type="text" wire:model="orderItems.{{ $index }}.unit_amount" step="0.01" class="form-control rounded-pill" readonly>
                @error("orderItems.{$index}.unit_amount")
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <!-- Total Amount -->
            <div class="col-md-2 mb-3">
                <label class="font-weight-bold">Total Amount</label>
                <input type="number" wire:model="orderItems.{{ $index }}.total_amount" readonly step="0.01" class="form-control rounded-pill">
                @error("orderItems.{$index}.total_amount")
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <!-- Remove Button -->
            <div class="col-md-1 text-center mb-3">
                <button type="button" wire:click="removeProduct({{ $index }})" class="btn btn-danger btn-sm rounded-circle">
                    <i class="fas fa-trash"></i>
                </button>
            </div>

        </div>
    </div>
@endforeach


                            <!-- Add Product Button -->
                            <div class="text-right mb-4">
                                <button type="button" wire:click="addProduct" class="btn btn-info rounded-pill">
                                    <i class="fas fa-plus"></i> Add Product
                                </button>
                            </div>

                            <div class="row"></div>



                            <div class="form-row">

                                <!-- Payment Method -->
                                <div class="form-group col-md-6">
                                    <label for="payment_method" class="font-weight-bold">Payment Method:</label>
                                    <select wire:model="payment_method" class="form-control rounded-pill">
                                        <option value="">-- Select Payment Method --</option>
                                        <option value="COD">COD</option>
                                        <option value="STRIPE">STRIPE</option>
                                    </select>
                                    @error('payment_method')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Payment Status -->
                                <div class="form-group col-md-6">
                                    <label for="payment_status" class="font-weight-bold">Payment Status:</label>
                                    <select wire:model="payment_status" class="form-control rounded-pill">
                                        <option value="">-- Select Payment Status --</option>
                                        <option value="paid">Paid</option>
                                        <option value="unpaid">Unpaid</option>
                                    </select>
                                    @error('payment_status')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group form-row">

                                <!-- Order Status -->
                                <div class="col-md-6">
                                    <label for="status" class="font-weight-bold">Order Status:</label>
                                    <select wire:model="status" class="form-control rounded-pill">
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

                                <!-- Currency -->
                                <div class="col-md-6">
                                    <label for="currency" class="font-weight-bold">Currency:</label>
                                    <input type="text" wire:model="currency" class="form-control rounded-pill" placeholder="Currency (e.g., INR)">
                                    @error('currency')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>

                            <div class="form-group form-row">
                                <!-- Shipping Amount -->
                                <div class="col-md-6">
                                    <label for="shipping_amount" class="font-weight-bold">Shipping Amount:</label>
                                    <input type="number" wire:model.live="shipping_amount" step="0.01" class="form-control rounded-pill" placeholder="Shipping Amount">
                                    @error('shipping_amount')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Shipping Method -->
                                <div class="col-md-6">
                                    <label for="shipping_method" class="font-weight-bold">Shipping Method:</label>
                                    <input type="text" wire:model="shipping_method" class="form-control rounded-pill" placeholder="Shipping Method ">
                                    @error('shipping_method')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Notes -->
                            <div class="form-group mb-4">
                                <label for="notes" class="font-weight-bold">Order Notes:</label>
                                <textarea wire:model="notes" class="form-control rounded-pill" rows="3" placeholder="Additional information for the order"></textarea>
                                @error('notes')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Grand Total -->
                            <div class="form-group mb-4">
                                <label for="grand_total" class="font-weight-bold">Grand Total</label>
                                <input type="number" wire:model="grand_total" readonly class="form-control rounded-pill">
                                @error('grand_total')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="card-footer text-center">
                                <button type="submit" class="btn btn-success btn-lg rounded-pill">
                                    <i class="fas fa-save"></i> Save Order
                                </button>
                            </div>


                            {{-- <button type="button" wire:click="addProduct" class="btn btn-info mb-3">Add Product</button> --}}

                            {{-- <button type="submit" class="btn btn-success btn-lg rounded-pill">
                                <i class="fas fa-save"></i> Save Order
                            </button> --}}

                        </div>
                    </div>
                </form>
            </div>
        </section>
    </div>
</div>
