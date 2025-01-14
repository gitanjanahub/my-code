<div>
    <div class="content-wrapper" style="margin-left: 5% !important;">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1>Contact Details</h1>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <a wire:navigate href="{{ route('admin.dashboard') }} " class="btn btn-primary rounded-pill">Back to Home</a>

                </ol>
              </div>
            </div>
          </div><!-- /.container-fluid -->

          @if (session('success'))
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
              <!-- left column -->
              <div class="col-md-12">
                <!-- jquery validation -->
                <div class="card card-primary">
                  <div class="card-header">
                    <h3 class="card-title">Contact Details</h3>
                  </div>
                  <!-- /.card-header -->



                  <!-- form start -->
                  <form wire:submit.prevent="save" enctype="multipart/form-data">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="categoryName">Name</label>
                            <input type="text" wire:model.lazy="name" class="form-control" id="name" placeholder="Company Name">
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="categorySlug">Email</label>
                            <input type="email" wire:model="email" class="form-control" id="email" placeholder="Email">
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="categorySlug">Phone</label>
                            <input type="text" wire:model="phone" class="form-control" id="phone" placeholder="Phone">
                            @error('phone')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="categorySlug">Address</label>
                            <textarea wire:model="address" class="form-control" id="phone" placeholder="Address"></textarea>
                            @error('address')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="categoryImage">Logo</label>
                            {{-- <div class="custom-file">
                                <input type="file" wire:model="image" class="custom-file-input" id="categoryImage">
                                <label class="custom-file-label" for="categoryImage">Choose file</label>
                            </div> --}}
                            <input type="file" wire:model="logo" id="logo" class="form-control">

                            @error('logo')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- @if ($uploadedLogo)
                                        <div class="form-group">
                                            <label>Current Image</label>
                                            <img src="{{ asset('storage/' . $uploadedLogo) }}" alt="Current Logo" >
                                        </div>
                        @endif --}}

                        @if ($previewLogo)
                            <div class="form-group mt-2">
                                <label>Preview Image</label>
                                <img src="{{ $previewLogo }}" alt="Preview Logo" class="img-fluid" />
                            </div>
                        @elseif ($uploadedLogo)
                            <div class="form-group mt-2">
                                <label>Current Image</label>
                                <img src="{{ asset('storage/' . $uploadedLogo) }}" alt="Current Logo" class="img-fluid" />
                            </div>
                        @endif



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

