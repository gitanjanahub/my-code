<div>
    <!-- Topbar Start -->
    <div class="container-fluid">
        <div class="row bg-secondary py-1 px-xl-5">
            <div class="col-lg-6 d-none d-lg-block">
                <div class="d-inline-flex align-items-center h-100">
                    <a wire:navigate href="/contac-tus" class="text-body mr-3" href="">Contact</a>

                    {{-- <a class="text-body mr-3" href="">About</a>
                    <a class="text-body mr-3" href="">Help</a>
                    <a class="text-body mr-3" href="">FAQs</a> --}}
                </div>
            </div>
            <div class="col-lg-6 text-center text-lg-right">
                <div class="d-inline-flex align-items-center">
                    <!-- Account Dropdown -->
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            My Account
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            @auth
                                <div class="dropdown-item text-center font-weight-bold mb-2">
                                    {{ auth()->user()->name }}
                                </div>
                                <a wire:navigate href="/my-profile" class="dropdown-item"><i class="fas fa-user"></i> My Profile</a>
                                <a wire:navigate href="/my-orders" class="dropdown-item"><i class="fas fa-box"></i> My Orders</a>
                                <a wire:navigate href="/logout" class="dropdown-item"><i class="fas fa-sign-out-alt"></i> Logout</a>
                            @endauth
                            @guest
                                <a wire:navigate href="/login" class="dropdown-item">Log In</a>
                                <a wire:navigate href="/register" class="dropdown-item">Sign Up</a>
                            @endguest
                        </div>

                    </div>

                    <!-- Wishlist and Cart -->
                    <div class="d-inline-flex align-items-center ml-3">
                        <a wire:navigate href="/wishlist" class="btn px-0">
                            <i class="fas fa-heart text-dark"></i>
                            <span class="badge text-dark border border-dark rounded-circle" style="padding-bottom: 2px;">{{ $total_wishlist_count }}</span>
                        </a>
                        <a wire:navigate href="/cart" class="btn px-0 ml-2">
                            <i class="fas fa-shopping-cart text-dark"></i>
                            <span class="badge text-dark border border-dark rounded-circle" style="padding-bottom: 2px;">{{ $total_count }}</span>
                        </a>
                    </div>
                </div>
            </div>

        </div>
        <div class="row align-items-center bg-light py-3 px-xl-5 d-none d-lg-flex">
            <div class="col-lg-4">
                <a href="" class="text-decoration-none">
                    <span class="h1 text-uppercase text-primary bg-dark px-2">Multi</span>
                    <span class="h1 text-uppercase text-dark bg-primary px-2 ml-n1">Shop</span>
                </a>
            </div>
            <div class="col-lg-4 col-6 text-left">
                <form wire:submit.prevent="searchProducts">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search for products" wire:model.debounce.300ms="search">
                        <div class="input-group-append">
                            {{-- <span class="input-group-text bg-transparent text-primary">
                                <i class="fa fa-search"></i>
                            </span> --}}
                            <button type="submit" class="input-group-text bg-transparent text-primary">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            @if ($contactDetail && $contactDetail->phone)
            <div class="col-lg-4 col-6 text-right">
                <p class="m-0">Customer Service</p>
                <h5 class="m-0">
                        {{ $contactDetail->phone }}
                </h5>
            </div>
            @endif
        </div>
    </div>
    <!-- Topbar End -->
</div>
