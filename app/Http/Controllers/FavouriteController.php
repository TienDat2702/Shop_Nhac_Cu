<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Favourite;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavouriteController extends Controller
{
    public function index()
    {
        $favourites = Favourite::with('product')->where('customer_id', Auth::guard('customer')->id())->get();
        return view('user.favourites.index', compact('favourites'));
    }

    public function add($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        // Kiểm tra xem sản phẩm đã có trong favourites chưa
        $existingFavourite = Favourite::where('product_id', $id)
            ->where('customer_id', Auth::guard('customer')->id())
            ->first();

        if ($existingFavourite) {
            return response()->json(['message' => 'Product already in favourites'], 409);
        }

        // Thêm sản phẩm vào favourites
        try {
            $favourite = new Favourite();
            $favourite->product_id = $id;
            $favourite->customer_id = Auth::guard('customer')->id(); // Gán customer_id
            $favourite->save();

            return response()->json(['message' => 'Product added to favourites'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error adding product to favourites', 'error' => $e->getMessage()], 500);
        }
    }

    public function remove($id)
    {
        Favourite::where('id', $id)
            ->where('customer_id', Auth::guard('customer')->id())
            ->delete();
        return redirect()->back()->with('success', 'Sản phẩm đã được xóa khỏi wishlist.');
    }
}
