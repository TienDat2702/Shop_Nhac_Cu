<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Banner extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'image',
        'order',
        'position',
        'publish',
        'description',
    ];
    public function scopeGetWithParent($query)
    {
        return $query->orderBy('id', 'DESC');
    }
}
