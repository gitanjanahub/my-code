<div>
    <div class="content-wrapper" style="margin-left: 5% !important;">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Banners</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <a wire:navigate href="{{ route('admin.banner-create') }}" class="btn btn-success rounded-pill">Create New</a>
                        </ol>
                    </div>
                </div>
            </div>

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
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="card-title text-info">Total Banners: <strong>{{ $totalBannersCount }}</strong></h4>
                                <!-- Search Input Aligned to the Right -->
                                <div class="ml-auto">
                                    <input type="text" wire:model.live.debounce.300ms="search" class="form-control w-100" style="width: 300px;" placeholder="Search Banners...">
                                </div>
                            </div>
                            <div class="card-body">

                                <div class="mb-2">

                                    <button class="btn btn-danger btn-sm" wire:click="confirmMultipleDelete" {{ count($selectedBanners) === 0 ? 'disabled' : '' }}>
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
                                        <th>Title</th>
                                        <th>Image</th>
                                        <th>Is Active</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($banners as $key => $banner)
                                            <tr wire:key="{{ $banner->id }}">
                                                <td><input type="checkbox" wire:model.live="selectedBanners" value="{{ $banner->id }}"></td>
                                                <td>{{ $banners->firstItem() + $key }}</td>
                                                <td>{{ $banner->title }}</td>
                                                <td>
                                                    <img src="{{ asset('storage/' . $banner->image_path) }}" alt="{{ $banner->title }}" width="50" height="50">
                                                </td>
                                                <td>
                                                    <div class="custom-control custom-switch">
                                                        <input
                                                            type="checkbox"
                                                            class="custom-control-input"
                                                            id="switch-{{ $banner->id }}"
                                                            wire:model="categories.{{ $loop->index }}.is_active"
                                                            wire:change="toggleActive({{ $banner->id }}, $event.target.checked)"
                                                            {{ $banner->is_active ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="switch-{{ $banner->id }}"></label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <a wire:navigate href="{{ route('admin.banner-view', $banner->id) }}" class="btn btn-primary btn-sm rounded-pill" title="View">
                                                        <i class="fas fa-eye"></i> View
                                                    </a>
                                                    <a wire:navigate class="btn btn-warning btn-sm rounded-pill" title="Edit" href="{{ route('admin.banner-edit', $banner->id) }}">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    <button wire:click="confirmDelete({{ $banner->id }})" class="btn btn-danger btn-sm rounded-pill" title="Delete" data-toggle="modal" data-target="#deleteModal">
                                                        <i class="fas fa-trash-alt"></i> Delete
                                                    </button>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="5" >
                                                    <div class="alert alert-warning alert-dismissible">
                                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                                                        <h5><i class="icon fas fa-exclamation-triangle"></i> Alert!</h5>
                                                        No Items In Banners.
                                                      </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>

                                <!-- Pagination -->
                                <div class="d-flex justify-content-end mt-3">
                                    {{ $banners->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>




<div>
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
                    Are you sure you want to delete this banner?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="$set('showDeleteModal', false)">Cancel</button>
                    <button type="button" class="btn btn-danger" wire:click="deleteBanner" wire:click="$set('showDeleteModal', false)">Yes, Delete</button>
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
                    Are you sure you want to delete the selected banners?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="$set('showMultipleDeleteModal', false)">Cancel</button>
                    <button type="button" class="btn btn-danger" wire:click="deleteSelectedBanners" wire:click="$set('showMultipleDeleteModal', false)">Yes, Delete</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>





