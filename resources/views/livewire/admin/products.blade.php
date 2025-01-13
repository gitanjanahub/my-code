<div>
    <div class="content-wrapper" style="margin-left: 5% !important;">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Products</h1>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a wire:navigate href="{{ route('admin.product-create') }}" class="btn btn-success rounded-pill">Create New</a>
                    </div>
                </div>

                <!-- Flash Messages -->
                @if (session('message'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert" wire:poll.3s>
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5><i class="icon fas fa-check"></i> Alert!</h5>
                        {{ session('message') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert" wire:poll.3s>
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5><i class="icon fas fa-check"></i> Alert!</h5>
                        {{ session('error') }}
                    </div>
                @endif
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h4 class="card-title text-info">Total Products: <strong>{{ $totalProductsCount }}</strong></h4>

                        <div class="ml-auto">
                            <input type="text" wire:model.live.debounce.300ms="search" class="form-control w-100" style="width: 300px;" placeholder="Search Products...">
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <button class="btn btn-danger btn-sm" wire:click="confirmMultipleDelete" {{ count($selectedProducts) === 0 ? 'disabled' : '' }}>
                                Delete Selected
                            </button>

                        </div>

                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" wire:model.live="selectAll"> <!-- Select All Checkbox -->
                                    </th>
                                    <th>Sl No</th>
                                    <th>Thumbnail</th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Brand</th>
                                    <th>Price</th>
                                    <th>Offer Price</th>
                                    {{-- <th>Is Active</th>
                                    <th>Is Featured</th> --}}
                                    <th>In Stock</th>
                                    {{-- <th>On Sale</th> --}}
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($products as $key => $product)
                                    <tr>
                                        <td><input type="checkbox" wire:model.live="selectedProducts" value="{{ $product->id }}"></td>
                                        <td>{{ $products->firstItem() + $key }}</td>
                                        <td>
                                            <img src="{{ Storage::url($product->thumb_image) }}" style="width: 60px; height: 60px; object-fit: cover;">
                                        </td>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->category->name ?? 'N/A' }}</td>
                                        <td>{{ $product->brand->name ?? 'N/A' }}</td>
                                        <td>{{ Number::currency($product->price,'INR') }}</td>
                                        <td>{{ Number::currency($product->offer_price,'INR') }}</td>
                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input
                                                    type="checkbox"
                                                    class="custom-control-input"
                                                    id="switch-{{ $product->id }}"
                                                    wire:model="categories.{{ $loop->index }}.in_stock"
                                                    wire:change="toggleActive({{ $product->id }}, $event.target.checked)"
                                                    {{ $product->in_stock ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="switch-{{ $product->id }}"></label>
                                            </div>
                                        </td>

                                        <td>
                                            <a wire:navigate href="{{ route('admin.product-view', $product->id) }}" class="btn btn-primary btn-sm rounded-pill">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            <a wire:navigate href="{{ route('admin.product-edit', $product->id) }}" class="btn btn-warning btn-sm rounded-pill">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>

                                            <button wire:click="confirmDelete({{ $product->id }})" class="btn btn-danger btn-sm rounded-pill" title="Delete" data-toggle="modal" data-target="#deleteModal">
                                                <i class="fas fa-trash-alt"></i> Delete
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No Products Found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <div class="mt-3 d-flex justify-content-end">
                            {{ $products->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>

     <!-- Delete Confirmation Modal (Single) -->
     @if($showDeleteModal)
     <div class="modal fade show" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true" style="display: block;">
         <div class="modal-dialog">
             <div class="modal-content">
                 <div class="modal-header">
                     <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                     <button type="button" class="close" wire:click="$set('showDeleteModal', false)" aria-label="Close">
                         <span aria-hidden="true">&times;</span>
                     </button>
                 </div>
                 <div class="modal-body">
                     Are you sure you want to delete this brand?
                 </div>
                 <div class="modal-footer">
                     <button type="button" class="btn btn-secondary" wire:click="$set('showDeleteModal', false)">Cancel</button>
                     <button type="button" class="btn btn-danger" wire:click="deleteProduct" wire:click="$set('showDeleteModal', false)">Yes, Delete</button>
                 </div>
             </div>
         </div>
     </div>
     @endif

     <!-- Multiple Delete Confirmation Modal -->
     @if($showMultipleDeleteModal)
     <div class="modal fade show" id="multipleDeleteModal" tabindex="-1" aria-labelledby="multipleDeleteModalLabel" aria-hidden="true" style="display: block;">
         <div class="modal-dialog">
             <div class="modal-content">
                 <div class="modal-header">
                     <h5 class="modal-title" id="multipleDeleteModalLabel">Confirm Multiple Deletions</h5>
                     <button type="button" class="close" wire:click="$set('showMultipleDeleteModal', false)" aria-label="Close">
                         <span aria-hidden="true">&times;</span>
                     </button>
                 </div>
                 <div class="modal-body">
                     Are you sure you want to delete the selected products?
                 </div>
                 <div class="modal-footer">
                     <button type="button" class="btn btn-secondary" wire:click="$set('showMultipleDeleteModal', false)">Cancel</button>
                     <button type="button" class="btn btn-danger" wire:click="deleteSelectedProducts" wire:click="$set('showMultipleDeleteModal', false)">Yes, Delete</button>
                 </div>
             </div>
         </div>
     </div>
     @endif


</div>



