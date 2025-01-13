<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

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

    public function scopeNotDeleted($query)
    {
        return $query->where('is_deleted', 0);
    }


     // Ensure products are "hidden" or "disabled" when the category is deleted
     public static function boot()
     {
         parent::boot();

         static::deleting(function ($category) {
             $category->products()->update(['is_active' => 0]); // Deactivate products under this category
         });
     }

}
