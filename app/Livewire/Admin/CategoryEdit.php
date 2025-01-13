<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

#[Layout('components.layouts.adminpanel')]
#[Title('Edit Category')]
class CategoryEdit extends Component
{
    use WithFileUploads;

    public $category; // Category model
    public $name;
    public $slug;
    public $image;
    public $is_active;

    // Load category data on mount
    public function mount($id)
    {
        // Retrieve the category by id
        $this->category = Category::findOrFail($id);

        // Initialize the form fields with existing data
        $this->name = $this->category->name;
        $this->slug = $this->category->slug;
        $this->image = null; // You can optionally handle the existing image differently if needed
        $this->is_active = $this->category->is_active;
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
            'slug' => 'required|max:255|unique:categories,slug,' . $this->category->id,
            'image' => 'nullable|image|max:1024', // Ensure the file is an image and has a max size of 1MB
            'is_active' => 'required|boolean',
        ]);

        // If an image is uploaded, store it and update the image path
        if ($this->image) {
            $imagePath = $this->image->store('images/categories', 'public');
        } else {
            $imagePath = $this->category->image; // Keep existing image if no new image is uploaded
        }

        // Update the category in the database
        $updated =  $this->category->update([
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
            session()->flash('message', 'Category updated successfully!');

            // Redirect to categories list
            return redirect()->route('admin.categories');
        } else {
            // If the update failed, flash an error message
            session()->flash('error', 'There was an issue updating the category. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.admin.category-edit');
    }
}

