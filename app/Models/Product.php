<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

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
        'slug',
    ];

    // Scope để tìm kiếm sản phẩm
    public function scopeSearch($query, array $request = [])
    {
        if (isset($request['keyword'])) {
            $query->where('name', 'LIKE', '%' . $request['keyword'] . '%');
        }
        if (isset($request['publish']) && $request['publish'] > 0) {
            $query->where('publish', $request['publish']);
        }
        return $query->orderBy('id', 'DESC')->paginate(10);
    }

    // Định nghĩa mối quan hệ với Category
    public function category()
    {
        return $this->belongsTo(ProductCategory::class);
    }

    // Định nghĩa mối quan hệ với Brand
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}
