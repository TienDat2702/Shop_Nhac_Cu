<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role_id', // Thêm role_id để quản lý phân quyền
        'publish',
        'image',
        'address',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Lấy tất cả user theo thứ tự mới nhất
    public function scopeGetUserAll($query)
    {
        return $query->orderBy('id', 'DESC')->with('role');
    }

    // Lấy danh sách user được publish
    public function scopeGetUserPublish($query)
    {
        return $query->where('publish', 1)->orderBy('id', 'DESC')->with('role');
    }

    // Hàm tìm kiếm user
    public function scopeSearch($query, array $request = [])
    {
        if (isset($request['keyword'])) {
            $query->where('name', 'LIKE', '%' . $request['keyword'] . '%')
                  ->orWhere('email', 'LIKE', '%' . $request['keyword'] . '%');
        }
        if (isset($request['publish']) && $request['publish'] > 0) {
            $query->where('publish', $request['publish']);
        }
        if (isset($request['role_id'])) {
            $query->where('role_id', $request['role_id']);
        }
        if (isset($request['created_at']) && $request['created_at'] > 0) {
            [$month, $year] = explode('-', $request['created_at']);
            $query->whereMonth('created_at', '=', $month)
                  ->whereYear('created_at', '=', $year);
        }
        return $query->orderBy('id', 'DESC')->paginate(10);
    }

    // Tạo slug duy nhất cho user
    public function scopeGenerateUniqueSlug($query, $str)
    {
        $slug = Str::slug($str);
        $count = $query->withTrashed()->where('slug', 'LIKE', "{$slug}%")->count();
        return $count ? "{$slug}-{$count}" : $slug;
    }

    // Lấy danh sách tháng-năm mà user đã được tạo
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

    // Lấy danh sách user nổi bật theo vai trò (role)
    public function scopeGetHotUsers($query, $role_id)
    {
        return $query->where('publish', 1)
                     ->where('role_id', $role_id)
                     ->orderBy('created_at', 'DESC')
                     ->limit(6);
    }

    // Quan hệ với vai trò (role)
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }
       // Kiểm tra nếu người dùng là admin
       public function isAdmin()
       {
           return $this->role_id === 2; // role_id = 2 là admin
       }
   
       // Kiểm tra nếu người dùng là customer
       public function isCustomer()
       {
           return $this->role_id === 1; // role_id = 1 là khách hàng
       }
}
