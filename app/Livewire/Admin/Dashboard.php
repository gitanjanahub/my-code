<?php

namespace App\Livewire\Admin;

use App\Models\AttributeName;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.adminpanel')]

#[Title('Dashboard')]

class Dashboard extends Component
{
    public function render()
    {
        // Count categories where is_deleted = 0
        $categoriesCount = Category::where('is_deleted', 0)->count();

        // Count brands excluding soft-deleted records
        $brandsCount = Brand::withoutTrashed()->count();

        // Count attribute names excluding soft-deleted records
        $attributeNamesCount = AttributeName::withoutTrashed()->count();

        // Count products excluding soft-deleted records
        $productsCount = Product::withoutTrashed()->count();

        // Count orders excluding soft-deleted records
        $ordersCount = Order::withoutTrashed()->count();

        // Count users excluding soft-deleted records
        $usersCount = User::withoutTrashed()->count();

        return view('livewire.admin.dashboard',[
            'categoriesCount' => $categoriesCount,
            'brandsCount' => $brandsCount,
            'attributeNamesCount' => $attributeNamesCount,
            'productsCount' => $productsCount,
            'ordersCount' => $ordersCount,
            'usersCount' => $usersCount,
        ]);
    }
}
