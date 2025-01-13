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
                        <h4 class="card-title text-info">Total Products: <strong>{{ count($products) }}</strong></h4>

                        <div class="ml-auto">
                            <input type="text" wire:model.live.debounce.300ms="search" class="form-control w-100" style="width: 300px;" placeholder="Search Products...">
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Sl No</th>
                                    <th>Product Name</th>
                                    <th>Variants & Stocks</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($products as $key => $product)
                                    <tr>
                                        <td>{{ $products->firstItem() + $key }}</td>
                                        <td>{{ $product->name }}</td>
                                        <td>
                                            @foreach ($product->variants as $variant)
                                                <div>
                                                    <!-- Display the formatted attributes -->
                                                    <span class="badge badge-warning">{{ $variant->formattedAttributes }}</span>
                                                    <span class="ml-3"><strong>Stock:</strong> {{ $variant->stock_quantity }}</span>
                                                </div>
                                            @endforeach
                                        </td>
                                        <td>
                                            <a wire:navigate href="{{ route('admin.stock-view', $product->id) }}" class="btn btn-sm btn-info rounded-pill">View</a>
                                            @if ($product->variants->isEmpty())
                                                <a wire:navigate href="{{ route('admin.stock-create', $product) }}" class="btn btn-sm btn-primary rounded-pill">
                                                    Add Stock
                                                </a>
                                            @else
                                                <a wire:navigate href="{{ route('admin.stock-edit', $product->id) }}" class="btn btn-sm btn-warning rounded-pill">
                                                    Edit Stock
                                                </a>
                                            @endif
                                            <a wire:navigate href="{{ route('admin.product-view', $product->id) }}" class="btn btn-sm btn-success rounded-pill">Product View</a>
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
</div>
