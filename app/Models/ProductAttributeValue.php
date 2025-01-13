<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductAttributeValue extends Model
{
    use HasFactory;// Define the table name if it's different from the default "product_attribute_values"
    protected $table = 'product_attribute_values';

    // Enable mass assignment
    protected $fillable = ['product_id', 'attribute_id', 'attribute_value_id'];

    /**
     * Get the product that this attribute value belongs to.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the attribute that this value is associated with.
     */
    public function attribute(): BelongsTo
    {
        return $this->belongsTo(AttributeName::class, 'attribute_id');
    }

    /**
     * Get the specific attribute value.
     */
    public function attributeValue(): BelongsTo
    {
        return $this->belongsTo(AttributeValue::class, 'attribute_value_id');
    }
}
