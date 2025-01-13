<div class="content-wrapper" style="margin-left: 5% !important;">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-3 align-items-center">
                <div class="col-sm-6">
                    <h1 class="text-primary">Edit Stock for {{ $product->name }}</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a wire:navigate href="{{ route('admin.stocks') }}" class="btn btn-secondary btn-sm rounded-pill shadow-sm">
                        <i class="fas fa-arrow-left"></i> Back to Stocks
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <form wire:submit.prevent="save">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Edit Stock Quantities</h3>
                    </div>
                    <div class="card-body">
                        <!-- Iterate over rows -->
                        @foreach ($rows as $index => $row)
                            <div class="row align-items-center mb-2">
                                <!-- Dropdowns for productAttributes -->
                                @foreach ($productAttributes as $attributeName => $values)
                                    <div class="col-md-3">
                                        <label>{{ $attributeName }}</label>
                                        <select
    wire:model="rows.{{ $index }}.selectedAttributes.{{ $values[0]['attribute_id'] }}"
    class="form-control">
    <option value="">Select {{ $attributeName }}</option>
    @foreach ($values as $value)
        <option value="{{ $value['value_id'] }}"
            @if (isset($row['selectedAttributes'][$value['attribute_id']]) &&
                $row['selectedAttributes'][$value['attribute_id']] == $value['value_id'])
                selected
            @endif>
            {{ $value['value_name'] }}
        </option>
    @endforeach
</select>




                                    </div>
                                @endforeach


                                <!-- Quantity Input -->
                                <div class="col-md-2">
                                    <label>Quantity</label>
                                    <input
                                        type="number"
                                        min="0"
                                        wire:model="rows.{{ $index }}.quantity"
                                        class="form-control"
                                        placeholder="Enter Quantity">
                                    @error("rows.{$index}.quantity")
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Remove Button -->
                                <div class="col-md-1">
                                    <label>&nbsp;</label>
                                    <button type="button" wire:click="removeRow({{ $index }})" class="btn btn-danger btn-sm">Remove</button>
                                </div>
                            </div>
                        @endforeach

                        <!-- Add More Button -->
                        <button type="button" wire:click="addRow" class="btn btn-primary btn-sm mt-3">Add Stock Entry</button>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-success">Save Changes</button>
                        <a href="{{ route('admin.stocks') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>
