<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attribute extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $guarded = [];

    // Ensure the value is cast as an array
    protected $casts = [
        'value' => 'array',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'attribute_product')
                    ->withPivot('value')
                    ->withTimestamps();
    }
}
