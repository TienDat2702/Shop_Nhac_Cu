<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use App\Mail\Contact;
use App\Models\Brand;
use App\Models\Banner;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Showroom;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use PhpParser\Node\Expr\New_;

class HomeController extends Controller
{
    public function index(){
        $brands = Brand::where('publish', 2)->get();
        $banners = Banner::where('position', 1)
                 ->where('publish', 2)
                 ->get();
        $product_views = Product::GetProductPublish()->orderBy('view', 'desc')->take(4)->get();
        $product_price = Product::GetProductPublish()->where('price_sale', '>', 0)->orderBy('price_sale', 'asc')->take(8)->get();
        $products = Product::GetProductPublish()->orderBy('updated_at', 'desc')->paginate(8);

        $product_cateogries = ProductCategory::where('publish',2)->where('level',1)->take(6)->get();


         // Lấy danh mục cha và tất cả sản phẩm liên quan (cả cha lẫn con)
        $product_cateogries = ProductCategory::with(['children', 'products' => function ($query) {
            $query->where('publish', 2);
        }])
        ->where('publish', 2)
        ->where('level', 1)
        ->get();

        // Xử lý danh sách sản phẩm cho từng danh mục cha
        $categoriesWithProducts = $product_cateogries->map(function ($category) {
            $childCategoryIds = $category->children->pluck('id')->toArray();

            // Gộp sản phẩm từ danh mục cha và danh mục con
            $products = Product::where('publish', 2)
                ->whereIn('category_id', array_merge([$category->id], $childCategoryIds))
                ->take(4) // Giới hạn 4 sản phẩm
                ->get();

            return [
                'category' => $category,
                'products' => $products,
            ];
        });

        $posts = Post::GetPostPublish()->limit(4)->get();
        $post_category_event = PostCategory::GetAllByPublish()->where('name', 'Sự Kiện')->first();
        
        $customer = Auth::guard('customer')->user();
        $product_favourite = [];
        if ( $customer) {
            $product_favourite = $customer->favourites->pluck('id','product_id')->toArray();
        }

        return view('user.index', compact(
            'brands', 
            'product_favourite', 
            'product_views', 
            'product_price', 
            'products', 
            'banners', 
            'posts',
            'product_cateogries',
            'categoriesWithProducts'
        ));
    }
    public function about(){
        $showrooms = Showroom::all();
        return view('user.about',compact('showrooms'));
    }
    public function contact(){
        $khotong = Showroom::where('publish', 4)->first();
        return view('user.contact', compact('khotong'));
    }
    public function brand($slug){
        $brand = Brand::where('slug',$slug)->first();
        $products = Product::GetProductPublish()->where('brand_id',$brand->id)->get();
        $customer = Auth::guard('customer')->user();
        $product_favourite = [];
        if ( $customer) {
            $product_favourite = $customer->favourites->pluck('id','product_id')->toArray();
        }
        return view('user.brand',compact('products','brand', 'product_favourite'));
    }
    public function postContact(ContactRequest $request){
        $contact = $request->validated();
        Mail::to('tiendat03533@gmail.com')->send( New Contact($contact) );
        toastr()->success('Gửi thành công');
        return redirect()->route('contact');
    }
}
