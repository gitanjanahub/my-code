<div>
    <div class="content-wrapper" style="margin-left: 5% !important;">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1>Attributes</h1>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <a wire:navigate href="{{ route('admin.attributes') }} " class="btn btn-primary rounded-pill">Back to Attributes</a>
                    <a wire:navigate href="{{ route('admin.attribute-create') }}" class="btn btn-success rounded-pill">Create New</a>
                </ol>
              </div>
            </div>
          </div><!-- /.container-fluid -->

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
                    <h3 class="card-title">Create Attribute</h3>
                  </div>
                  <!-- /.card-header -->
                  <!-- form start -->
                  <form wire:submit.prevent="save">
                    <div class="card-body">

                        <div class="form-group">
                            <label for="attributeName">Attribute Name</label>
                            <input type="text" id="attributeName" class="form-control" wire:model="attributeName" placeholder="Enter attribute name">
                            @error('attributeName') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label for="values">Attribute Values</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" wire:model.defer="newValue" placeholder="Enter a value">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-success" wire:click="addValue">Add</button>
                                </div>
                            </div>
                            @error('values') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        @foreach($values as $index => $value)
                        <div class="d-flex align-items-center mb-2">
                            <input type="text" class="form-control" value="{{ $value }}" disabled>
                            <button type="button" class="btn btn-danger ml-2" wire:click="removeValue({{ $index }})">Remove</button>
                        </div>
                        @endforeach

                        {{-- @error('values.*') <span class="text-red-500">{{ $message }}</span> @enderror --}}


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

