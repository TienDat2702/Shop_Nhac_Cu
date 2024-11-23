<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminResetToken extends Model
{
    protected $fillable = ['email', 'token'];

    public static function checkToken($token)
    {
        return self::where('token', $token)->first();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }
    public function scopeCheckToken($q, $token)
    {
        return $q->where('token', $token)->firstOrFail();
    }
}
