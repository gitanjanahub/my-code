<div>
    <div class="content-wrapper" style="margin-left: 5% !important;">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Edit Attribute</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <a wire:navigate href="{{ route('admin.attributes') }}" class="btn btn-primary rounded-pill">Back to Attributes</a>
                            <a wire:navigate href="{{ route('admin.attribute-create') }}" class="btn btn-success rounded-pill">Create New</a>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Edit Attribute</h3>
                            </div>

                            <!-- Form Start -->
                            <form wire:submit.prevent="save">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="attributeName">Attribute Name</label>
                                        <input type="text" class="form-control" wire:model="attributeName" placeholder="Enter attribute name">
                                        @error('attributeName') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>Attribute Values</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" wire:model.defer="newValue" placeholder="Add new value">
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-success" wire:click="addValue">Add</button>
                                            </div>
                                        </div>
                                    </div>

                                    @foreach($values as $key => $value)
                                        <div class="d-flex align-items-center mb-2">
                                            <input type="text" class="form-control" wire:model="values.{{ $key }}">
                                            <button type="button" class="btn btn-danger ml-2" wire:click="removeValue('{{ $key }}')">Remove</button>
                                        </div>
                                    @endforeach

                                    @error('values') <span class="text-danger">{{ $message }}</span> @enderror

                                </div>



                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
