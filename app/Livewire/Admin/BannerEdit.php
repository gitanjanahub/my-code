<?php

namespace App\Livewire\Admin;

use App\Models\Banner;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

#[Layout('components.layouts.adminpanel')]
#[Title('Edit Banner')]

class BannerEdit extends Component
{

    use WithFileUploads;

    public $banner, $title, $image_path, $link, $section, $size, $order;

    protected $rules = [
        'title' => 'nullable|string|max:255',
        'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'link' => 'nullable|url',
        'section' => 'required|string',
        'size' => 'required|in:small,medium,large',
        'order' => 'required|integer',
    ];

    // Load banner data on mount
    public function mount($id)
    {
        // Retrieve the banner by id
        $this->banner = Banner::findOrFail($id);

        // Initialize the form fields with existing data
        $this->title = $this->banner->title;
        $this->image_path = null; // You can optionally handle the existing image differently if needed
        $this->link = $this->banner->link;
        $this->section = $this->banner->section;
        $this->size = $this->banner->size;
        $this->order = $this->banner->order;
    }

    public function save()
    {
        $this->validate();

        // If an image is uploaded, store it and update the image path
        if ($this->image_path) {
            $imagePath = $this->image_path->store('images/banners', 'public');
        } else {
            $imagePath = $this->banner->image_path; // Keep existing image if no new image is uploaded
        }

        $updated =  $this->banner->update([
            'title' => $this->title,
            'image_path' => $imagePath,
            'link' => $this->link,
            'section' => $this->section,
            'size' => $this->size,
            'order' => $this->order,
        ]);

        // Check if the update was successful
        if ($updated) {
            // Successfully updated, reset the form fields
            $this->reset(['title', 'image_path', 'link', 'section', 'size', 'order']);

            // Flash success message
            session()->flash('message', 'Banner updated successfully!');

            // Redirect to brands list
            return redirect()->route('admin.banners');
        } else {
            // If the update failed, flash an error message
            session()->flash('error', 'There was an issue updating the banner. Please try again.');
        }
    }


    public function render()
    {
        return view('livewire.admin.banner-edit');
    }
}
