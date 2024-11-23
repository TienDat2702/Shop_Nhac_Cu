<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class Customer extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'loyalty_level_id',
        'name',
        'email',
        'password',
        'image',
        'phone',
        'address',
        'publish',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'publish' => 'integer',
    ];

    // Quan hệ với cấp độ trung thành (loyalty level)
    public function loyaltyLevel()
    {
        return $this->belongsTo(LoyaltyLevel::class, 'loyalty_level_id', 'id');
    }

    // Quan hệ với đơn hàng (orders)
    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id', 'id');
    }

    // Lấy danh sách customer được publish
    public function scopeGetCustomerPublish($query)
    {
        return $query->where('publish', 1)->orderBy('id', 'DESC');
    }

    // Hàm tìm kiếm customer
    public function scopeSearch($query, array $request = [])
    {
        if (isset($request['keyword'])) {
            $query->where('name', 'LIKE', '%' . $request['keyword'] . '%')
                  ->orWhere('email', 'LIKE', '%' . $request['keyword'] . '%');
        }
        if (isset($request['publish']) && $request['publish'] > 0) {
            $query->where('publish', $request['publish']);
        }
        if (isset($request['loyalty_level_id'])) {
            $query->where('loyalty_level_id', $request['loyalty_level_id']);
        }
        if (isset($request['created_at']) && $request['created_at'] > 0) {
            [$month, $year] = explode('-', $request['created_at']);
            $query->whereMonth('created_at', '=', $month)
                  ->whereYear('created_at', '=', $year);
        }
        return $query->orderBy('id', 'DESC')->paginate(10);
    }

    // Tạo slug duy nhất cho customer
    public function scopeGenerateUniqueSlug($query, $str)
    {
        $slug = Str::slug($str);
        $count = $query->withTrashed()->where('slug', 'LIKE', "{$slug}%")->count();
        return $count ? "{$slug}-{$count}" : $slug;
    }

    // Lấy danh sách tháng-năm mà customer đã được tạo
    public function scopeDate($query)
    {
        $date = $query->select(DB::raw('MONTH(created_at) as month, YEAR(created_at) as year'))
                      ->groupBy('month', 'year')
                      ->get();

        $monthYear = $date->map(function ($item) {
            return $item->month . '-' . $item->year;
        })->toArray();

        return $monthYear;
    }

    // Kiểm tra nếu customer ở mức độ trung thành cụ thể
    public function isLoyaltyLevel($levelId)
    {
        return $this->loyalty_level_id === $levelId;
    }
}
