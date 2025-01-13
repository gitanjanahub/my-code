<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.adminpanel')]
#[Title('Categories')]
class Categories extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $categoryIdToDelete = null;
    public $search; // Add a public property for the search term

    // Listener to refresh the component
    protected $listeners = ['refreshComponent' => '$refresh'];

    // Reset pagination when search is updated
    // public function updatingSearch()
    // {
    //     $this->resetPage();
    // }

    // Toggle the active status of a category
    public function toggleActive($categoryId, $isActive)
    {
        $category = Category::find($categoryId);

        if ($category) {
            $category->is_active = $isActive ? 1 : 0;
            $category->save();
            session()->flash('message', 'Status Changed successfully!');
        }
    }

    // Set the category ID to be deleted
    public function confirmDelete($id)
    {
        $this->categoryIdToDelete = $id;
    }

    // Delete category (soft delete)
    // public function deleteCategory()
    // {
    //     if ($this->categoryIdToDelete) {
    //         $category = Category::find($this->categoryIdToDelete);

    //         if ($category) {
    //             // Soft delete by setting is_deleted to 1
    //             $category->update(['is_deleted' => 1]);
    //             session()->flash('message', 'Category soft deleted successfully!');

    //             // Reset page after deletion for better UX
    //             $this->resetPage();
    //         } else {
    //             session()->flash('error', 'Category not found!');
    //         }

    //         // Reset category ID after deletion
    //         $this->categoryIdToDelete = null;
    //     }
    // }

    public function deleteCategory()
    {
        if ($this->categoryIdToDelete) {
            $category = Category::withCount('products')->find($this->categoryIdToDelete);

            if ($category) {
                if ($category->products_count > 0) {
                    session()->flash('error', 'Category cannot be deleted as it has associated products!');
                } else {
                    $category->update(['is_deleted' => 1]); // Soft delete
                    session()->flash('message', 'Category soft deleted successfully!');
                    $this->resetPage();
                }
            } else {
                session()->flash('error', 'Category not found!');
            }

            $this->categoryIdToDelete = null;
        }
    }


    public function render()
    {
        // Log the search term to the Laravel log file
    //logger('Search Term:', [$this->search]);

        // Fetch categories with search functionality
        $categories = Category::where('is_deleted', 0)
            ->where('name', 'like', '%' . $this->search . '%')
            ->withCount('products') // Fetch product count using the relationship and not checking active or not
            ->paginate(5);

        return view('livewire.admin.categories', [
            'categories' => $categories,
            'totalCategoriesCount' => $categories->total() // Use pagination total count
        ]);
    }
}
