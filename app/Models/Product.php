<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'image',
        'price',
        'price_sale',
        'view',
        'description',
        'publish',
        'summary',
    ];

    public function showrooms()
    {
        return $this->belongsToMany(Showroom::class, 'showroom_products')->withPivot('stock');
    }
    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }
}
