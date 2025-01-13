<?php

namespace App\Livewire\Admin;

use App\Models\Attribute;
use App\Models\AttributeName;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.adminpanel')]
#[Title('View Attribute')]

class AttributeView extends Component
{

    public $attribute;
    public $attributeIdToDelete = null;

    // Mount method to load the attribute by ID
    public function mount($attributeId)
    {
        $this->attribute = AttributeName::with('values')->findOrFail($attributeId);
    }

    // Method to confirm deletion
    public function confirmDelete($id)
    {
        $this->attributeIdToDelete = $id;
    }

    // Method to delete an attribute along with its related values
    public function deleteAttribute()
    {
        if ($this->attributeIdToDelete) {
            $attribute = AttributeName::with('values')->find($this->attributeIdToDelete);

            if ($attribute) {
                // Soft delete attribute and its related values
                $attribute->values()->delete(); // Soft delete related values
                $attribute->delete(); // Soft delete the attribute itself

                session()->flash('message', 'Attribute and its values deleted successfully!');
                return redirect()->route('admin.attributes');
            } else {
                session()->flash('error', 'Attribute not found!');
            }

            $this->attributeIdToDelete = null;
        }
    }


    public function render()
    {
        return view('livewire.admin.attribute-view');
    }
}
