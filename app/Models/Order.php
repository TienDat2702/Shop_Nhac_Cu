<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'discount_id',
        'name',
        'email',
        'status',
        'customer_note',
        'user_note',
        'address',
        'phone'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
