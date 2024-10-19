<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;
    public function product(){
        return $this->hasMany(Product::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    public function children()
    {
        return $this->hasMany(ProductCategory::class, 'parent_id');
    }

    public function allProducts()
    {
        return $this->products()->orWhereHas('category.parent', function ($query) {
            $query->where('id', $this->id);
        });
    }

    public function getProductCountAttribute()
    {
        return $this->allProducts()->count();
    }
}
