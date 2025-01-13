<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $guarded = [];

    //Relationships

    public function products(){
        return $this->hasMany(Product::class);
    }

    //Scopes

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    // Deactivate associated products when brand is soft-deleted
    public static function boot()
    {
        parent::boot();

        static::deleting(function ($brand) {
            $brand->products()->update(['is_active' => 0]); // Deactivate products under this brand
        });
    }
}
