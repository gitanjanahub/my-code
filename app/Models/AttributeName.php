<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttributeName extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    public function values()
    {
        return $this->hasMany(AttributeValue::class);
    }

    protected static function booted()
    {
        static::deleting(function ($attributeName) {
            // Soft delete related AttributeValues
            $attributeName->values->each(function ($value) {
                $value->delete();  // This soft deletes each related value
            });
        });
    }

     // Relationship with AttributeValue
     public function attributeValues()
     {
         return $this->hasMany(AttributeValue::class);
     }

     /// Renamed relationship to avoid conflict with Product model
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'attribute_name_id');
    }

}
