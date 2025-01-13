<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;


use Illuminate\Support\Str;



#[Layout('components.layouts.adminpanel')]

#[Title('Create Category')]


class CategoryCreate extends Component
{

    use WithFileUploads;

    public $name;
    public $slug;
    public $image;
    public $is_active = 0; // Default to 1 (active)

    // Automatically update slug when name changes
    public function updated($propertyName)
    {
        if ($propertyName === 'name') {
            $this->slug = Str::slug($this->name);
        }
    }

    public function save(){

        // Validate the input fields, including the image
        $this->validate([
            'name' => 'required|min:3|max:255',
            'slug' => 'required|max:255|unique:categories,slug',
            'image' => 'required|image|max:1024', // Ensure the file is an image and has a max size of 1MB
            'is_active' => 'required|boolean',
        ]);


        // Store the image in the 'public' disk, inside 'images/categories'
        $imagePath = $this->image->store('images/categories', 'public');

        // Save the category to the database
        $saved = Category::create([
            'name' => $this->name,
            'slug' => $this->slug,
            'image' => $imagePath, // Store the image path in the database
            'is_active' => $this->is_active ? 1 : 0, // Convert true/false to 1/0
        ]);

        if($saved){

            // Reset form fields after successful submission
            $this->reset(['name', 'slug', 'image', 'is_active']);

            // Send success message and redirect
            session()->flash('message', 'Category created successfully!');
            return redirect()->route('admin.categories'); // Ensure this route exists

        }else{

            // If the save failed, flash an error message
            session()->flash('error', 'There was an issue saving the category. Please try again.');

        }

    }



    public function render()
    {
        return view('livewire.admin.category-create');
    }
}
