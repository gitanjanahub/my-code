<div class="content-wrapper" style="margin-left: 5% !important;">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-3 align-items-center">
                <div class="col-sm-6">
                    <h1 class="text-primary">Stock Management</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a wire:navigate href="{{ route('admin.products') }}" class="btn btn-secondary btn-sm rounded-pill shadow-sm">
                        <i class="fas fa-arrow-left"></i> Back to Products
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <!-- Product Information -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-info text-white">
                    <h3 class="card-title">Product Details</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <h5><strong>Product Name:</strong></h5>
                            <p>{{ $product->name }}</p>
                        </div>
                        <div class="col-md-4">
                            <h5><strong>Product ID:</strong></h5>
                            <p>{{ $productId }}</p>
                        </div>
                        <div class="col-md-4">
                            <h5><strong>Total Variant Combinations:</strong></h5>
                            <p>{{ count($stocks) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stock Table -->
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title">Stock Details</h3>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Attributes</th>
                                <th>Stock Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($stocks as $index => $stock)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td><span class="badge badge-warning">{{ $stock['attributes'] }} </span></td>
                                    <td>{{ $stock['quantity'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">No stocks found for this product!</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer text-right">
                    <a wire:navigate href="{{ route('admin.stocks') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-cogs"></i> Back to Stock List
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>

