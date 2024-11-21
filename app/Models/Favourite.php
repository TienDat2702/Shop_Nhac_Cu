<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Favourite extends Model
{
    protected $fillable = ['customer_id', 'product_id']; // Các trường có thể được gán hàng loạt

    // Định nghĩa quan hệ với model User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Định nghĩa quan hệ với model Product
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    // Định nghĩa quan hệ với model Customer
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}