<?php

namespace App\Livewire\Admin;

use App\Models\Banner;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.adminpanel')]

#[Title('Banners')]

class Banners extends Component
{

    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $bannerIdToDelete = null;

    public $search; // Add a public property for the search term

    public $selectedBanners = []; // Array to hold selected banner IDs

    public $selectAll = false; // Property for "Select All" checkbox

    public $showDeleteModal = false; // Control visibility of the single delete modal

    public $showMultipleDeleteModal = false; // Control visibility of the multiple delete modal

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function toggleActive($bannerId, $isActive)
    {
        $banner = Banner::find($bannerId);

        if ($banner) {
            // Set is_active based on checkbox state
            $banner->is_active = $isActive ? 1 : 0;
            $banner->save();
            session()->flash('message', 'Status Changed successfully!');
        }
    }


    public function confirmDelete($id)
    {


        // Set banner ID for individual deletion and show modal
        $this->bannerIdToDelete = $id;
        $this->showDeleteModal = true;  // Show the individual delete modal
    }

    public function deleteBanner()
    {
        if ($this->bannerIdToDelete) {
            $banner = Banner::find($this->bannerIdToDelete);

            if ($banner) {
                $banner->delete(); // Use soft delete

                session()->flash('message', 'Banner deleted successfully!');

                $this->resetPage();
            } else {
                session()->flash('error', 'Banner not found!');
            }

            $this->bannerIdToDelete = null;  // Reset banner ID after deletion
            $this->showDeleteModal = false;  // Hide the modal after deletion
        }
    }


    public function updatedSelectAll($value)
    {

        if ($value) {
            // When "Select All" is checked, select all banner IDs
            $this->selectedBanners = Banner::pluck('id')->toArray();
        } else {
            // If "Select All" is unchecked, clear the selection
            $this->selectedBanners = [];
        }
    }

    public function updatedselectedBanners($value)
    {
        // When individual checkboxes are clicked, this method ensures
        // "Select All" is checked only if all items are selected
        $this->selectAll = count($this->selectedBanners) === Banner::count();

    }

    public function confirmMultipleDelete()
    {

        // Show the multiple delete modal if any banners are selected
        if (count($this->selectedBanners)) {
            $this->showMultipleDeleteModal = true;
        }
    }

    public function deleteSelectedBanners()
    {
        // Perform delete for all selected banners
        Banner::whereIn('id', $this->selectedBanners)->delete();
        session()->flash('message', 'Selected Banners deleted successfully!');
        $this->selectedBanners = []; // Clear selected banners after deletion
        $this->showMultipleDeleteModal = false;  // Hide the modal after deletion
    }

    public function render()
    {
        $query = Banner::query();

        // Only apply search if $this->search is not empty
        if (!empty($this->search)) {
            $query->where('title', 'like', '%' . $this->search . '%');
        }
        $query->orderBy('created_at', 'desc');

        $banners = $query->paginate(5);


        return view('livewire.admin.banners',[
            'banners' => $banners,
            'totalBannersCount' => $banners->total(),
        ]);
    }
}
