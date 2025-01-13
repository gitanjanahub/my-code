<div>


    {{-- <div>
        Selected Prices: {{ json_encode($selectedPrices) }}
    </div> --}}

    <!-- Shop Start -->
    <div class="container-fluid">
        <div class="row px-xl-5">

            <!-- Shop Sidebar Start -->
            <div class="col-lg-3 col-md-4">

                <!-- Category Start -->
                @if ($categories)
                <h5 class="section-title position-relative text-uppercase mb-3">
                    <span class="bg-secondary pr-3">Filter by category</span>
                </h5>
                <div class="bg-light p-4 mb-30">
                    <!-- Individual Category Checkboxes -->
                    @foreach ($categories as $category)
                        <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3" wire:key="{{ $category->id }}">
                            <input type="checkbox"
                                class="custom-control-input"
                                wire:model.live="selected_categories"
                                id="{{ $category->slug }}"
                                value="{{ $category->id }}">
                            <label class="custom-control-label" for="{{ $category->slug }}">{{ $category->name }}</label>
                            <span class="badge border font-weight-normal">{{ $category->products_count }}</span>
                        </div>
                    @endforeach
                </div>
                @endif
                <!-- Category End -->


                <!-- Brand Start -->
                @if ($brands)
                    <h5 class="section-title position-relative text-uppercase mb-3"><span class="bg-secondary pr-3">Filter by brand</span></h5>
                        <div class="bg-light p-4 mb-30" >

                            <!-- Individual Category Checkboxes -->
                            @foreach ($brands as $brand)
                            <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3" wire:key={{ $brand->id }}>
                                <input type="checkbox" class="custom-control-input" wire:model.live="selected_brands" id="{{ $brand->slug }}" value="{{ $brand->id }}">
                                <label class="custom-control-label" for="{{ $brand->slug }}">{{ $brand->name }}</label>
                                <span class="badge border font-weight-normal">{{ $brand->products_count }}</span>
                            </div>
                            @endforeach
                        </div>
                @endif
                <!-- Brand End -->
                <!-- Attribute Start -->

                @foreach ($attributesWithCounts as $attribute)
                    <div wire:key="attribute-{{ $attribute->id }}">
                        <h5 class="section-title position-relative text-uppercase mb-3">
                            <span class="bg-secondary pr-3">Filter by {{ $attribute->name }}</span>
                        </h5>
                        <div class="bg-light p-4 mb-30">

                            <!-- Individual Values -->
                            @foreach ($attribute->values as $value)
                            <div wire:key="value-{{ $value->id }}" class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                                <input type="checkbox"
                                    class="custom-control-input"
                                    id="attribute-{{ $value->id }}"
                                    wire:model.live="selected_values.{{ $attribute->id }}.{{ $value->id }}"
                                    value="{{ $value->id }}">
                                <label class="custom-control-label" for="attribute-{{ $value->id }}">{{ $value->value }}</label>
                                <span class="badge border font-weight-normal">{{ $value->products_count ?? 0 }}</span>
                            </div>
                            @endforeach

                        </div>
                    </div>
                @endforeach



                <!-- Attributes End -->


                <!-- Price Start -->
            <h5 class="section-title position-relative text-uppercase mb-3">
                <span class="bg-secondary pr-3">Filter by price</span>
            </h5>
            <div class="bg-light p-4 mb-30">

                <!-- Individual Price Ranges -->
                @foreach ($priceRanges as $range)
                <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                    <input type="checkbox" class="custom-control-input" id="price-{{ $range['min'] }}-{{ $range['max'] }}"
                        wire:model.live="selectedPrices" value="{{ $range['min'] }}-{{ $range['max'] }}"
                        {{ in_array("{$range['min']}-{$range['max']}", $selectedPrices) ? 'checked' : '' }}>
                    <label class="custom-control-label" for="price-{{ $range['min'] }}-{{ $range['max'] }}">
                        ₹{{ $range['min'] }} - ₹{{ $range['max'] }}
                    </label>
                    <span class="badge border font-weight-normal">{{ $range['count'] }}</span>
                </div>
                @endforeach

            </div>
            <!-- Price End -->


            </div>
            <!-- Shop Sidebar End -->


            <!-- Shop Product Start -->
            <div class="col-lg-9 col-md-8">
                <div class="row pb-3">
                    <div class="col-12 pb-1">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div>
                                <button class="btn btn-sm btn-light"><i class="fa fa-th-large"></i></button>
                                <button class="btn btn-sm btn-light ml-2"><i class="fa fa-bars"></i></button>
                            </div>
                            <div class="ml-2">
                                <div class="btn-group" wire:ignore.self>
                                    <button type="button" class="btn btn-sm btn-light dropdown-toggle" data-toggle="dropdown">Sorting</button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        {{-- <a class="dropdown-item" href="#" wire:click="updateSorting('latest')">Latest</a>
                                        <a class="dropdown-item" href="#" wire:click="updateSorting('popularity')">Popularity</a>
                                        <a class="dropdown-item"  wire:click="updateSorting('price_low_to_high')">Price Low to High</a>
                                        <a class="dropdown-item"  wire:click="updateSorting('price')">Price</a>
                                        <a class="dropdown-item"  wire:click="updateSorting('price_high_to_low')">Price High to Low</a>
                                        <a class="dropdown-item" href="#" wire:click="updateSorting('name')">Name</a>
                                        <a class="dropdown-item" href="#">Best Rating</a> --}}
                                        <a class="dropdown-item" wire:click="$set('sorting', 'latest')">Latest</a>
                                        <a class="dropdown-item" wire:click="$set('sorting', 'name')">Name</a>
                                        <a class="dropdown-item" wire:click="$set('sorting', 'price_low_to_high')">Price: Low to High</a>
                                        <a class="dropdown-item" wire:click="$set('sorting', 'price_high_to_low')">Price: High to Low</a>

                                    </div>
                                </div>
                                <div class="btn-group ml-2" wire:ignore.self>
                                    <button type="button" class="btn btn-sm btn-light dropdown-toggle" data-toggle="dropdown">Showing {{ $perPage }}</button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item"  wire:click.prevent="updatePerPage(10)">10</a>
                                        <a class="dropdown-item"  wire:click.prevent="updatePerPage(20)">20</a>
                                        <a class="dropdown-item"  wire:click.prevent="updatePerPage(30)">30</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @forelse ($products as $product)
                        <div class="col-lg-4 col-md-6 col-sm-6 pb-1" wire:key={{ $product->id }}>
                            <div class="product-item bg-light mb-4">
                                <div class="product-img position-relative overflow-hidden">
                                    <img class="img-fluid w-100" src="{{ url('storage', $product->thumb_image) }}" alt="{{ $product->name }}">
                                    <div class="product-action">
                                        <a class="btn btn-outline-dark btn-square" href="/products/{{ $product->slug }}"><i class="fa fa-shopping-cart"></i></a>
                                        <a class="btn btn-outline-dark btn-square" wire:click="toggleWishlist({{ $product->id }})"><i class="{{ in_array($product->id, $wishlistProductIds) ? 'fa fa-heart' : 'far fa-heart' }}"></i></a>
                                        {{-- <a class="btn btn-outline-dark btn-square" href=""><i class="fa fa-sync-alt"></i></a>
                                        <a class="btn btn-outline-dark btn-square" href=""><i class="fa fa-search"></i></a> --}}
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
                                <p class="m-0 font-weight-bold">Oops! No Products Found.</p>
                                <small class="text-muted">Please adjust your filters or check back later!</small>
                            </div>
                        </div>

                    @endforelse






                    <!-- Custom Pagination -->
                    <div class="col-12">
                        <div class="d-flex justify-content-center">
                            {{ $products->links('pagination.custom') }}
                        </div>
                    </div>
                </div>
            </div>
            <!-- Shop Product End -->
        </div>
    </div>
    <!-- Shop End -->
</div>
