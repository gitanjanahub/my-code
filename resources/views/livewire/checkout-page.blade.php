<div>
    <!-- Checkout Start -->
    <div class="container-fluid">
        <form wire:submit.prevent="placeOrder">
            <div class="row px-xl-5">
                <div class="col-lg-8">
                    <h5 class="section-title position-relative text-uppercase mb-3"><span class="bg-secondary pr-3">Billing Address</span></h5>
                    <div class="bg-light p-30 mb-5">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>First Name</label>
                                <input class="form-control" type="text" placeholder="John" wire:model="first_name">

                                @error('first_name')
                                <div style="color: red; font-size: 0.875rem; font-weight: bold;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 form-group">
                                <label>Last Name</label>
                                <input class="form-control" type="text" placeholder="Doe" wire:model="last_name">

                                @error('last_name')
                                <div style="color: red; font-size: 0.875rem; font-weight: bold;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 form-group">
                                <label>E-mail</label>
                                <input class="form-control" type="email" placeholder="example@email.com" wire:model="email">

                                @error('email')
                                <div style="color: red; font-size: 0.875rem; font-weight: bold;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 form-group">
                                <label>Mobile No</label>
                                <input class="form-control" type="text" placeholder="+123 456 789" wire:model="phone">

                                @error('phone')
                                <div style="color: red; font-size: 0.875rem; font-weight: bold;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 form-group">
                                <label>Street Address</label>
                                <textarea class="form-control" type="text" placeholder="123 Street" wire:model="street_address">Address</textarea>

                                @error('street_address')
                                <div style="color: red; font-size: 0.875rem; font-weight: bold;">{{ $message }}</div>
                                @enderror
                            </div>
                            {{-- <div class="col-md-6 form-group">
                                <label>Address Line 2</label>
                                <input class="form-control" type="text" placeholder="123 Street">
                            </div> --}}
                            <div class="col-md-6 form-group">
                                <label>Country</label>
                                <select class="custom-select" wire:model="country">
                                    <option value="" >Select</option>
                                    <option value="India">India</option>
                                    <option value="UAE">UAE</option>
                                    <option value="UK">UK</option>
                                </select>

                                @error('country')
                                <div style="color: red; font-size: 0.875rem; font-weight: bold;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 form-group">
                                <label>State</label>
                                <input class="form-control" type="text" placeholder="Kerala" wire:model="state">

                                @error('state')
                                <div style="color: red; font-size: 0.875rem; font-weight: bold;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 form-group">
                                <label>City</label>
                                <input class="form-control" type="text" placeholder="Kozhikode" wire:model="city">

                                @error('city')
                                <div style="color: red; font-size: 0.875rem; font-weight: bold;">{{ $message }}</div>
                                @enderror
                            </div>


                            <div class="col-md-6 form-group">
                                <label>ZIP Code</label>
                                <input class="form-control" type="text" placeholder="123" wire:model="zip_code">

                                @error('zip_code')
                                <div style="color: red; font-size: 0.875rem; font-weight: bold;">{{ $message }}</div>
                                @enderror
                            </div>
                            {{-- <div class="col-md-12 form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="newaccount">
                                    <label class="custom-control-label" for="newaccount">Create an account</label>
                                </div>
                            </div> --}}
                            {{-- <div class="col-md-12">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="shipto">
                                    <label class="custom-control-label" for="shipto"  data-toggle="collapse" data-target="#shipping-address">Ship to different address</label>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                    {{-- <div class="collapse mb-5" id="shipping-address">
                        <h5 class="section-title position-relative text-uppercase mb-3"><span class="bg-secondary pr-3">Shipping Address</span></h5>
                        <div class="bg-light p-30">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>First Name</label>
                                    <input class="form-control" type="text" placeholder="John">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Last Name</label>
                                    <input class="form-control" type="text" placeholder="Doe">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>E-mail</label>
                                    <input class="form-control" type="text" placeholder="example@email.com">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Mobile No</label>
                                    <input class="form-control" type="text" placeholder="+123 456 789">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Address Line 1</label>
                                    <input class="form-control" type="text" placeholder="123 Street">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Address Line 2</label>
                                    <input class="form-control" type="text" placeholder="123 Street">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Country</label>
                                    <select class="custom-select">
                                        <option selected>United States</option>
                                        <option>Afghanistan</option>
                                        <option>Albania</option>
                                        <option>Algeria</option>
                                    </select>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>City</label>
                                    <input class="form-control" type="text" placeholder="New York">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>State</label>
                                    <input class="form-control" type="text" placeholder="New York">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>ZIP Code</label>
                                    <input class="form-control" type="text" placeholder="123">
                                </div>
                            </div>
                        </div>
                    </div> --}}
                </div>
                <div class="col-lg-4">
                    <h5 class="section-title position-relative text-uppercase mb-3"><span class="bg-secondary pr-3">Order Total</span></h5>
                    <div class="bg-light p-30 mb-5">
                        <div class="border-bottom">
                            <h6 class="mb-3">Products</h6>
                            @foreach ($cart_items as $item)
                                <div class="d-flex justify-content-between">
                                    <p>
                                        {{ $item['name'] }}
                                        <span style="font-size: 0.9em;">
                                            <strong>(</strong>x {{ $item['quantity'] }}<strong>)</strong>
                                        </span>
                                    </p>


                                    <p>{{ Number::currency($item['total_amount'],'INR') }}</p>
                                </div>
                            @endforeach
                        </div>
                        <div class="border-bottom pt-3 pb-2">
                            <div class="d-flex justify-content-between mb-3">
                                <h6>Subtotal</h6>
                                <h6>{{ Number::currency($grand_total,'INR') }}</h6>
                            </div>
                            <div class="d-flex justify-content-between">
                                <h6 class="font-weight-medium">Shipping</h6>
                                <h6 class="font-weight-medium">{{ Number::currency(0,'INR') }}</h6>
                            </div>
                        </div>
                        <div class="pt-2">
                            <div class="d-flex justify-content-between mt-2">
                                <h5>Total</h5>
                                <h5>{{ Number::currency($grand_total,'INR') }}</h5>
                            </div>
                        </div>
                    </div>
                    <div class="mb-5">
                        <h5 class="section-title position-relative text-uppercase mb-3"><span class="bg-secondary pr-3">Payment</span></h5>
                        <div class="bg-light p-30">
                            <div class="form-group">
                                <div class="custom-control custom-radio">
                                    <input wire:model="payment_method" type="radio" class="custom-control-input"  id="cod" value="cod">
                                    <label class="custom-control-label" for="cod">Cash on Delivery</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="custom-control custom-radio">
                                    <input wire:model="payment_method" type="radio" class="custom-control-input" id="stripe" value="stripe">
                                    <label class="custom-control-label" for="stripe">Stripe</label>
                                </div>
                            </div>

                            @error('payment_method')
                            <div style="color: red; font-size: 0.875rem; font-weight: bold;">{{ $message }}</div>
                            @enderror

                            {{-- <div class="form-group mb-4">
                                <div class="custom-control custom-radio">
                                    <input wire:model="payment_method" type="radio" class="custom-control-input" name="payment" id="banktransfer">
                                    <label class="custom-control-label" for="banktransfer">Bank Transfer</label>
                                </div>
                            </div> --}}
                            <button type="submit" class="btn btn-block btn-primary font-weight-bold py-3">
                                <span wire:loading.remove>Place Order</span>
                                <span wire:loading>Processing...</span>
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </form>
    </div>
    <!-- Checkout End -->
</div>
