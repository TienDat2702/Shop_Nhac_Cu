<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_id',
        'discount_id',
        'name',
        'email',
        'status',
        'customer_note',
        'user_note',
        'address',
        'phone',
        'total',
        'payment_method',
        'token'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'delivered_at' => 'datetime',
        'canceled_at' => 'datetime'
    ];


    // hàm search
    public function scopeSearch($query, array $request = []){

        if(isset($request['keyword'])){
            $keyword = $request['keyword'];
            $query->where('id', 'LIKE', '%' . $keyword . '%')
            ->orWhereHas('customer', function ($query) use ($keyword) { // Tìm trong tên khách hàng
                $query->where('name', 'LIKE', '%' . $keyword . '%');
            });
        }
        if(isset($request['publish']) && $request['publish'] > 0){
            $query->where('publish', $request['publish']);
        }
        if(isset($request['status']) && $request['status'] > 0){
            $query->where('status', $request['status']);
        }
        if(isset($request['created_at']) && $request['created_at'] > 0){
            // Tách giá trị month-year thành tháng và năm
            [$month, $year] = explode('-', $request['created_at']);

            // Tìm theo tháng và năm
            $query->whereMonth('created_at', '=', $month)
                ->whereYear('created_at', '=', $year);
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
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
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

