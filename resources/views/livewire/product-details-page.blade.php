<div>
    <div class="container-fluid pb-5">
        <div class="row px-xl-5">
            <div class="col-lg-5 mb-30" wire:ignore>
                <!-- Product Image Carousel -->
                @if (!empty($product->image) && is_array($product->image))
                    <div id="product-carousel" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner bg-light">
                            @foreach ($product->image as $image)
                                <div class="carousel-item @if ($loop->first) active @endif">
                                    <img class="w-100 h-100" src="{{ Storage::url($image) }}" alt="Image">
                                </div>
                            @endforeach
                        </div>
                        <a class="carousel-control-prev" href="#product-carousel" data-slide="prev">
                            <i class="fa fa-2x fa-angle-left text-dark"></i>
                        </a>
                        <a class="carousel-control-next" href="#product-carousel" data-slide="next">
                            <i class="fa fa-2x fa-angle-right text-dark"></i>
                        </a>
                    </div>
                @else
                    <p>No images available.</p>
                @endif
            </div>


            <div class="col-lg-7 h-auto mb-30">
                <div class="h-100 bg-light p-30">
                    <h3>{{ $product->name }}</h3>

                    <div class="mb-4">
                        @if ($product->offer_price > 0)
                            <h3 class="font-weight-semi-bold mb-2 d-inline-block">
                                {{ Number::currency($product->offer_price, 'INR') }}
                            </h3>
                            <h5 class="text-muted d-inline-block ml-3">
                                <del>{{ Number::currency($product->price, 'INR') }}</del>
                            </h5>
                        @else
                            <h3 class="font-weight-semi-bold mb-4">
                                {{ Number::currency($product->price, 'INR') }}
                            </h3>
                        @endif
                    </div>

                    <p class="mb-4">{{ $product->short_description }}</p>

                    <!-- Display Product Attributes Dynamically -->
                    @if(empty($groupedVariants))
                        <div class="alert alert-warning text-center">
                            No Stocks Available
                        </div>
                    @else
                    <form>
                        @foreach($groupedVariants as $attributeName => $values)
                            <div class="d-flex mb-4">
                                <strong class="text-dark mr-3">{{ ucfirst($attributeName) }}:</strong>
                                @foreach($values as $index => $value)
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input
                                            type="radio"
                                            class="custom-control-input"
                                            id="{{ $attributeName }}-{{ $index }}"
                                            wire:model="selectedAttributes.{{ $value['attribute_id'] }}"
                                            value="{{ $value['value_id'] }}"
                                        >
                                        <label class="custom-control-label" for="{{ $attributeName }}-{{ $index }}">
                                            {{ $value['value'] }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </form>
                    @endif

                    <!-- Error Message -->
                    {{-- @if($errorMessage)
                        <div class="alert alert-danger">
                            {{ $errorMessage }}
                        </div>
                    @endif --}}

                    <!-- Add to Cart -->
                    @if(!empty($groupedVariants))
                        <div class="d-flex align-items-center mb-4 pt-2">
                            <div class="input-group quantity mr-3" style="width: 130px;">
                                <div class="input-group-btn">
                                    <button wire:click="decreaseQty" class="btn btn-primary btn-minus">
                                        <i class="fa fa-minus"></i>
                                    </button>
                                </div>
                                <input type="text" class="form-control bg-secondary border-0 text-center" wire:model="quantity" value="1">
                                <div class="input-group-btn">
                                    <button wire:click="increaseQty" class="btn btn-primary btn-plus">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary px-3" wire:click="addToCart({{ $product->id }})">
                                <i class="fa fa-shopping-cart mr-1"></i>
                                <span wire:loading.remove wire:target="addToCart({{ $product->id }})">Add to Cart</span>
                                <span wire:loading wire:target="addToCart({{ $product->id }})">Adding...</span><!--new sapn added-->
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>


<!-- Products Start -->
<div class="container-fluid py-5" wire:ignore>
    <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4">
        <span class="bg-secondary pr-3">You May Also Like</span>
    </h2>
    <div class="row px-xl-5">
        <div class="col">
            <div class="owl-carousel related-carousel">
                @foreach($relatedProducts as $relatedProduct)
                    <div class="product-item bg-light">
                        <div class="product-img position-relative overflow-hidden">
                            <img class="img-fluid w-100"
                                 src="{{ url('storage', $relatedProduct->thumb_image) }}"
                                 alt="{{ $relatedProduct->name }}">
                            <div class="product-action">
                                <a class="btn btn-outline-dark btn-square" href="">
                                    <i class="fa fa-shopping-cart"></i>
                                </a>
                                <a class="btn btn-outline-dark btn-square" href="">
                                    <i class="far fa-heart"></i>
                                </a>
                                <a class="btn btn-outline-dark btn-square" href="">
                                    <i class="fa fa-sync-alt"></i>
                                </a>
                                <a class="btn btn-outline-dark btn-square" href="/products/{{ $relatedProduct->slug }}">
                                    <i class="fa fa-search"></i>
                                </a>
                            </div>
                        </div>
                        <div class="text-center py-4">
                            <a class="h6 text-decoration-none text-truncate"
                               href="/products/{{ $relatedProduct->slug }}">
                                {{ $relatedProduct->name }}
                            </a>
                            <div class="d-flex align-items-center justify-content-center mt-2">
                                <h5>
                                    {{ Number::currency($relatedProduct->offer_price > 0 ? $relatedProduct->offer_price : $relatedProduct->price, 'INR') }}
                                </h5>
                                @if($relatedProduct->offer_price > 0)
                                    <h6 class="text-muted ml-2">
                                        <del>{{ Number::currency($relatedProduct->price, 'INR') }}</del>
                                    </h6>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Products End -->
</div>
