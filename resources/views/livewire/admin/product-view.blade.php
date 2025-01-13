<div>
    <div class="content-wrapper" style="margin-left: 5% !important;">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="">Product Details</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <a wire:navigate href="{{ route('admin.products') }}" class="btn btn-primary rounded-pill">
                                <i class="fas fa-arrow-left"></i> Back to Products
                            </a>
                            <a wire:navigate href="{{ route('admin.product-create') }}" class="btn btn-success rounded-pill">Create New</a>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5><i class="icon fas fa-exclamation-triangle"></i> Error!</h5>
                        {{ session('error') }}
                    </div>
                @endif

                @if($product)
                    <div class="card shadow-sm">
                        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                            <h3 class="card-title">{{ $product->name }}</h3>

                        </div>

                        <div class="card-body">
                            <div class="row">
                                <!-- Product Thumbnail -->
                                <div class="col-md-4">
                                    <img src="{{ Storage::url($product->thumb_image) }}"
                                         alt="{{ $product->name }}" class="img-fluid img-thumbnail"
                                         style="width: 100%; object-fit: cover;">
                                </div>

                                <!-- Product Details -->
                                <div class="col-md-8">
                                    <table class="table table-striped">
                                        <tr>
                                            <th>Name</th>
                                            <td>{{ $product->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Category</th>
                                            <td>{{ $product->category->name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Brand</th>
                                            <td>{{ $product->brand->name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Price</th>
                                            <td>${{ number_format($product->price, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Offer Price</th>
                                            <td>
                                                @if($product->on_sale && $product->offer_price)
                                                    ${{ number_format($product->offer_price, 2) }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Slug</th>
                                            <td>{{ $product->slug }}</td>
                                        </tr>
                                        <tr>
                                            <th>Featured</th>
                                            <td>{{ $product->is_featured ? 'Yes' : 'No' }}</td>
                                        </tr>
                                        <tr>
                                            <th>In Stock</th>
                                            <td>{{ $product->in_stock ? 'Yes' : 'No' }}</td>
                                        </tr>
                                        <tr>
                                            <th>On Sale</th>
                                            <td>{{ $product->on_sale ? 'Yes' : 'No' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Created At</th>
                                            <td>{{ $product->created_at->format('d M, Y') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <!-- Product Description -->
                            <div class="mt-4">
                                <h4>Description</h4>
                                <p>{{ $product->description ?? 'No description available' }}</p>
                            </div>

                            <!-- Product Description -->
                            <div class="mt-4">
                                <h4>Short Description</h4>
                                <p>{{ $product->short_description ?? 'No short description available' }}</p>
                            </div>

                            <!-- Additional Images -->
                            @if($product->image)
                            @php
                                $images = json_decode($product->image, true);
                            @endphp
                            @if(is_array($images) && count($images) > 0)
                                <div class="mt-4">
                                    <h4>Additional Images</h4>
                                    <div class="row">
                                        @foreach($images as $image)
                                            <div class="col-md-3 mb-3">
                                                <div class="card">
                                                    <img src="{{ Storage::url($image) }}"
                                                        class="img-fluid img-thumbnail"
                                                        alt="Product Image"
                                                        style="width: 100%; height: 200px; object-fit: cover;">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            @endif


                            <!-- Product Attributes and Values -->
                            <div class="mt-4">
                                <h4>Attributes</h4>
                                <table class="table table-bordered">
                                    <tbody>
                                        @php
                                            $groupedAttributes = $product->attributes->groupBy('name');
                                        @endphp

                                        @foreach($groupedAttributes as $attributeName => $attributeGroup)
                                            <tr>
                                                <th>{{ $attributeName }}</th>
                                                <td>
                                                    @foreach($attributeGroup as $attribute)
                                                        @php
                                                            $attributeValue = \App\Models\AttributeValue::find($attribute->pivot->attribute_value_id);
                                                        @endphp
                                                        <span class="badge badge-info">{{ $attributeValue ? $attributeValue->value : 'N/A' }}</span>
                                                    @endforeach
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Product Variants and Stock -->
{{-- <div class="mt-4">
    <h4>Variants and Stock</h4>
    @if($product->variants->count() > 0)
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>#</th>
                    <th>Variant Attributes</th>
                    <th>Stock Quantity</th>
                </tr>
            </thead>
            <tbody>
                @foreach($product->variants as $index => $variant)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            @php
                                $decodedAttributes = is_array($variant->attributes) ? $variant->attributes : json_decode($variant->attributes, true);
                            @endphp

                            @if ($decodedAttributes)
                            @foreach ($decodedAttributes as $key => $value)
                                <span class="badge badge-warning mr-1">
                                    <strong>{{ $key }}:</strong> {{ $value }}
                                </span>
                            @endforeach
                            @else
                            <span class="text-danger">Invalid attributes format</span>
                            @endif
                        </td>
                        <td>{{ $variant->stock_quantity }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="text-center text-muted">No variants available for this product.</p>
    @endif
</div> --}}


<div class="mt-4">
    <h4>Variants and Stock</h4>
    @if(count($rows) > 0)
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>#</th>
                    <th>Variant Attributes</th>
                    <th>Stock Quantity</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $index => $row)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            @foreach(explode(', ', $row['attributes']) as $attribute)
                                @php
                                    [$key, $value] = explode(': ', $attribute);
                                @endphp
                                <span class="badge badge-warning mr-1">
                                    <strong>{{ $key }}:</strong> {{ $value }}
                                </span>
                            @endforeach
                        </td>
                        <td>{{ $row['quantity'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="text-center text-muted">No variants available for this product.</p>
    @endif
</div>












                        </div>

                        <!-- Card Footer with Action Buttons -->
                        <div class="card-footer">
                            <a wire:navigate href="{{ route('admin.product-edit', $product->id) }}" class="btn btn-warning rounded-pill">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a wire:click="confirmDelete({{ $product->id }})" class="btn btn-danger rounded-pill" title="Delete" data-toggle="modal" data-target="#deleteModal">
                                <i class="fas fa-trash"></i> Delete
                            </a>
                            <a wire:navigate href="{{ route('admin.products') }}" class="btn btn-secondary rounded-pill">
                                <i class="fas fa-arrow-left"></i> Back to List
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </section>
    </div>

    <!-- Delete Confirmation Modal -->
    <div wire:ignore.self class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this product?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" wire:click="deleteProduct" data-dismiss="modal">Yes, Delete</button>
                </div>
            </div>
        </div>
    </div>

</div>
