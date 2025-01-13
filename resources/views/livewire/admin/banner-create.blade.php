<div>
    <div class="content-wrapper" style="margin-left: 5% !important;">
        <!-- Content Header -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-3 align-items-center">
                    <div class="col-sm-6">
                        <h1 class="text-primary">Create Banner</h1>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a wire:navigate href="{{ route('admin.banners') }}" class="btn btn-secondary btn-sm rounded-pill shadow-sm">
                            <i class="fas fa-arrow-left"></i> Back to Banners
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main Content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <!-- Form Card -->
                        <div class="card card-primary">
                            <div class="card-header">
                              <h3 class="card-title">Create Brand</h3>
                            </div>
                            <form wire:submit.prevent="save">
                                <div class="card-body ">
                                    <div class="row">
                                        <!-- Column 1 -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="title">Title</label>
                                                <input type="text" wire:model="title" id="title" class="form-control shadow-sm" placeholder="Enter banner title">
                                                @error('title') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>

                                            <div class="form-group">
                                                <label for="link">Link</label>
                                                <input type="text" wire:model="link" id="link" class="form-control shadow-sm" placeholder="Enter link (optional)">
                                                @error('link') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>

                                            <div class="form-group">
                                                <label for="image_path">Image</label>
                                                <input type="file" wire:model="image_path" id="image_path" class="form-control shadow-sm">
                                                @if ($image_path)
                                                    <img src="{{ $image_path->temporaryUrl() }}" class="img-thumbnail mt-3" style="max-width: 150px;">
                                                @endif
                                                @error('image_path') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>


                                        </div>

                                        <!-- Column 2 -->
                                        <div class="col-md-6">

                                            <div class="form-group">
                                                <label for="section">Section</label>
                                                <input type="text" wire:model="section" id="section" class="form-control shadow-sm" placeholder="Specify section (e.g., 'carousel', 'offer')">
                                                @error('section') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>


                                            <div class="form-group">
                                                <label for="size">Size</label>
                                                <select wire:model="size" id="size" class="form-control shadow-sm">
                                                    <option value="" >Select size</option>
                                                    <option value="small">Small</option>
                                                    <option value="medium">Medium</option>
                                                    <option value="large">Large</option>
                                                </select>
                                                @error('size') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>

                                            <div class="form-group">
                                                <label for="order">Order</label>
                                                <input type="number" wire:model="order" id="order" class="form-control shadow-sm" placeholder="Set display order">
                                                @error('order') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer bg-light">
                                    <button type="submit" class="btn btn-primary shadow-sm rounded-pill px-4">
                                        <i class="fas fa-save"></i> Submit
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
