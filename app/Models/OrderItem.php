<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    public function order(){
        return $this->belongsTo(Order::class);
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }

    // Relationship with Attribute Name (e.g., Size, Color)
    public function attributeName()
    {
        return $this->belongsTo(AttributeName::class);
    }

    // Relationship with Attribute Value (e.g., Large, Red)
    public function attributeValue()
    {
        return $this->belongsTo(AttributeValue::class);
    }

    public function itemAttributes()
    {
        return $this->hasMany(OrderItemAttribute::class);
    }

}
