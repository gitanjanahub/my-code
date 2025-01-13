<?php

namespace App\Livewire\Admin;

use App\Models\Brand;
use Livewire\Component;

use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithFileUploads;

#[Layout('components.layouts.adminpanel')]
#[Title('Edit Brand')]

class BrandEdit extends Component
{

    use WithFileUploads;

    public $brand; // brand model
    public $name;
    public $slug;
    public $image;
    public $is_active;

    // Load brand data on mount
    public function mount($id)
    {
        // Retrieve the brand by id
        $this->brand = Brand::findOrFail($id);

        // Initialize the form fields with existing data
        $this->name = $this->brand->name;
        $this->slug = $this->brand->slug;
        $this->image = null; // You can optionally handle the existing image differently if needed
        $this->is_active = $this->brand->is_active;
    }

    // Automatically update slug when name changes
    public function updated($propertyName)
    {
        if ($propertyName === 'name') {
            $this->slug = Str::slug($this->name);
        }
    }

    public function save()
    {
        // Validate the input fields, including the image
        $this->validate([
            'name' => 'required|min:3|max:255',
            'slug' => 'required|max:255|unique:brands,slug,' . $this->brand->id,
            'image' => 'nullable|image|max:1024', // Ensure the file is an image and has a max size of 1MB
            'is_active' => 'required|boolean',
        ]);

        // If an image is uploaded, store it and update the image path
        if ($this->image) {
            $imagePath = $this->image->store('images/brands', 'public');
        } else {
            $imagePath = $this->brand->image; // Keep existing image if no new image is uploaded
        }

        // Update the brand in the database
        $updated =  $this->brand->update([
                    'name' => $this->name,
                    'slug' => $this->slug,
                    'image' => $imagePath, // Update the image path in the database
                    'is_active' => $this->is_active ? 1 : 0, // Convert true/false to 1/0
                ]);

        // Check if the update was successful
        if ($updated) {
            // Successfully updated, reset the form fields
            $this->reset(['name', 'slug', 'image', 'is_active']);

            // Flash success message
            session()->flash('message', 'Brand updated successfully!');

            // Redirect to brands list
            return redirect()->route('admin.brands');
        } else {
            // If the update failed, flash an error message
            session()->flash('error', 'There was an issue updating the brand. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.admin.brand-edit');
    }
}
