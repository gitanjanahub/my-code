<?php

namespace App\Livewire\Admin;

use App\Models\AttributeName;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttributeValue;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.adminpanel')]

#[Title('Edit Product')]

class ProductEdit extends Component
{
    use WithFileUploads;

    public $product; // The product being edited
    public $categories; // All categories
    public $brands; // All brands
    public $attributeNames; // All attribute names
    public $name;
    public $slug;
    public $category_id;
    public $brand_id;
    public $price;
    public $offer_price;
    public $description;
    public $short_description;
    public $is_active;
    public $is_featured;
    public $in_stock;
    public $on_sale;
    public $thumb_image;
    public $images = [];
    public $selectedAttributes = []; // The selected attributes for the product
    public $existingImages = []; // Store paths for existing images to display them in the form
    public $image_paths = [];
    public $newImages = [];



// public function mount($id)
// {
//     // Fetch product by ID and its associated data
//     $this->product = Product::with('attributeValues')->findOrFail($id);
//     $this->categories = Category::all();
//     $this->brands = Brand::all();
//     $this->attributeNames = AttributeName::with('values')->get();

//     // Pre-fill the product details
//     $this->name = $this->product->name;
//     $this->slug = $this->product->slug;
//     $this->category_id = $this->product->category_id;
//     $this->brand_id = $this->product->brand_id;
//     $this->price = $this->product->price;
//     $this->offer_price = $this->product->offer_price;
//     $this->description = $this->product->description;
//     $this->is_active = $this->product->is_active;
//     $this->is_featured = $this->product->is_featured;
//     $this->in_stock = $this->product->in_stock;
//     $this->on_sale = $this->product->on_sale;

//     // Populate selectedAttributes with existing attribute values from the database
//     $this->selectedAttributes = [];
//     if ($this->product->attributeValues) {
//         foreach ($this->product->attributeValues as $attributeValue) {
//             $this->selectedAttributes[$attributeValue->pivot->attribute_id][$attributeValue->id] = true;
//         }
//     }

//     // Existing images (main thumbnail and other images)
//     $this->existingImages = [
//         'thumb_image' => $this->product->thumb_image,
//         'images' => is_string($this->product->image) ? json_decode($this->product->image, true) : $this->product->image,
//     ];
// }

public function mount($id)
{
    // Fetch product by ID and its associated data
    $this->product = Product::with('attributeValues')->findOrFail($id);
    $this->categories = Category::all();
    $this->brands = Brand::all();
    $this->attributeNames = AttributeName::with('values')->get();

    // Pre-fill the product details
    $this->name = $this->product->name;
    $this->slug = $this->product->slug;
    $this->category_id = $this->product->category_id;
    $this->brand_id = $this->product->brand_id;
    $this->price = $this->product->price;
    $this->offer_price = $this->product->offer_price;
    $this->description = $this->product->description;
    $this->short_description = $this->product->short_description;
    $this->is_active = $this->product->is_active;
    $this->is_featured = $this->product->is_featured;
    $this->in_stock = $this->product->in_stock;
    $this->on_sale = $this->product->on_sale;

    // Populate selectedAttributes with existing attribute values from the database
    $this->selectedAttributes = [];
    if ($this->product->attributeValues) {
        foreach ($this->product->attributeValues as $attributeValue) {
            $this->selectedAttributes[$attributeValue->pivot->attribute_id][$attributeValue->id] = true;
        }
    }

    // Existing images (main thumbnail and other images)
    $this->existingImages = [
        'thumb_image' => $this->product->thumb_image,
        'images' => is_string($this->product->image) ? json_decode($this->product->image, true) : $this->product->image,
    ];
}


// Automatically update slug when the name changes
public function updated($propertyName)
{
    if ($propertyName === 'name') {
        $this->slug = Str::slug($this->name);
    }
}



 // Method to delete an image
 public function deleteImage($imagePath, $key)
 {
     // Delete image from storage
     if (Storage::exists("public/{$imagePath}")) {
         Storage::delete("public/{$imagePath}");
     }

     // Remove image from the existing images array
     unset($this->existingImages['images'][$key]);

     // Re-index the array to avoid gaps
     $this->existingImages['images'] = array_values($this->existingImages['images']);
 }









    public function save()
{
    // Validate input
    $validatedData = $this->validate([
        'name' => 'required|string|max:255',
        'slug' => 'required|max:255|unique:products,slug,' . $this->product->id,
        'category_id' => 'required|integer',
        'brand_id' => 'required|integer',
        'price' => 'required|numeric',
        'offer_price' => 'nullable|numeric',
        'description' => 'required|string|max:255',
        'short_description' => 'required|string|max:255',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'in_stock' => 'boolean',
        'on_sale' => 'boolean',
        'thumb_image' => 'nullable|image|max:2048',
        'images.*' => 'nullable|image|max:2048',
        'selectedAttributes' => 'required|array|min:1',
    ]);

   // $offerPrice = $this->offer_price ?? 0;
    if($this->offer_price > 0){
        $offerPrice = $this->offer_price;
    }else{
        $offerPrice = 0;
    }

    // Update product data
    $this->product->update([
        'name' => $this->name,
        'slug' => $this->slug,
        'category_id' => $this->category_id,
        'brand_id' => $this->brand_id,
        'price' => $this->price,
        'offer_price' => $offerPrice,
        'description' => $this->description,
        'short_description' => $this->short_description,
        'is_active' => $this->is_active,
        'is_featured' => $this->is_featured,
        'in_stock' => $this->in_stock,
        'on_sale' => $this->on_sale,
    ]);

    // Handle the thumbnail image update
    if ($this->thumb_image) {
        if (!empty($this->existingImages['thumb_image'])) {
            Storage::disk('public')->delete($this->existingImages['thumb_image']);
        }

        $thumbPath = $this->thumb_image->store('images/products/thumbnails', 'public');
        $this->product->update(['thumb_image' => $thumbPath]);
    }

    // Handle the other product images update
    $existingImages = $this->existingImages['images'] ?? [];
    $newImagePaths = [];

    if ($this->images) {
        foreach ($this->images as $image) {
            $path = $image->store('images/products/images', 'public');
            $newImagePaths[] = $path;
        }
    }

    // Merge existing and new images
    $allImages = array_merge($existingImages, $newImagePaths);
    $this->product->update(['image' => json_encode($allImages)]);

    // First, remove any existing attribute values from the product
    $this->product->attributeValues()->detach();

    // Insert new selected attribute values while preserving existing ones
    foreach ($this->selectedAttributes as $attributeId => $attributeValues) {
        foreach ($attributeValues as $valueId => $isChecked) {
            if ($isChecked) {
                // Attach new attribute values to the product
                $this->product->attributeValues()->attach($valueId, ['attribute_id' => $attributeId]);
            }
        }
    }

    session()->flash('message', 'Product updated successfully!');
    return redirect()->route('admin.products');
}


//     public function save()
// {
//     // Validate input
//     $validatedData = $this->validate([
//         'name' => 'required|string|max:255',
//         'slug' => 'required|max:255|unique:products,slug,' . $this->product->id,
//         'category_id' => 'required|integer',
//         'brand_id' => 'required|integer',
//         'price' => 'required|numeric',
//         'offer_price' => 'nullable|numeric',
//         'description' => 'required|string|max:255',
//         'is_active' => 'boolean',
//         'is_featured' => 'boolean',
//         'in_stock' => 'boolean',
//         'on_sale' => 'boolean',
//         'thumb_image' => 'nullable|image|max:2048',
//         'images.*' => 'nullable|image|max:2048',
//         'selectedAttributes' => 'required|array|min:1',
//     ]);

//     // Update product data
//     $this->product->update([
//         'name' => $this->name,
//         'slug' => $this->slug,
//         'category_id' => $this->category_id,
//         'brand_id' => $this->brand_id,
//         'price' => $this->price,
//         'offer_price' => $this->offer_price,
//         'description' => $this->description,
//         'is_active' => $this->is_active,
//         'is_featured' => $this->is_featured,
//         'in_stock' => $this->in_stock,
//         'on_sale' => $this->on_sale,
//     ]);

//     // Handle the thumbnail image update
//     if ($this->thumb_image) {
//         if (!empty($this->existingImages['thumb_image'])) {
//             Storage::disk('public')->delete($this->existingImages['thumb_image']);
//         }

//         $thumbPath = $this->thumb_image->store('images/products/thumbnails', 'public');
//         $this->product->update(['thumb_image' => $thumbPath]);
//     }

//     // Handle the other product images update
//     $existingImages = $this->existingImages['images'] ?? [];
//     $newImagePaths = [];

//     if ($this->images) {
//         foreach ($this->images as $image) {
//             $path = $image->store('images/products/images', 'public');
//             $newImagePaths[] = $path;
//         }
//     }

//     $allImages = array_merge($existingImages, $newImagePaths);
//     $this->product->update(['image' => json_encode($allImages)]);

//     // First, remove any existing attribute values from the product
//     $this->product->attributeValues()->detach();

//     // Insert new selected attribute values while preserving existing ones
//     foreach ($this->selectedAttributes as $attributeId => $attributeValues) {
//         foreach ($attributeValues as $valueId => $isChecked) {
//             if ($isChecked) {
//                 // Attach new attribute values to the product
//                 $this->product->attributeValues()->attach($valueId, ['attribute_id' => $attributeId]);
//             }
//         }
//     }

//     session()->flash('message', 'Product updated successfully!');
//     return redirect()->route('admin.products');
// }







    public function render()
    {
        return view('livewire.admin.product-edit');
    }
}
