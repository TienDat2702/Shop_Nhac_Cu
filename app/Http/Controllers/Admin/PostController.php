<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostCreateRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Http\Request;
use App\Services\UploadImageService;
use App\Services\LibraryService;

class PostController extends Controller
{
    protected $uploadImageService;
    protected $libraryService; 

    public function __construct(
        UploadImageService $uploadImageService,
        LibraryService $libraryService
    ) {
        $this->uploadImageService = $uploadImageService;
        $this->libraryService = $libraryService;
    }

    //--------------------------------- Hiện bài viết----------------------------------------
    
    public function index(Request $request)
    {
        $date = Post::Date();
        $countDeleted = Post::onlyTrashed()->get();
        $postCategories = $this->getRecursive();

        if ($request['deleted'] == 'daxoa') {
            $config = 'deleted';
            $getDeleted = Post::onlyTrashed()->Search($request->all());
            return view('admin.posts.post.index', compact('config', 'countDeleted','getDeleted', 'postCategories', 'date'));
        }
        else{
            $config ='index';
            $posts = Post::GetPostAll()->Search($request->all());
            return view('admin.posts.post.index', compact('posts','countDeleted', 'config', 'postCategories','date'));
        }
    }

    // public function search(Request $request, $config){
    //     $countDeleted = Post::onlyTrashed()->get();
    //     if($config == 'index'){
    //         $posts = Post::Search($request->all());
    //         return view('admin.posts.post.index', compact('posts','countDeleted', 'config'));
    //     }
    //     else{
    //         $getDeleted = Post::onlyTrashed()->Search($request->all());
    //         return view('admin.posts.post.deleted', compact('getDeleted', 'config','countDeleted'));
    //     }
    // }

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

        $slug = $request->input('slug');

        if($slug == ''){
            $slug = $this->libraryService->generateUniqueSlug($request->input('title'));
            
        }

        $post = Post::create([
            'user_id' => 1,
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'description' => $request->input('description'),
            'slug' => $slug,
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
            'user' => 1,
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'description' => $request->input('description'),
            'slug' => $request->input('slug'),
            'post_category_id' => $request->input('post_category_id'),
       ];

       // kiểm tra nếu tồn tại ảnh cũ hay không
        if($request->hasFile('image')){
            if($post && $post->image){
                $image_path = 'uploads/posts/posts/' . $post->image;
                if (file_exists($image_path)) { // tìm vào đường dẫn ảnh
                    unlink($image_path); // xóa đường dẩn chứ ảnh cũ
                }
            }
        }
        // hàm lưu ảnh
        $uploadPath = public_path('uploads/posts/posts');
        $this->uploadImageService->uploadImage($request, $post, $uploadPath);

        if($post && $post->update($data)){
            if($post->wasChanged()){
                toastr()->success('Cập nhật thành công!');
            }
            else{
                toastr()->success('Không có gì thay đổi!');
            }
            return redirect()->route('post.index');
        }else{
            toastr()->error('Cập nhật không thành công!');
        }

        
    }
    
    //--------------------------------- Xử lý xóa mềm bài viết----------------------------------------

    public function destroy(string $id)
    {
        $post = Post::GetPostAll()->find($id);

        if($post && $post->image){
            $image_path = 'uploads/posts/posts/' . $post->image;
            if (file_exists($image_path)) { // tìm vào đường dẫn ảnh
                unlink($image_path); // xóa đường dẩn chứ ảnh cũ
            }
        }
        $post->publish = 1;
        $post->save();
        
        if ($post->delete()) {
            toastr()->success('Xóa thành công!');
        }
        else{
            toastr()->error('Xóa không thành công!');
        }
        return redirect()->back();
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
