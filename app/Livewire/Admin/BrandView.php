<?php

namespace App\Livewire\Admin;

use App\Models\Brand;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.adminpanel')]
#[Title('View Brand')]

class BrandView extends Component
{

    public $brand;

    public $brandIdToDelete = null;

    // Mount method to load the brand by ID
    public function mount($brandId)
    {
        //dd($brandId);
        $this->brand = Brand::findOrFail($brandId);
    }

    public function confirmDelete($id)
    {
        $this->brandIdToDelete = $id;
    }

    public function deleteBrand()
    {
        if ($this->brandIdToDelete) {
            $brand = Brand::find($this->brandIdToDelete);

            if ($brand) {
                $brand->delete(); // Use soft delete

                session()->flash('message', 'Brand deleted successfully!');

                return redirect()->route('admin.brands');

                //$this->resetPage();
            } else {
                session()->flash('error', 'Brand not found!');
            }

            $this->brandIdToDelete = null;
        }
    }



    public function render()
    {
        return view('livewire.admin.brand-view');
    }
}
