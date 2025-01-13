<div>
    <div class="content-wrapper" style="margin-left: 5% !important;">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Banner Details</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <a wire:navigate href="{{ route('admin.banners') }}" class="btn btn-primary rounded-pill mr-2">Back to Banners</a>
                            <a wire:navigate href="{{ route('admin.banner-create') }}" class="btn btn-success rounded-pill">Create New</a>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <!-- banner details card -->
                    <div class="col-lg-8 col-md-10">
                        <div class="card shadow-lg">
                            <div class="card-header bg-info text-white">
                                <h3 class="card-title">Banner Information</h3>
                            </div>
                            <div class="card-body">
                                <!-- Image Display -->
                                <div class="text-center mb-4">
                                    <img src="{{ asset('storage/' . $banner->image_path) }}"
                                         alt="{{ $banner->title }}"
                                         class="img-fluid rounded"
                                         style="max-width: 200px;">
                                </div>

                                <!-- banner Details -->
                                <table class="table table-striped table-bordered">
                                    <tbody>
                                        <tr>
                                            <th style="width: 30%;">Banner Title:</th>
                                            <td>{{ $banner->title }}</td>
                                        </tr>

                                        <tr>
                                            <th>Link:</th>
                                            <td>{{ $banner->link }}</td>
                                        </tr>

                                        <tr>
                                            <th>Section:</th>
                                            <td>{{ $banner->section }}</td>
                                        </tr>
                                        <tr>
                                            <th>Size:</th>
                                            <td>{{ $banner->size }}</td>
                                        </tr>
                                        <tr>
                                            <th>Order:</th>
                                            <td>{{ $banner->order }}</td>
                                        </tr>

                                        <tr>
                                            <th>Status:</th>
                                            <td>
                                                @if($banner->is_active)
                                                    <span class="badge badge-success">Active</span>
                                                @else
                                                    <span class="badge badge-danger">Inactive</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Card Footer with Action Buttons -->
                            <div class="card-footer d-flex justify-content-between">
                                <a wire:navigate href="{{ route('admin.banner-edit', $banner->id) }}" class="btn btn-warning rounded-pill">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a wire:click="confirmDelete({{ $banner->id }})" class="btn btn-danger rounded-pill" title="Delete" data-toggle="modal" data-target="#deleteModal">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                                <a wire:navigate href="{{ route('admin.banners') }}" class="btn btn-secondary rounded-pill">
                                    <i class="fas fa-arrow-left"></i> Back to List
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
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
                    Are you sure you want to delete this banner?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" wire:click="deleteBanner" data-dismiss="modal">Yes, Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>

