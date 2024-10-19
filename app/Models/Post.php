<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'publish',	
        'title',	
        'content',	
        'image',	
        'description',	
        'slug',	
        'post_category_id',	
        'user_id'	
    ];

    public function scopeGetPostAll($query){
        return $query->orderBy('id', 'DESC')->with('users', 'postCategory');
    }

    // hàm search
    public function scopeSearch($query, array $request = []){

        if(isset($request['keyword'])){
            $query->where('title', 'LIKE', '%' . $request['keyword'] . '%');
        }
        if($request['publish'] > 0){
            $query->where('publish', $request['publish']);
        }
        return $query->orderBy('id', 'DESC')->paginate(10);
    }

    public function users(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function postCategory(){
        return $this->belongsTo(PostCategory::class, 'post_category_id', 'id');
    }
}
