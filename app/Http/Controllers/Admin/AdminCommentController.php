<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class AdminCommentController extends Controller
{
    public function index(Request $request){
        $date = Comment::Date();
        $comments = Comment::orderby('id','desc')->Search($request->all());
        
        foreach($comments as $comment){
            $comment->is_new = 2;
            $comment->save();
        }
        
        return view('admin.comment.index', compact('comments','date'));
    }
    public function delete($id){
        $comment = Comment::find($id);
        if (!$comment) {
            toastr()->error('Dữ liệu không tồn tại!');
            return redirect()->back();
        }
        else{
            $comment->delete();
            toastr()->success('Xóa thành công!');
            return redirect()->back();
        }
    }
}
