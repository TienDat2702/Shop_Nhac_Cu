<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Discount;

class DiscountController extends Controller
{
    public function index()
    {
        $discounts = Discount::all();
        return view('admin.discounts.index', compact('discounts'));
    }

    public function create()
    {
        return view('admin.discounts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:discounts',
            'discount_rate' => 'required|numeric',
            'max_value' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        Discount::create($request->all());
        return redirect()->route('admin.discounts.index')->with('success', 'Mã giảm giá đã được tạo thành công!');
    }

    public function edit(Discount $discount)
    {
        return view('admin.discounts.edit', compact('discount'));
    }

    public function update(Request $request, Discount $discount)
    {
        $request->validate([
            'code' => 'required|unique:discounts,code,' . $discount->id,
            'discount_rate' => 'required|numeric',
            'max_value' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        $discount->update($request->all());
        return redirect()->route('admin.discounts.index')->with('success', 'Mã giảm giá đã được cập nhật thành công!');
    }

    public function destroy(Discount $discount)
    {
        $discount->delete();
        return redirect()->route('admin.discounts.index')->with('success', 'Mã giảm giá đã được xóa thành công!');
    }
}
