<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Showroom extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'phone',
        'image',
        'publish',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'showroom_products')->withPivot('stock');
    }
}
