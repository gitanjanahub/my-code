<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItemAttribute extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    /**
     * Relationship with AttributeName (e.g., Size, Color)
     */
    public function attributeName()
    {
        return $this->belongsTo(AttributeName::class);
    }

    /**
     * Relationship with AttributeValue (e.g., Large, Red)
     */
    public function attributeValue()
    {
        return $this->belongsTo(AttributeValue::class);
    }
}
