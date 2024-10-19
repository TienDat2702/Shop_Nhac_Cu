<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
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
        if(isset($request['publish']) && $request['publish'] > 0){
            $query->where('publish', $request['publish']);
        }
        if(isset($request['post_category_id'])){
            $query->where('post_category_id', $request['post_category_id']);
        }
        // if(isset($request['publish']) && $request['publish'] > 0){
        //     $query->where('publish', $request['publish']);
        // }
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

    public function users(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function postCategory(){
        return $this->belongsTo(PostCategory::class, 'post_category_id', 'id');
    }

    public function scopeFindBySlugLike($query, $slug)
    {
        return $query->where('slug', 'LIKE', "{$slug}%");
    }
}
