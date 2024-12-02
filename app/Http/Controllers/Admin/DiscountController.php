<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DiscountCreateRequest;
use App\Http\Requests\DiscountUpdateRequest;
use App\Models\Discount;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
class DiscountController extends Controller
{
    public function index(Request $request)
    {
        $query = Discount::query();
        $countDeleted = Discount::onlyTrashed()->count();

        if ($request->input('deleted') == 'daxoa') {
            $getDelete = Discount::onlyTrashed()->paginate(10);
            $config = 'deleted';
            return view('admin.discounts.index', compact('countDeleted', 'config', 'getDelete'));
        } else {
            if ($request->has('search')) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }
            $discounts = $query->paginate(10);
            $config = 'index';
        }

        return view('admin.discounts.index', compact('discounts', 'countDeleted', 'config'));
    }

    public function create()
    {
        return view('admin.discounts.create');
    }

    public function store(DiscountCreateRequest $request)
    {
        $discount = new Discount();
        $discount->name = $request->input('name');
        $discount->discount_rate = $request->input('discount_rate');
        $discount->max_value = $request->input('max_value');
        $discount->minimum_total_value = $request->input('minimum_total_value');
        $discount->minimum_order_value = $request->input('minimum_order_value');
        $discount->start_date = $request->input('start_date');
        $discount->end_date = $request->input('end_date');
        $discount->use_limit = $request->input('use_limit');
        
        $discount->save();

        return redirect()->route('discount.index')->with('success', 'Mã giảm giá đã được tạo thành công.');
    }

    public function edit($id)
    {
        $discount = Discount::findOrFail($id);
        return view('admin.discounts.edit', compact('discount'));
    }

    public function update(DiscountUpdateRequest $request, $id)
{
    try {
        // Lấy dữ liệu đã validate từ request
        $data = $request->validated();

        // Chuyển đổi ngày giờ sang định dạng chuẩn
        $data['start_date'] = Carbon::parse($request->start_date)->format('Y-m-d H:i:s');
        $data['end_date'] = Carbon::parse($request->end_date)->format('Y-m-d H:i:s');

        // Thực hiện cập nhật
        $updated = Discount::where('id', $id)->update($data);

        if ($updated) {
            toastr()->success('Cập nhật thành công!');
        } else {
            toastr()->error('Không tìm thấy bản ghi cần cập nhật.');
        }
    } catch (\Exception $e) {
        toastr()->error('Đã xảy ra lỗi trong quá trình cập nhật.');
    }

    return redirect()->route('discount.index');
}


    public function destroy($id)
    {
        $discount = Discount::findOrFail($id);
        $discount->delete();

        toastr()->success('Xóa thành công!');
        return redirect()->route('discount.index');
    }

    public function restore($id)
    {
        $discount = Discount::withTrashed()->find($id);
        if ($discount) {
            $discount->restore();
            return redirect()->route('discount.index')->with('success', 'Mã giảm giá đã được khôi phục thành công.');
        }
        return redirect()->route('discount.index')->with('error', 'Mã giảm giá không tồn tại.');
    }
}