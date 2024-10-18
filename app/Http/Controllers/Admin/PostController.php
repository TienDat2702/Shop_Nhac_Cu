<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostCreateRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Http\Request;
use App\Services\UploadImageService;

class PostController extends Controller
{
    protected $uploadImageService;

    public function __construct(
        UploadImageService $uploadImageService
    ) {
        $this->uploadImageService = $uploadImageService;
    }

    //--------------------------------- Hiện bài viết----------------------------------------
    
    public function index(Request $request)
    {
        $config ='index';
        $countDeleted = Post::onlyTrashed()->get();
        if ($request['deleted'] == 'daxoa') {
            $config = 'deleted';
            $getDeleted = Post::onlyTrashed()->paginate(10);
            return view('admin.posts.post.deleted', compact('config', 'countDeleted','getDeleted'));
        }
        $posts = Post::GetPostAll()->paginate(10);
        return view('admin.posts.post.index', compact('posts','countDeleted', 'config'));
    }

    public function search(Request $request, $config){
        $countDeleted = Post::onlyTrashed()->get();
        if($config == 'index'){
            $posts = Post::Search($request->all());
            return view('admin.posts.post.index', compact('posts','countDeleted', 'config'));
        }
        else{
            $getDeleted = Post::onlyTrashed()->Search($request->all());
            return view('admin.posts.post.deleted', compact('getDeleted', 'config','countDeleted'));
        }
    }

    //--------------------------------- Hiện thêm bài viết----------------------------------------

    public function create()
    {
        $postCategories = $this->getRecursive();
        $posts = Post::GetPostAll()->get();
        return view('admin.posts.post.create', compact('posts', 'postCategories'));
    }

    //------------------------------- xử lý đệ quy show phân cấp danh mục--------------------------

    public function getRecursive(){
        $postCategories = PostCategory::GetAllByPublish()->get(); // danh sách tất cả danh mục đang hoạt động
        $listCategories = []; // tạo mảng chứa category
        PostCategory::recursive($postCategories, $parents = 0, $level = 1, $listCategories); // hàm đệ quy
        return $listCategories;
    }

    //--------------------------------- Xử lý thêm bài viết----------------------------------------
    
    public function store(PostCreateRequest $request)
    {

        $post = Post::create([
            'user_id' => 1,
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'description' => $request->input('description'),
            'slug' => $request->input('slug'),
            'post_category_id' => $request->input('post_category_id'),
        ]);

        // hàm xử lý ảnh
        $uploadPath = public_path('uploads/posts/posts');
        $this->uploadImageService->uploadImage($request, $post, $uploadPath);
        

        if($post){
            // $post->save();
            toastr()->success('Thêm mới thành công!');
        }
        else{
            toastr()->error('Thêm mới không thành công.');
        }
        return redirect()->route('post.index');
    }

    //--------------------------------- Hiện sửa bài viết----------------------------------------
    
    public function edit(string $id)
    {
        $postCategories = $this->getRecursive();
        $post = Post::GetPostAll()->find($id);
        return view('admin.posts.post.update', compact('postCategories', 'post'));
    }

    //--------------------------------- Xử lý sửa bài viết----------------------------------------

    public function update(PostUpdateRequest $request, string $id)
    {
       $post = Post::find($id);

       $data = [
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'title' => $request->input('title'),
            'title' => $request->input('title'),
            'title' => $request->input('title'),
       ];
    }
    
    //--------------------------------- Xử lý xóa mềm bài viết----------------------------------------

    public function destroy(string $id)
    {
      
    }



    //------------------- xử lý thùng rác------------------------------

    public function deleted(){
        
    }

    //--------------------------------- Xử lý khôi bài viết----------------------------------------

    public function restore(string $id)
    {
       
        
    }

    //--------------------------------- Xử lý xóa bài viết----------------------------------------

    public function forceDelete(string $id){
    
        
    }
}
