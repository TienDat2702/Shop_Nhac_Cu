<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'product_id', 'comment', 'rating'];


    // hàm search
    public function scopeSearch($query, array $request = []){

        if(isset($request['keyword'])){
            $keyword = $request['keyword'];
            $query->where('comment', 'LIKE', '%' . $keyword  . '%')
                    ->orWhereHas('customer', function ($query) use ($keyword) { // Tìm trong tên khách hàng
                        $query->where('name', 'LIKE', '%' . $keyword . '%');
                    })
                    ->orWhereHas('product', function ($query) use ($keyword) { // Tìm trong tên khách hàng
                        $query->where('name', 'LIKE', '%' . $keyword . '%');
                    })
            ;
        }
        if(isset($request['rating']) && $request['rating'] > 0){
            $query->where('rating', $request['rating']);
        }
        return $query->orderBy('id', 'DESC')->paginate(10);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}