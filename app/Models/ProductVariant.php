<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'attributes', 'stock_quantity'];

    // Cast attributes as array
    protected $casts = [
        'attributes' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

//     public function variantAttributeDetails()
// {
//     return $this->hasManyThrough(
//         AttributeValue::class,  // Final model we want to access (attribute values)
//         AttributeName::class,   // Intermediate model (pivot table)
//         'id',  // Foreign key on AttributeName table (the primary key on AttributeNames)
//         'attribute_name_id',    // Foreign key on AttributeValue table (links to AttributeName)
//         'id',  // Local key on ProductVariant table (links to ProductVariant)
//         'id'   // Local key on AttributeValue table (links to AttributeValue)
//     );
// }

public function variantAttributeDetails()
{
    // Retrieve the attributes JSON directly from the database for the current variant
    $attributesJson = DB::table('product_variants')->where('id', $this->id)->value('attributes');

    // Decode the attributes JSON
    $attributeIds = collect(json_decode($attributesJson))
        ->pluck('attribute_value_id')
        ->toArray();

    // Return the matching attribute values
    return AttributeValue::whereIn('id', $attributeIds)->get();
}









}
