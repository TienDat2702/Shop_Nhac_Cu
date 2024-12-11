<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
        if(isset($request['created_at']) && $request['created_at'] > 0){
            // Tách giá trị month-year thành tháng và năm
            [$month, $year] = explode('-', $request['created_at']);

            // Tìm theo tháng và năm
            $query->whereMonth('created_at', '=', $month)
                ->whereYear('created_at', '=', $year);
        }
        if(isset($request['rating']) && $request['rating'] > 0){
            $query->where('rating', $request['rating']);
        }
        return $query->orderBy('id', 'DESC')->paginate(10);
    }

    public function scopeDate($query){
        $date = $query->select(DB::raw('MONTH(created_at) as month, YEAR(created_at) as year'))
                      ->groupBy('month', 'year')
                      ->get();

        $monthYear = $date->map(function($item){
            return $item->month . '-' . $item->year;
        })->toArray();

        return $monthYear;
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