<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostCategoryCreateRequest;
use App\Http\Requests\PostCategoryUpdateRequest;
use App\Services\UploadImageService;
use App\Models\PostCategory;
use Illuminate\Http\Request;

class PostCategoryController extends Controller
{

    protected $uploadImageService;

    public function __construct(
        UploadImageService $uploadImageService
    ) {
        $this->uploadImageService = $uploadImageService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // lấy danh mục với danh mục cha
        $postCategories = PostCategory::GetWithParent()->paginate(10);
        return view('admin.posts.post_category.index', compact('postCategories'));
    }

    public function search(Request $request){
        $postCategories = PostCategory::Search($request->all());
        return view('admin.posts.post_category.index', compact('postCategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $postCategories = $this->getPostCategory();
        return view('admin.posts.post_category.create', compact('postCategories'));
    }

    // đệ quy show phân cấp danh mục
    public function getPostCategory(){
        $postCategories = PostCategory::GetAllByPublish()->get();
        $listCategories = []; // tạo mảng chứa category
        PostCategory::recursive($postCategories, $parents = 0, $level = 1, $listCategories); // hàm đệ quy
        return $listCategories;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostCategoryCreateRequest $request)
    {
        $postCategory = new PostCategory;
        $postCategory->name = $request->input('name');
        $postCategory->description = $request->input('description');
        $postCategory->parent_id = $request->input('parent_id');
        // Thiết lập level dựa trên level của danh mục cha
        $parentCategory = PostCategory::find($postCategory->parent_id);
        $postCategory->level = $parentCategory ? $parentCategory->level + 1 : 1;

        // hàm xử lý ảnh
        $this->uploadImageService->uploadImage($request, $postCategory);
        
        if($postCategory){
            $postCategory->save();
            toastr()->success('Thêm mới thành công!');
        }
        else{
            toastr()->error('Thêm mới không thành công.');
        }
        return redirect()->route('postCatagory.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

        $postCategories = $this->getPostCategory();
        $postCategory = PostCategory::GetWithParent()->find($id);
        return view('admin.posts.post_category.update', compact('postCategories', 'postCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostCategoryUpdateRequest $request, string $id)
    {
        $postCategory = PostCategory::GetWithParent()->find($id);

        $postCategory->name = $request->input('name');
        $postCategory->description = $request->input('description');

        // Kiểm tra xem danh mục cha có phải là con cháu của danh mục hiện tại không
        if (self::isDescendant($postCategory,  $request->input('parent_id'))) {
            return redirect()->back()->withErrors(['Danh mục cha không hợp lệ!!!']);
        }

        $postCategory->parent_id = $request->input('parent_id');

        // Thiết lập level dựa trên level của danh mục cha
        $parentCategory = PostCategory::find($postCategory->parent_id);
        $postCategory->level = $parentCategory ? $parentCategory->level + 1 : 1;

        // hàm xử lý ảnh

        // kiểm tra nếu tồn tại ảnh cũ hay không
        if($request->hasFile('image')){
            if($postCategory && $postCategory->image){
                $image_path = 'uploads/posts/post_categories/' . $postCategory->image;
                if (file_exists($image_path)) { // tìm vào đường dẫn ảnh
                    unlink($image_path); // xóa đường dẩn chứ ảnh cũ
                }
            }
        }
        $this->uploadImageService->uploadImage($request, $postCategory);
        
        
        if($postCategory->isDirty()){ // dùng isDirty để kiểm tra dữ liệu có thay đổi hay không
            $postCategory->save();
            toastr()->success('Cập nhật thành công!');
        }
        else{
            toastr()->success('Không có gì thay đổi!');
        }
        return redirect()->route('postCatagory.index');
    }

    // xử lý kiểm tra danh mục cha con
    public static function isDescendant($parentCategory, $childId) 
    {
        // Nếu ID của danh mục hiện tại bằng ID của danh mục được chọn
        if ($parentCategory->id == $childId) {
            return true; // Trả về true vì danh mục này là chính nó
        }
    
        // Lấy tất cả danh mục con của danh mục hiện tại
        $children = PostCategory::where('parent_id', $parentCategory->id)->get();
    
        // Duyệt qua tất cả danh mục con để kiểm tra đệ quy
        // tìm danh mục cháu của danh mục hiện tại bằng cách duyệt danh mục con của danh mục con của danh mục hiện tại
        foreach ($children as $child) {
            if (self::isDescendant($child, $childId)) { 
                return true;
            }
        }
    
        return false;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $postCategory = PostCategory::GetWithParent()->find($id);

        if (!$postCategory) {
            return redirect()->back()->withErrors(['Danh mục không tồn tại!']);
        }
        
        if($postCategory && $postCategory->image){
            $image_path = 'uploads/posts/post_categories/' . $postCategory->image;
            if (file_exists($image_path)) { // tìm vào đường dẫn ảnh
                unlink($image_path); // xóa đường dẩn chứ ảnh cũ
            }
        }

        $postCategory->delete();

        toastr()->success('Xóa danh mục thành công!');
        return redirect()->back();
    }
}
