<div>
    <div class="content-wrapper" style="margin-left: 5% !important;">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1>Categories</h1>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <a wire:navigate href="{{ route('admin.categories') }} " class="btn btn-primary rounded-pill">Back to Categories</a>

                </ol>
              </div>
            </div>
          </div><!-- /.container-fluid -->
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
                    <h3 class="card-title">Create Category</h3>
                  </div>
                  <!-- /.card-header -->
                  <!-- form start -->
                  <form wire:submit.prevent="save">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="categoryName">Category Name</label>
                            <input type="text" wire:model.lazy="name" class="form-control" id="categoryName" placeholder="Category Name">
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="categorySlug">Slug</label>
                            <input type="text" wire:model.defer="slug" class="form-control" id="categorySlug" placeholder="Slug">
                            @error('slug')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="categoryImage">Image</label>
                            {{-- <div class="custom-file">
                                <input type="file" wire:model="image" class="custom-file-input" id="categoryImage">
                                <label class="custom-file-label" for="categoryImage">Choose file</label>
                            </div> --}}
                            <input type="file" wire:model="image" id="categoryImage" class="form-control">
                            @error('image')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="isActive">Is Active</label>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" wire:model="is_active" class="custom-control-input" id="isActive">
                                <label class="custom-control-label" for="isActive"></label>
                            </div>
                        </div>

                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>

                </div>
                <!-- /.card -->
                </div>
              <!--/.col (left) -->
              <!-- right column -->
              <div class="col-md-6">

              </div>
              <!--/.col (right) -->
            </div>
            <!-- /.row -->
          </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
      </div>
</div>

