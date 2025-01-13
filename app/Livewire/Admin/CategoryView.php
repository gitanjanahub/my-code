<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.adminpanel')]
#[Title('View Category')]

class CategoryView extends Component
{

    public $category;

    public $categoryIdToDelete = null;

    // Mount method to load the category by ID
    public function mount($categoryId)
    {
        $this->category = Category::findOrFail($categoryId);
    }

    public function confirmDelete($id)
    {
        $this->categoryIdToDelete = $id;
    }

    public function deleteCategory()
    {
        if ($this->categoryIdToDelete) {
            $category = Category::find($this->categoryIdToDelete);

            if ($category) {
                // Soft delete by updating the is_deleted column
                $category->update(['is_deleted' => 1]);

                session()->flash('message', 'Category deleted successfully!');

                // Redirect back to the categories list page after deletion
                return redirect()->route('admin.categories');
            } else {
                session()->flash('error', 'Category not found!');
            }

            // Reset the categoryIdToDelete after the operation
            $this->categoryIdToDelete = null;
        }
    }


    public function render()
    {
        return view('livewire.admin.category-view');
    }
}
