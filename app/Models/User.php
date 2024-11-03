<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',  // Đảm bảo trường phone có trong danh sách fillable
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Khai báo casts cho các trường
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Quan hệ với model Post
    public function posts() {
        return $this->hasMany(Post::class, 'user_id', 'id');
    }

    // Phương thức kiểm tra vai trò admin
    public function isAdmin()
    {
        return $this->role_id === 1; // Giả sử role_id = 1 là admin
    }
}
