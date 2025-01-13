<?php

namespace App\Livewire\Admin;

use App\Models\Brand;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.adminpanel')]
#[Title('Brands')]

class Brands extends Component
{

    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $brandIdToDelete = null;

    public $search; // Add a public property for the search term

    public $selectedBrands = []; // Array to hold selected brand IDs

    public $selectAll = false; // Property for "Select All" checkbox

    public $showDeleteModal = false; // Control visibility of the single delete modal

    public $showMultipleDeleteModal = false; // Control visibility of the multiple delete modal

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function toggleActive($brandId, $isActive)
    {
        $brand = Brand::find($brandId);

        if ($brand) {
            // Set is_active based on checkbox state
            $brand->is_active = $isActive ? 1 : 0;
            $brand->save();
            session()->flash('message', 'Status Changed successfully!');
        }
    }


    public function confirmDelete($id)
    {
        //$this->brandIdToDelete = $id;

        // Set brand ID for individual deletion and show modal
        $this->brandIdToDelete = $id;
        $this->showDeleteModal = true;  // Show the individual delete modal
    }

    // public function deleteBrand()
    // {
    //     if ($this->brandIdToDelete) {
    //         $brand = Brand::find($this->brandIdToDelete);

    //         if ($brand) {
    //             $brand->delete(); // Use soft delete

    //             session()->flash('message', 'Brand deleted successfully!');

    //             $this->resetPage();
    //         } else {
    //             session()->flash('error', 'Brand not found!');
    //         }

    //         $this->brandIdToDelete = null;  // Reset brand ID after deletion
    //         $this->showDeleteModal = false;  // Hide the modal after deletion
    //     }
    // }
    public function deleteBrand()
    {
        if ($this->brandIdToDelete) {
            $brand = Brand::withCount('products')->find($this->brandIdToDelete);

            if ($brand) {
                if ($brand->products_count > 0) {
                    session()->flash('error', 'Brand cannot be deleted as it has associated products!');
                } else {
                    $brand->delete(); // Soft delete the brand
                    session()->flash('message', 'Brand deleted successfully!');
                }

                $this->resetPage();  // Reset pagination after deletion
            } else {
                session()->flash('error', 'Brand not found!');
            }

            $this->brandIdToDelete = null;  // Reset brand ID after deletion
            $this->showDeleteModal = false;  // Hide the modal after deletion
        }
    }



    public function updatedSelectAll($value)
    {
        // if ($value) {
        //     $this->selectedBrands = Brand::pluck('id')->toArray();
        // } else {
        //     $this->selectedBrands = [];
        // }
        //$this->selectedBrands = $value ? Brand::pluck('id')->toArray() : [];
        if ($value) {
            // When "Select All" is checked, select all brand IDs
            $this->selectedBrands = Brand::pluck('id')->toArray();
        } else {
            // If "Select All" is unchecked, clear the selection
            $this->selectedBrands = [];
        }
    }

    public function updatedSelectedBrands($value)
    {
        // When individual checkboxes are clicked, this method ensures
        // "Select All" is checked only if all items are selected
        $this->selectAll = count($this->selectedBrands) === Brand::count();

    }

    public function confirmMultipleDelete()
    {

        // Show the multiple delete modal if any brands are selected
        if (count($this->selectedBrands)) {
            $this->showMultipleDeleteModal = true;
        }
    }

    // public function deleteSelectedBrands()
    // {
    //     // Perform delete for all selected brands
    //     Brand::whereIn('id', $this->selectedBrands)->delete();
    //     session()->flash('message', 'Selected brands deleted successfully!');
    //     $this->selectedBrands = []; // Clear selected brands after deletion
    //     $this->showMultipleDeleteModal = false;  // Hide the modal after deletion
    // }
    public function deleteSelectedBrands()
    {
        $brands = Brand::withCount('products')->whereIn('id', $this->selectedBrands)->get();

        // Check if any brand has associated products
        $brandsWithProducts = $brands->filter(function ($brand) {
            return $brand->products_count > 0;
        });

        if ($brandsWithProducts->count() > 0) {
            session()->flash('error', 'Some brands cannot be deleted because they have associated products!');
        } else {
            // Proceed to delete the selected brands
            Brand::whereIn('id', $this->selectedBrands)->delete();
            session()->flash('message', 'Selected brands deleted successfully!');
        }

        // Reset selected brands and close modal
        $this->selectedBrands = [];
        $this->showMultipleDeleteModal = false;
    }





    public function render()
    {
        $query = Brand::query();

        // Only apply search if $this->search is not empty
        if (!empty($this->search)) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }
        $query->withCount('products');
        $query->orderBy('created_at', 'desc');

        $brands = $query->paginate(5);

        return view('livewire.admin.brands', [
            'brands' => $brands,
            'totalBrandsCount' => $brands->total(),
        ]);
    }

}
