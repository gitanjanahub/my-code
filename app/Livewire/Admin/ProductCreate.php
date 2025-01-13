<?php

namespace App\Livewire\Admin;


use App\Models\AttributeName;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttributeValue;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.adminpanel')]

#[Title('Create Product')]

class ProductCreate extends Component
{
    use WithFileUploads;

    public $categories;
    public $brands;
    public $name;
    public $slug;
    public $category_id;
    public $brand_id;
    public $price;
    public $offer_price;
    public $description;
    public $short_description;
    public $is_active = 0;
    public $is_featured = 0;
    public $in_stock = 0;
    public $on_sale = 0;
    public $images = [];
    public $thumb_image;
    public $attributeNames = [];
    public $attributeValues = [];
    public $selectedAttributes = [];

    public function mount()
    {
        $this->categories = Category::all();
        $this->brands = Brand::all();
        $this->attributeNames = AttributeName::with('values')->get();
    }

    // Automatically update slug when name changes
    public function updated($propertyName)
    {
        if ($propertyName === 'name') {
            $this->slug = Str::slug($this->name);
        }
    }

    public function save()
    {
        //dd($this->selectedAttributes);
        // Step 1: Validate form inputs, including image files
        $validatedData = $this->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|max:255|unique:products,slug',
            'description' => 'required|string|max:255',
            'short_description' => 'required|string|max:255',
            'category_id' => 'required|integer',
            'brand_id' => 'required|integer',
            'price' => 'required|numeric',
            'offer_price' => 'nullable|numeric',
            'thumb_image' => 'required|image|max:2048', // Image validation (max 2MB)
            'images.*' => 'nullable|image|max:2048', // Multiple image validation (max 2MB each)
            //'is_active' => 'boolean',
            //'is_featured' => 'boolean',
            //'in_stock' => 'boolean',
            //'on_sale' => 'boolean',
            'selectedAttributes' => 'required|array|min:1',
        ]);

        $offerPrice = $this->offer_price ?? 0;  // If offer_price is not set, default it to 0


        // If validation passes, proceed with inserting data into the database

        $product = Product::create([
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'short_description' => $this->short_description,
            'category_id' => $this->category_id,
            'brand_id' => $this->brand_id,
            'price' => $this->price,
            'offer_price' => $offerPrice,
            'is_active' => $this->is_active ? 1 : 0,
            'is_featured' => $this->is_featured ? 1 : 0,
            'in_stock' => $this->in_stock ? 1 : 0,
            'on_sale' => $this->on_sale ? 1 : 0,
        ]);

        // Only after successful validation and product creation, handle file uploads
        if ($this->thumb_image) {
            $thumbPath = $this->thumb_image->store('images/products/thumbnails', 'public');
            $product->update(['thumb_image' => $thumbPath]);
        }

        if (is_array($this->images) && count($this->images) > 0) {
            $imagePaths = [];
            foreach ($this->images as $image) {
                $path = $image->store('images/products/images', 'public');
                $imagePaths[] = $path;
            }
            $product->update(['image' => json_encode($imagePaths)]);
        }

            // Step 4: Save selected attribute values to product_attribute_values table
            // if ($this->selectedAttributes) {
            //     foreach ($this->selectedAttributes as $attributeId => $attributeValues) {
            //         foreach ($attributeValues as $valueId => $isChecked) {
            //             if ($isChecked) {
            //                 ProductAttributeValue::create([
            //                     'product_id' => $product->id,
            //                     'attribute_id' => $attributeId,
            //                     'attribute_value_id' => $valueId,
            //                 ]);
            //             }
            //         }
            //     }
            // }

            // Step 4: Save selected attribute values to product_attribute_values table
if (!empty($this->selectedAttributes)) {
    foreach ($this->selectedAttributes as $attributeId => $attributeValues) {
        foreach ($attributeValues as $valueId => $isChecked) {
            if ($isChecked) {
                // Ensure that the valueId is valid before inserting
                ProductAttributeValue::create([
                    'product_id' => $product->id,
                    'attribute_id' => $attributeId,
                    'attribute_value_id' => $valueId,
                ]);
            }
        }
    }
}


        $this->reset();

        session()->flash('message', 'Product created successfully!');
        return redirect()->route('admin.products');

    }






    public function render()
    {
        return view('livewire.admin.product-create');
    }
}
