<?php

namespace App\Livewire;

use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Home Page')]

class HomePage extends Component
{
    public function render()
    {

        // Fetch active and non-deleted categories
        // $categories = Category::query()
        //     ->where('is_active', 1)
        //     ->where('is_deleted', 0)
        //     ->latest()
        //     ->get();

        // Fetch active brands (soft deletes handled automatically)
        // $brands = Brand::query()
        //     ->where('is_active', 1)
        //     ->latest()
        //     ->get();

        // Fetch featured and active products
        // $productsFeatured = Product::query()
        //     ->where('is_active', 1)
        //     ->where('is_featured', 1)
        //     ->latest()
        //     ->get();

        // Fetch recent active products
        // $productsRecent = Product::query()
        //     ->where('is_active', 1)
        //     ->latest()
        //     ->get();

        // Fetch active and non-deleted categories, selecting specific columns
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

    // Fetch active brands, selecting specific columns
    $brands = Brand::query()
        ->select('id', 'name', 'slug','image')
        ->active()
        ->latest()
        ->get();

    // Fetch featured and active products, selecting specific columns
    $productsFeatured = Product::query()
        ->select('id', 'name', 'slug', 'thumb_image', 'price', 'offer_price')
        ->active()
        ->featured()
        ->latest()
        ->get();

    // Fetch the most recent active products, selecting specific columns
    $productsRecent = Product::query()
        ->select('id', 'name', 'slug', 'thumb_image', 'price', 'offer_price')
        ->active()
        ->latest()
        ->get();

    $carouselBanners = Banner::select('id', 'title', 'image_path', 'link')
        ->where('section', 'carousel')
        ->active()//scope
        ->orderBy('order')
        ->get();

    $offerBanners = Banner::select('id', 'title', 'image_path', 'link')
        ->where('section', 'offer')
        ->active()//scope
        ->orderBy('order')
        ->get();


        return view('livewire.home-page',
            compact(
              'categories',
              'brands',
              'productsFeatured',
              'productsRecent',
              'carouselBanners',
              'offerBanners'
            ));
    }
}
