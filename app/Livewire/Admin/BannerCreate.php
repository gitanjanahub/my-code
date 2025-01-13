<?php

namespace App\Livewire\Admin;

use App\Models\Banner;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.adminpanel')]

#[Title('Create Banner')]

class BannerCreate extends Component
{

    use WithFileUploads;

    public $title, $image_path , $link, $section, $size, $order;

    protected $rules = [
        'title' => 'nullable|string|max:255',
        'image_path' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        'link' => 'nullable|url',
        'section' => 'required|string',
        'size' => 'required|in:small,medium,large',
        'order' => 'required|integer',
    ];

    public function save()
    {
        //dd('ss');
       $this->validate();


        $imagePath = $this->image_path->store('images/banners', 'public');

         Banner::create([
            'title' => $this->title,
            'image_path' => $imagePath,
            'link' => $this->link,
            'section' => $this->section,
            'size' => $this->size,
            'order' => $this->order,
        ]);
       // dd($ss);



        session()->flash('message', 'Banner created successfully!');
        return redirect()->route('admin.banners');
    }


    public function render()
    {
        return view('livewire.admin.banner-create');
    }
}
