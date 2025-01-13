<?php

namespace App\Livewire;

use App\Models\Category;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Categories Page')]

class CategoriesPage extends Component
{
    public function render()
    {
        $categories = Category::query()
                    ->select('id', 'name', 'slug', 'image')
                    ->active()
                    ->notDeleted()
                    ->latest()
                    //->withCount('products')
                    ->withCount(['products' => function($query) {
                        $query->where('is_active', 1); // Example condition for active products
                    }])
                    ->get();

        return view('livewire.categories-page',compact('categories'));
    }
}
