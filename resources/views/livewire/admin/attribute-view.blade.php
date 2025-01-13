<div>
    <div class="content-wrapper" style="margin-left: 5% !important;">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>View Attribute</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <a wire:navigate href="{{ route('admin.attributes') }}" class="btn btn-primary rounded-pill">
                                Back to Attributes
                            </a>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main Content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">Attribute Details</h3>
                            </div>
                            <div class="card-body">
                                <!-- Attribute Details -->
                                <dl class="row">
                                    <dt class="col-sm-4">Attribute Name:</dt>
                                    <dd class="col-sm-8">{{ $attribute->name }}</dd>

                                    <dt class="col-sm-4">Attribute Values:</dt>
                                    <dd class="col-sm-8">
                                        @if($attribute->values->isNotEmpty())
                                            @foreach($attribute->values as $value)
                                                <span class="badge badge-info">{{ $value->value }}</span>
                                            @endforeach
                                        @else
                                            <span>No values found.</span>
                                        @endif
                                    </dd>

                                    <dt class="col-sm-4">Created At:</dt>
                                    <dd class="col-sm-8">{{ $attribute->created_at->format('F d, Y') }}</dd>

                                    <dt class="col-sm-4">Updated At:</dt>
                                    <dd class="col-sm-8">{{ $attribute->updated_at->format('F d, Y') }}</dd>
                                </dl>
                            </div>
                            <div class="card-footer">
                                <a wire:navigate href="{{ route('admin.attribute-edit', $attribute->id) }}" class="btn btn-warning rounded-pill">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <button wire:click="confirmDelete({{ $attribute->id }})" class="btn btn-danger rounded-pill" title="Delete" data-toggle="modal" data-target="#deleteModal">
                                    <i class="fas fa-trash-alt"></i> Delete
                                </button>
                                <a wire:navigate href="{{ route('admin.attributes') }}" class="btn btn-secondary rounded-pill">
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
                    Are you sure you want to delete this attribute along with its values?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" wire:click="deleteAttribute" data-dismiss="modal">
                        Yes, Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
