<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttributeValue extends Model
{
    use HasFactory ;
    use SoftDeletes;

    protected $guarded = [];

    public function attributeName()
    {
        return $this->belongsTo(AttributeName::class);
    }

    // Relationship with Products
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_attribute_values', 'attribute_value_id', 'product_id');
    }

    // Relationship with Order Items (if you want to see which order items use this attribute value)
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'attribute_value_id');
    }


}
