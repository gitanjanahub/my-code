<div>
    <div class="content-wrapper" style="margin-left: 5% !important;">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Edit Brand</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <a wire:navigate href="{{ route('admin.brands') }}" class="btn btn-primary rounded-pill">Back to Brands</a>
                            <a wire:navigate href="{{ route('admin.brand-create') }}" class="btn btn-success rounded-pill">Create New</a>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- left column -->
                    <div class="col-md-12">
                        <!-- jquery validation -->
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Edit Brand</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form wire:submit.prevent="save">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="brandName">Brand Name</label>
                                        <input type="text" wire:model.lazy="name" class="form-control" id="brandName" placeholder="brand Name">
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="brandSlug">Slug</label>
                                        <input type="text" wire:model.defer="slug" class="form-control" id="brandSlug" placeholder="Slug">
                                        @error('slug')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>


                                    <div class="form-group">
                                        <label for="brandImage">Image</label>
                                        {{-- <div class="custom-file">
                                            <input type="file" wire:model="image" class="custom-file-input" id="brandImage">
                                            <label class="custom-file-label" for="brandImage">Choose file</label>
                                        </div> --}}
                                        <input type="file" wire:model="image" id="brandImage" class="form-control">
                                        @error('image')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="isActive">Is Active</label>
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" wire:model="is_active" class="custom-control-input" id="isActive" @checked($this->is_active)>
                                            <label class="custom-control-label" for="isActive"></label>
                                        </div>
                                    </div>

                                    @if ($brand->image)
                                        <div class="form-group">
                                            <label>Current Image</label>
                                            <img src="{{ asset('storage/' . $brand->image) }}" width="100" height="100" alt="brand Image">
                                        </div>
                                    @endif
                                </div>

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!--/.col (left) -->
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
</div>