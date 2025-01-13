<div>
    <div class="content-wrapper" style="margin-left: 5% !important;">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Edit Product</h1>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a wire:navigate href="{{ route('admin.products') }}" class="btn btn-primary rounded-pill">
                            Back to Products
                        </a>
                        <a wire:navigate href="{{ route('admin.product-create') }}" class="btn btn-success rounded-pill">Create New</a>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-md-10">
                        <div class="card shadow-lg">
                            <div class="card-header bg-primary text-white">
                                <h3 class="card-title">Edit Product</h3>
                            </div>

                            <form wire:submit.prevent="save">
                                <div class="card-body">
                                    <div class="row">
                                        <!-- Product Name -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="productName">Product Name <span class="text-danger">*</span></label>
                                                <input type="text" wire:model.lazy="name" id="productName" class="form-control" placeholder="Enter product name">
                                                @error('name')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Slug -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="productSlug">Slug <span class="text-danger">*</span></label>
                                                <input type="text" wire:model.defer="slug" id="productSlug" class="form-control" placeholder="Enter product slug">
                                                @error('slug')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Description -->
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="description">Description <span class="text-danger">*</span></label>
                                                <textarea wire:model="description" id="description" class="form-control" rows="4" placeholder="Enter product description"></textarea>
                                                @error('description')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Short Description -->
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="short_description">Short Description <span class="text-danger">*</span></label>
                                                <textarea wire:model="short_description" id="short_description" class="form-control" rows="4" placeholder="Enter product short description"></textarea>
                                                @error('short_description')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Category -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="categorySelect">Category <span class="text-danger">*</span></label>
                                                <select wire:model="category_id" id="categorySelect" class="form-control">
                                                    <option value="">Select Category</option>
                                                    @foreach($categories as $category)
                                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('category_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Brand -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="brandSelect">Brand <span class="text-danger">*</span></label>
                                                <select wire:model="brand_id" id="brandSelect" class="form-control">
                                                    <option value="">Select Brand</option>
                                                    @foreach($brands as $brand)
                                                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('brand_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Price -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="price">Price <span class="text-danger">*</span></label>
                                                <input type="text" wire:model="price" id="price" class="form-control" placeholder="Enter product price">
                                                @error('price')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Offer Price -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="offerPrice">Offer Price</label>
                                                <input type="text" wire:model="offer_price" id="offerPrice" class="form-control" placeholder="Enter offer price (if any)">
                                                @error('offer_price')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Thumbnail Image Upload -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="thumbImage">Thumbnail Image <span class="text-danger">*</span></label>
                                                <input type="file" wire:model="thumb_image" id="thumbImage" class="form-control-file">
                                                @if ($thumb_image)
                                                    <img src="{{ $thumb_image->temporaryUrl() }}" alt="Thumbnail Preview" class="img-thumbnail mt-2" style="width: 150px;">
                                                @else
                                                    <img src="{{ Storage::url($existingImages['thumb_image']) }}" alt="Existing Thumbnail" class="img-thumbnail mt-2" style="width: 150px;">
                                                @endif

                                                @error('thumb_image')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Multiple Images Upload -->
                                        {{-- <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="multipleImages">Product Images</label>
                                                <input type="file" wire:model="images" id="multipleImages" class="form-control-file" multiple>
                                                <div class="mt-2">
                                                    @if ($images && count($images) > 0)
                                                        <!-- Display newly uploaded images -->
                                                        @foreach ($images as $image)
                                                            <img src="{{ $image->temporaryUrl() }}" alt="Image Preview" class="img-thumbnail mr-2" style="width: 100px;">
                                                        @endforeach
                                                    @elseif (!empty($existingImages['images']) && is_array($existingImages['images']))
                                                        <!-- Display existing images -->
                                                        @foreach($existingImages['images'] as $image)
                                                            <img src="{{ Storage::url($image) }}" alt="Existing Image" class="img-thumbnail mr-2" style="width: 100px;">
                                                        @endforeach
                                                    @else
                                                        <!-- If no images exist -->
                                                        <p>No images available.</p>
                                                    @endif
                                                </div>


                                                @error('images')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div> --}}

                                        <!-- Multiple Images Upload -->
<div class="col-md-12">
    <div class="form-group">
        <label for="multipleImages">Product Images</label>
        <input type="file" wire:model="images" id="multipleImages" class="form-control-file" multiple>
        <div class="mt-2">
            @if ($images && count($images) > 0)
                <!-- Display newly uploaded images -->
                @foreach ($images as $image)
                    <img src="{{ $image->temporaryUrl() }}" alt="Image Preview" class="img-thumbnail mr-2" style="width: 100px;">
                @endforeach
            @elseif (!empty($existingImages['images']) && is_array($existingImages['images']))
                <!-- Display existing images with delete option -->
                @foreach($existingImages['images'] as $key => $image)
                    {{-- <div class="d-inline-block position-relative mr-2">
                        <img src="{{ Storage::url($image) }}" alt="Existing Image" class="img-thumbnail" style="width: 100px;">
                        <button type="button" wire:click="deleteImage('{{ $image }}', {{ $key }})" class="btn btn-danger btn-sm position-absolute" style="top: 0; right: 0;">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div> --}}

                    <div class="d-inline-block position-relative mr-2">
                        <!-- Image with fixed dimensions -->
                        <img src="{{ Storage::url($image) }}" alt="Existing Image" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">

                        <!-- Delete Button positioned on the top-right corner -->
                        <button type="button" wire:click="deleteImage('{{ $image }}', {{ $key }})" class="btn btn-danger btn-sm position-absolute" style="top: 5px; right: 5px; padding: 5px;">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>

                @endforeach
            @else
                <!-- If no images exist -->
                <p>No images available.</p>
            @endif
        </div>

        @error('images')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
</div>




                                        <!-- Product Status Toggle Buttons -->
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Product Status</label>
                                                <div class="d-flex flex-wrap align-items-center">
                                                    <label for="isActive" class="form-check-label mr-2">Is Active</label>
                                                    <div class="custom-control custom-switch mr-4">
                                                        <input type="checkbox" wire:model="is_active" class="custom-control-input" id="isActive" @checked($this->is_active)>
                                                        <label class="custom-control-label" for="isActive"></label>
                                                    </div>

                                                    <label for="isFeatured" class="form-check-label mr-2">Is Featured</label>
                                                    <div class="custom-control custom-switch mr-4">
                                                        <input type="checkbox" wire:model="is_featured" class="custom-control-input" id="isFeatured" @checked($this->is_featured)>
                                                        <label class="custom-control-label" for="isFeatured"></label>
                                                    </div>

                                                    <label for="inStock" class="form-check-label mr-2">In Stock</label>
                                                    <div class="custom-control custom-switch mr-4">
                                                        <input type="checkbox" wire:model="in_stock" class="custom-control-input" id="inStock" @checked($this->in_stock)>
                                                        <label class="custom-control-label" for="inStock"></label>
                                                    </div>

                                                    <label for="onSale" class="form-check-label mr-2">On Sale</label>
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" wire:model="on_sale" class="custom-control-input" id="onSale" @checked($this->on_sale)>
                                                        <label class="custom-control-label" for="onSale"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Attributes</label>
                                                @foreach($attributeNames as $attribute)
                                                    <div class="card mb-3">
                                                        <div class="card-header bg-light">
                                                            <strong>{{ $attribute->name }}</strong>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                @foreach($attribute->values as $value)
                                                                    <div class="col-md-3">
                                                                        <div class="form-check">
                                                                            <input
                                                                                type="checkbox"
                                                                                wire:model="selectedAttributes.{{ $attribute->id }}.{{ $value->id }}"
                                                                                class="form-check-input"
                                                                                id="attribute-{{ $attribute->id }}-value-{{ $value->id }}"
                                                                                value="{{ $value->id }}"
                                                                            >
                                                                            <label class="form-check-label" for="attribute-{{ $attribute->id }}-value-{{ $value->id }}">
                                                                                {{ $value->value }}
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                                @error('selectedAttributes')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>




                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary rounded-pill">
                                        Update Product
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
