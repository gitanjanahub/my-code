<?php

namespace App\Livewire\Admin;

use App\Models\Banner;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.adminpanel')]
#[Title('View Banner')]


class BannerView extends Component
{

    public $banner;

    public $bannerIdToDelete = null;

    // Mount method to load the brand by ID
    public function mount($bannerId)
    {
        //dd($brandId);
        $this->banner = Banner::findOrFail($bannerId);
    }

    public function confirmDelete($id)
    {
        $this->bannerIdToDelete = $id;
    }

    public function deleteBanner()
    {
        if ($this->bannerIdToDelete) {
            $banner = Banner::find($this->bannerIdToDelete);

            if ($banner) {
                $banner->delete(); // Use soft delete

                session()->flash('message', 'Banner deleted successfully!');

                return redirect()->route('admin.banners');

                //$this->resetPage();
            } else {
                session()->flash('error', 'Banner not found!');
            }

            $this->bannerIdToDelete = null;
        }
    }

    public function render()
    {
        return view('livewire.admin.banner-view');
    }
}
