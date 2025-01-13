<div>
    <div class="content-wrapper" style="margin-left: 5% !important;">

        <section class="content-header">
            <div class="container-fluid">
              <div class="row mb-2">
                <div class="col-sm-6">
                  <h1>Change Password</h1>
                </div>
                <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                      <a wire:navigate href="{{ route('admin.dashboard') }} " class="btn btn-primary rounded-pill">Back to Dashboard</a>

                  </ol>
                </div>
              </div>
            </div><!-- /.container-fluid -->

             <!-- Success Message -->
             @if (session()->has('success'))
             <div class="alert alert-success">
                 {{ session('success') }}
             </div>
         @endif

         <!-- Error Message -->
         @if (session()->has('error'))
             <div class="alert alert-danger">
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
                      <h3 class="card-title">Change Password</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form wire:submit.prevent="updatePassword">
                        @csrf
                        <div class="card-body">
                        <!-- Current Password -->
                        <div class="form-group">
                            <label for="current_password">Current Password</label>
                            <input type="password" id="current_password" class="form-control" wire:model.defer="current_password">
                            @error('current_password') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- New Password -->
                        <div class="form-group">
                            <label for="new_password">New Password</label>
                            <input type="password" id="new_password" class="form-control" wire:model.defer="new_password">
                            @error('new_password') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- Confirm New Password -->
                        <div class="form-group">
                            <label for="new_password_confirmation">Confirm New Password</label>
                            <input type="password" id="new_password_confirmation" class="form-control" wire:model.defer="new_password_confirmation">
                            @error('new_password_confirmation') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Change Password</button>
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
