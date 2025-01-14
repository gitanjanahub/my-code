<div>
    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-secondary mt-5 pt-5">
        <div class="row px-xl-5 pt-5">
            <div class="col-lg-4 col-md-12 mb-5 pr-3 pr-xl-5">
                <h5 class="text-secondary text-uppercase mb-4">Get In Touch</h5>
                {{-- <p class="mb-4">No dolore ipsum accusam no lorem. Invidunt sed clita kasd clita et et dolor sed dolor. Rebum tempor no vero est magna amet no</p> --}}
                @if ($contactDetail)
                <p class="mb-2"><i class="fa fa-map-marker-alt text-primary mr-3"></i>{{ $contactDetail->address }}</p>
                <p class="mb-2"><i class="fa fa-envelope text-primary mr-3"></i>{{ $contactDetail->email }}</p>
                <p class="mb-0"><i class="fa fa-phone-alt text-primary mr-3"></i>{{ $contactDetail->phone }}</p>
                @endif
            </div>
            <div class="col-lg-8 col-md-12">
                <div class="row">
                    <div class="col-md-4 mb-5">
                        <h5 class="text-secondary text-uppercase mb-4">Quick Shop</h5>
                        <div class="d-flex flex-column justify-content-start">
                            <a wire:navigate class="text-secondary mb-2" href="/"><i class="fa fa-angle-right mr-2"></i>Home</a>
                            <a wire:navigate class="text-secondary mb-2" href="/categories"><i class="fa fa-angle-right mr-2"></i>Categories</a>
                            <a wire:navigate class="text-secondary mb-2" href="/products"><i class="fa fa-angle-right mr-2"></i>Products</a>
                            <a wire:navigate class="text-secondary mb-2" href="/cart"><i class="fa fa-angle-right mr-2"></i>Cart</a>
                            <a wire:navigate class="text-secondary" href="#"><i class="fa fa-angle-right mr-2"></i>Contact Us</a>
                        </div>
                    </div>
                    <div class="col-md-4 mb-5">
                        <h5 class="text-secondary text-uppercase mb-4">My Account</h5>
                        <div class="d-flex flex-column justify-content-start">
                            <a wire:navigate class="text-secondary mb-2" href="/"><i class="fa fa-angle-right mr-2"></i>Home</a>
                            @auth
                            <a wire:navigate class="text-secondary mb-2" href="/my-orders"><i class="fa fa-angle-right mr-2"></i>My Orders</a>
                            @endauth
                            @guest
                                <a wire:navigate href="/login" class="text-secondary mb-2"><i class="fa fa-angle-right mr-2"></i>Log In</a>
                                <a wire:navigate href="/register" class="text-secondary mb-2"><i class="fa fa-angle-right mr-2"></i>Sign Up</a>
                            @endguest
                        </div>
                    </div>
                    {{-- <div class="col-md-4 mb-5">
                        <h5 class="text-secondary text-uppercase mb-4">Newsletter</h5>
                        <p>Duo stet tempor ipsum sit amet magna ipsum tempor est</p>
                        <form action="">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Your Email Address">
                                <div class="input-group-append">
                                    <button class="btn btn-primary">Sign Up</button>
                                </div>
                            </div>
                        </form>
                        <h6 class="text-secondary text-uppercase mt-4 mb-3">Follow Us</h6>
                        <div class="d-flex">
                            <a class="btn btn-primary btn-square mr-2" href="#"><i class="fab fa-twitter"></i></a>
                            <a class="btn btn-primary btn-square mr-2" href="#"><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-primary btn-square mr-2" href="#"><i class="fab fa-linkedin-in"></i></a>
                            <a class="btn btn-primary btn-square" href="#"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
        <div class="row border-top mx-xl-5 py-4" style="border-color: rgba(256, 256, 256, .1) !important;">
            <div class="col-md-6 px-xl-0">
                <p class="mb-md-0 text-center text-md-left text-secondary">
                    &copy; <a class="text-primary" href="#">Domain</a>. All Rights Reserved. Designed
                    by
                    <a class="text-primary" href="">bla bla</a>
                </p>
            </div>
            <div class="col-md-6 px-xl-0 text-center text-md-right">
                {{-- <img class="img-fluid" src="img/payments.png" alt=""> --}}
            </div>
        </div>
    </div>
    <!-- Footer End -->
</div>
