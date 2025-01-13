<div>
    <!-- Shop Start -->
    <div class="container-fluid">
        <div class="row px-xl-5">

            <!-- Shop Product Start -->
            <div class="col-lg-12 col-md-12">
                <div class="row pb-3">
                    <div class="col-12 pb-1">
                        <h5 class="section-title position-relative text-uppercase mb-3">
                            <span class="bg-secondary pr-3">Your Wishlist ({{ $totalWishlistCount }})</span>
                        </h5>
                    </div>

                    @forelse ($wishlistProducts as $product)
                    <div class="col-lg-4 col-md-6 col-sm-6 pb-1" wire:key="{{ $product->id }}">
                        <div class="product-item bg-light mb-4">
                            <div class="product-img position-relative overflow-hidden">
                                <img class="img-fluid w-100" src="{{ url('storage', $product->thumb_image) }}" alt="{{ $product->name }}">
                                <div class="product-action">
                                    <a class="btn btn-outline-dark btn-square" href="/products/{{ $product->slug }}"><i class="fa fa-shopping-cart"></i></a>
                                    <a class="btn btn-outline-dark btn-square" wire:click="toggleWishlist({{ $product->id }})">
                                        <i class="{{ in_array($product->id, $wishlistProductIds) ? 'fa fa-heart' : 'far fa-heart' }}"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="text-center py-4">
                                <a class="h6 text-decoration-none text-truncate" href="/products/{{ $product->slug }}">{{ $product->name }}</a>
                                <div class="d-flex align-items-center justify-content-center mt-2">
                                    <h5>
                                        {{ Number::currency($product->offer_price > 0 ? $product->offer_price : $product->price, 'INR') }}
                                    </h5>
                                    @if ($product->offer_price > 0)
                                        <h6 class="text-muted ml-2">
                                            <del>{{ Number::currency($product->price, 'INR') }}</del>
                                        </h6>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <div class="alert alert-warning d-inline-block" style="font-size: 1.2rem; padding: 1.5rem 2rem; border-radius: 10px;">
                                <i class="fas fa-box-open fa-3x mb-3 text-warning"></i>
                                <p class="m-0 font-weight-bold">Oops! No products in your wishlist.</p>
                                <small class="text-muted">Browse and add products to your wishlist.</small>
                            </div>
                        </div>
                    @endforelse

                    <!-- Custom Pagination -->
                    <div class="col-12">
                        <div class="d-flex justify-content-center">
                            {{ $wishlistProducts->links('pagination.custom') }}
                        </div>
                    </div>
                </div>
            </div>
            <!-- Shop Product End -->
        </div>
    </div>
    <!-- Shop End -->
</div>
