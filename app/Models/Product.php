<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $guarded = [];
    protected $casts = ['image'=>'array'];

    //Relationships

    public function category(){
        return $this->belongsTo(Category::class);//Category model name
    }

    public function brand(){
        return $this->belongsTo(Brand::class); //Brand model name
    }

    public function orderItems(){
        return $this->hasMany(OrderItem::class); //OrderItem model name
    }



    // public function attributes()
    // {
    //     return $this->belongsToMany(Attribute::class, 'product_attribute_values')
    //                 ->withPivot('attribute_value_id');
    // }

    // Relationship to get attributes via the pivot table
    public function attributes()
    {
        return $this->belongsToMany(AttributeName::class, 'product_attribute_values', 'product_id', 'attribute_id')
                    ->withPivot('attribute_value_id');
    }

    // New relationship for attribute values
    public function attributeValues()
    {
        return $this->belongsToMany(AttributeValue::class, 'product_attribute_values', 'product_id', 'attribute_value_id')
                    ->withPivot('attribute_id');
    }

    // Define a relationship for variants
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    //for product details and their attributes and values

    // public function attributesWithValues()
    // {
    //     return $this->belongsToMany(AttributeName::class, 'product_attribute_values', 'product_id', 'attribute_id')
    //         ->withPivot('attribute_value_id')
    //         ->with(['attributeValues' => function ($query) {
    //             $query->select('id', 'value', 'attribute_name_id');
    //         }]);
    // }

    public function attributesWithValues()
{
    return $this->belongsToMany(AttributeName::class, 'product_attribute_values', 'product_id', 'attribute_id')
        ->join('attribute_values as av', 'product_attribute_values.attribute_value_id', '=', 'av.id')
        ->select(
            'attribute_names.name as attribute_name',
            'av.value as attribute_value',
            'product_attribute_values.attribute_value_id'
        )
        ->orderBy('av.id');
}

//custom relationships///


    public function attributeDetails()//for frontend list attribute and variants
    {
        return $this->hasManyThrough(
            AttributeValue::class, // Final model we want to access (attribute values)
            ProductAttributeValue::class, // Intermediate model (pivot table)
            'product_id', // Foreign key on pivot table referencing products
            'id', // Local key on the attribute_values table
            'id', // Local key on the products table
            'attribute_value_id' // Foreign key on pivot table referencing attribute_values
        )
        ->join('attribute_names as an', 'product_attribute_values.attribute_id', '=', 'an.id')
        ->select(
            'an.name as attribute_name',
            'attribute_values.value as attribute_value',
            'product_attribute_values.attribute_value_id'
        );
    }











    //Scopes

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
                    //->whereHas('category', fn($q) => $q->where('is_deleted', 0)) // Only active categories
                     //->whereHas('brand', fn($q) => $q->whereNull('deleted_at')); // Only active brands;
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', 1);
    }

    public function scopeOnsale($query)
    {
        return $query->where('on_sale', 1);
    }



}
