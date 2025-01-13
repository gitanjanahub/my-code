<?php

namespace App\Livewire\Admin;

use App\Models\AttributeName;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.adminpanel')]
#[Title('Attributes')]

class Attributes extends Component
{

    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $attributeIdToDelete = null;
    public $search = ''; // Add a public property for the search term
    public $selectedAttributes = []; // Array to hold selected attribute IDs
    public $selectAll = false; // Property for "Select All" checkbox
    public $showDeleteModal = false; // Control visibility of the single delete modal
    public $showMultipleDeleteModal = false; // Control visibility of the multiple delete modal

    protected $listeners = ['refreshComponent' => '$refresh'];

    // Method for single attribute delete confirmation
    public function confirmDelete($id)
    {
        $this->attributeIdToDelete = $id;
        $this->showDeleteModal = true;  // Show the individual delete modal
    }

    // Method to delete an attribute
    public function deleteAttribute()
    {
        if ($this->attributeIdToDelete) {
            $attribute = AttributeName::find($this->attributeIdToDelete);

            if ($attribute) {
                $attribute->delete(); // Use soft delete

                session()->flash('message', 'Attribute deleted successfully!');
                $this->resetPage();
            } else {
                session()->flash('error', 'Attribute not found!');
            }

            $this->attributeIdToDelete = null;  // Reset attribute ID after deletion
            $this->showDeleteModal = false;  // Hide the modal after deletion
        }
    }

    // Method to handle the "Select All" functionality
    public function updatedSelectAll($value)
    {
        if ($value) {
            // When "Select All" is checked, select all attribute IDs
            $this->selectedAttributes = AttributeName::pluck('id')->toArray();
        } else {
            // If "Select All" is unchecked, clear the selection
            $this->selectedAttributes = [];
        }
    }

    // Update the "Select All" checkbox based on individual selection
    public function updatedselectedAttributes($value)
    {
        $this->selectAll = count($this->selectedAttributes) === AttributeName::count();
    }

    // Method for multiple delete confirmation
    public function confirmMultipleDelete()
    {
        if (count($this->selectedAttributes)) {
            // Show the multiple delete modal if any attributes are selected
            $this->showMultipleDeleteModal = true;
        }
    }

    // // Method to delete selected attributes
    // public function deleteselectedAttributes()
    // {
    //     AttributeName::whereIn('id', $this->selectedAttributes)->delete();
    //     session()->flash('message', 'Selected attributes deleted successfully!');
    //     $this->selectedAttributes = []; // Clear selected attributes after deletion
    //     $this->showMultipleDeleteModal = false;  // Hide the modal after deletion
    // }

    // Method to delete selected attributes along with their related values
public function deleteselectedAttributes()
{
    // First, fetch the selected AttributeNames by their IDs
    $selectedAttributes = AttributeName::whereIn('id', $this->selectedAttributes)->get();

    // Loop through each selected AttributeName
    foreach ($selectedAttributes as $attributeName) {
        // Soft delete related AttributeValues
        $attributeName->values->each(function ($value) {
            $value->delete();  // Soft delete each related value
        });

        // Now soft delete the AttributeName itself
        $attributeName->delete();  // Soft delete the attribute
    }

    // Flash a success message after deletion
    session()->flash('message', 'Selected attributes and their values deleted successfully!');

    // Reset the selected attributes list and close the modal
    $this->selectedAttributes = []; // Clear selected attributes after deletion
    $this->showMultipleDeleteModal = false;  // Hide the modal after deletion
}


    // Render the view
    public function render()
    {
        $query = AttributeName::query();

        // Apply search functionality if search term is not empty
        if (!empty($this->search)) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        // Order by creation date
        $query->orderBy('created_at', 'desc');

        // Get paginated results
        $attributes = $query->paginate(5);

        return view('livewire.admin.attributes', [
            'attributes' => $attributes,
            'totalAttributesCount' => $attributes->total(),
        ]);
    }
}
