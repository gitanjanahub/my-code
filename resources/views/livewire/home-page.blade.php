<div>
    <!-- Carousel Start -->
    <div class="container-fluid mb-3">
        <div class="row px-xl-5">

            @if ($carouselBanners)
                <div class="col-lg-8">
                    <div id="header-carousel" class="carousel slide carousel-fade mb-30 mb-lg-0" data-ride="carousel">
                        <ol class="carousel-indicators">
                            @foreach ($carouselBanners as $index => $banner)
                                <li data-target="#header-carousel" data-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}"></li>
                            @endforeach
                        </ol>
                        <div class="carousel-inner">
                            @foreach ($carouselBanners as $index => $banner)
                                <div class="carousel-item position-relative {{ $index === 0 ? 'active' : '' }}" style="height: 430px;" wire:key={{ $banner->id }}>
                                    <img class="position-absolute w-100 h-100" src="{{ url('storage', $banner->image_path) }}" alt="{{ $banner->title }}" style="object-fit: cover;">
                                    <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                                        <div class="p-3" style="max-width: 700px;">
                                            <h1 class="display-4 text-white mb-3 animate__animated animate__fadeInDown">{{ $banner->title }}</h1>
                                            @if($banner->link)
                                                <a class="btn btn-outline-light py-2 px-4 mt-3 animate__animated animate__fadeInUp" href="{{ $banner->link }}">Shop Now</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            @if ($offerBanners)
                <div class="col-lg-4">
                    @foreach ($offerBanners as $obanner)
                        <div class="product-offer mb-30" style="height: 200px;" wire:key={{ $obanner->id }}>
                            <img class="img-fluid" src="{{ url('storage', $obanner->image_path) }}" alt="{{ $obanner->title }}">
                            <div class="offer-text">
                                <h6 class="text-white text-uppercase">{{ $obanner->title }}</h6>
                                @if($obanner->link)
                                <a href="{{ $obanner->link }}" class="btn btn-primary">Shop Now</a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
    <!-- Carousel End -->


    <!-- Featured Start -->
    <div class="container-fluid pt-5">
        <div class="row px-xl-5 pb-3">
            <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                <div class="d-flex align-items-center bg-light mb-4" style="padding: 30px;">
                    <h1 class="fa fa-check text-primary m-0 mr-3"></h1>
                    <h5 class="font-weight-semi-bold m-0">Quality Product</h5>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                <div class="d-flex align-items-center bg-light mb-4" style="padding: 30px;">
                    <h1 class="fa fa-shipping-fast text-primary m-0 mr-2"></h1>
                    <h5 class="font-weight-semi-bold m-0">Free Shipping</h5>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                <div class="d-flex align-items-center bg-light mb-4" style="padding: 30px;">
                    <h1 class="fas fa-exchange-alt text-primary m-0 mr-3"></h1>
                    <h5 class="font-weight-semi-bold m-0">14-Day Return</h5>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                <div class="d-flex align-items-center bg-light mb-4" style="padding: 30px;">
                    <h1 class="fa fa-phone-volume text-primary m-0 mr-3"></h1>
                    <h5 class="font-weight-semi-bold m-0">24/7 Support</h5>
                </div>
            </div>
        </div>
    </div>
    <!-- Featured End -->


    <!-- Categories Start -->
    @if ($categories)
        <div class="container-fluid pt-5">
            <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4"><span class="bg-secondary pr-3">Categories</span></h2>
            <div class="row px-xl-5 pb-3">
                @foreach ($categories as $category)
                    <div class="col-lg-3 col-md-4 col-sm-6 pb-1" wire:key={{ $category->id }}>
                        <a class="text-decoration-none" href="">
                            <div class="cat-item d-flex align-items-center mb-4">
                                <div class="overflow-hidden" style="width: 100px; height: 100px;">
                                    <img class="img-fluid" src="{{url('storage', $category->image)}}" alt="{{ $category->name }}">
                                </div>
                                <div class="flex-fill pl-3">
                                    <h6>{{ $category->name }}</h6>
                                    <small class="text-body">
                                        {{ $category->products_count > 0 ? $category->products_count . ' Products' : 'No products' }}
                                    </small>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
    <!-- Categories End -->



    <!-- Products Start -->
    @if ($productsFeatured)
        <div class="container-fluid pt-5 pb-3">
            <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4"><span class="bg-secondary pr-3">Featured Products</span></h2>
            <div class="row px-xl-5">
                @foreach ($productsFeatured as $featured)

                    <div class="col-lg-3 col-md-4 col-sm-6 pb-1" wire:key={{ $featured->id }}>
                        <div class="product-item bg-light mb-4">
                            <div class="product-img position-relative overflow-hidden">
                                <img class="img-fluid w-100" src="{{url('storage', $featured->thumb_image)}}" alt="{{ $featured->name }}">
                                <div class="product-action">
                                    <a class="btn btn-outline-dark btn-square" href=""><i class="fa fa-shopping-cart"></i></a>
                                    <a class="btn btn-outline-dark btn-square" href=""><i class="far fa-heart"></i></a>
                                    <a class="btn btn-outline-dark btn-square" href=""><i class="fa fa-sync-alt"></i></a>
                                    <a class="btn btn-outline-dark btn-square" href=""><i class="fa fa-search"></i></a>
                                </div>
                            </div>
                            <div class="text-center py-4">
                                <a class="h6 text-decoration-none text-truncate" href="/products/{{ $featured->slug }}">{{ $featured->name }}</a>
                                <div class="d-flex align-items-center justify-content-center mt-2">
                                    {{-- <h5>$123.00</h5><h6 class="text-muted ml-2"><del>$123.00</del></h6> --}}
                                    <h5>
                                        {{ Number::currency($featured->offer_price > 0 ? $featured->offer_price : $featured->price, 'INR') }}  <!-- Display offer price if greater than 0, otherwise display regular price -->
                                    </h5>

                                    @if ($featured->offer_price > 0)
                                        <h6 class="text-muted ml-2">
                                            <del>{{ Number::currency($featured->price, 'INR') }}</del>  <!-- Display the original price with strikethrough if offer price exists -->
                                        </h6>
                                    @endif
                                </div>
                                {{-- <div class="d-flex align-items-center justify-content-center mb-1">
                                    <small class="fa fa-star text-primary mr-1"></small>
                                    <small class="fa fa-star text-primary mr-1"></small>
                                    <small class="fa fa-star text-primary mr-1"></small>
                                    <small class="fa fa-star text-primary mr-1"></small>
                                    <small class="fa fa-star text-primary mr-1"></small>
                                    <small>(99)</small>
                                </div> --}}
                            </div>
                        </div>
                    </div>

                @endforeach

            </div>
        </div>
    @endif
    <!-- Products End -->


    <!-- Offer Start -->
    @if ($offerBanners)
        <div class="container-fluid pt-5 pb-3">
            <div class="row px-xl-5">
                @foreach ($offerBanners as $obanner)
                    <div class="col-md-6" wire:key={{ $obanner->id }}>
                        <div class="product-offer mb-30" style="height: 300px;">
                            <img class="img-fluid" src="{{ url('storage', $obanner->image_path) }}" alt="{{ $obanner->title }}">
                            <div class="offer-text">
                                {{-- <h6 class="text-white text-uppercase">Save 20%</h6> --}}
                                <h3 class="text-white mb-3">{{ $obanner->title }}</h3>

                                @if($obanner->link)
                                <a href="{{ $obanner->link }}" class="btn btn-primary">Shop Now</a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
    <!-- Offer End -->


    <!-- Products Start -->
    @if ($productsRecent)
        <div class="container-fluid pt-5 pb-3">
            <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4"><span class="bg-secondary pr-3">Recent Products</span></h2>
            <div class="row px-xl-5">
                @foreach ($productsRecent as $recent)
                    <div class="col-lg-3 col-md-4 col-sm-6 pb-1" wire:key={{ $recent->id }}>
                        <div class="product-item bg-light mb-4">
                            <div class="product-img position-relative overflow-hidden">
                                <img class="img-fluid w-100" src="{{url('storage', $recent->thumb_image)}}" alt="{{ $recent->name }}">
                                <div class="product-action">
                                    <a class="btn btn-outline-dark btn-square" href=""><i class="fa fa-shopping-cart"></i></a>
                                    <a class="btn btn-outline-dark btn-square" href=""><i class="far fa-heart"></i></a>
                                    <a class="btn btn-outline-dark btn-square" href=""><i class="fa fa-sync-alt"></i></a>
                                    <a class="btn btn-outline-dark btn-square" href=""><i class="fa fa-search"></i></a>
                                </div>
                            </div>
                            <div class="text-center py-4">
                                <a class="h6 text-decoration-none text-truncate" href="">{{ $recent->name }}</a>
                                <div class="d-flex align-items-center justify-content-center mt-2">
                                    <h5>
                                        {{ Number::currency($recent->offer_price > 0 ? $recent->offer_price : $recent->price, 'INR') }}  <!-- Display offer price if greater than 0, otherwise display regular price -->
                                    </h5>

                                    @if ($recent->offer_price > 0)
                                        <h6 class="text-muted ml-2">
                                            <del>{{ Number::currency($recent->price, 'INR') }}</del>  <!-- Display the original price with strikethrough if offer price exists -->
                                        </h6>
                                    @endif

                                </div>
                                {{-- <div class="d-flex align-items-center justify-content-center mb-1">
                                    <small class="fa fa-star text-primary mr-1"></small>
                                    <small class="fa fa-star text-primary mr-1"></small>
                                    <small class="fa fa-star text-primary mr-1"></small>
                                    <small class="fa fa-star text-primary mr-1"></small>
                                    <small class="fa fa-star text-primary mr-1"></small>
                                    <small>(99)</small>
                                </div> --}}
                            </div>
                        </div>
                    </div>

                @endforeach

            </div>
        </div>
    @endif
    <!-- Products End -->


    <!-- Vendor Start -->
    @if ($brands)
        <div class="container-fluid py-5">
            <div class="row px-xl-5">
                <div class="col">
                    <div class="owl-carousel vendor-carousel">
                        @foreach ($brands as $brand)
                            <div class="bg-light p-4">
                                <img src="{{url('storage', $brand->image)}}" alt="{{ $brand->name }}">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif
    <!-- Vendor End -->

</div>

