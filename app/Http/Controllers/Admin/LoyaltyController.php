<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoyaltyLevel;
use Illuminate\Http\Request;

class LoyaltyController extends Controller
{
    public function index(){
        $loyalty = LoyaltyLevel::get();

        return view('admin.loyalty.index', compact('loyalty'));
    }

    public function update(Request $request){
        $loyalty = LoyaltyLevel::get();

        // Mảng chứa rules để validate
        $rules = [];
        $messages = [];

        foreach ($loyalty as $level) {
            $rules['discount_rate_' . $level->id] = 'required|numeric|min:0|max:100';
            $rules['order_total_price_' . $level->id] = 'required|numeric|min:0|max:999999999999';

            $messages['discount_rate_' . $level->id . '.required'] = 'Phần trăm giảm cho cấp độ ' . $level->level_name . ' là bắt buộc.';
            $messages['discount_rate_' . $level->id . '.numeric'] = 'Phần trăm giảm phải là một số.';
            $messages['discount_rate_' . $level->id . '.min'] = 'Phần trăm giảm không được nhỏ hơn 0.';
            $messages['discount_rate_' . $level->id . '.max'] = 'Phần trăm giảm không được lớn hơn 100.';

            $messages['order_total_price_' . $level->id . '.required'] = 'Tổng mua tối đa cho cấp độ ' . $level->level_name . ' là bắt buộc.';
            $messages['order_total_price_' . $level->id . '.numeric'] = 'Tổng mua tối đa phải là một số.';
            $messages['order_total_price_' . $level->id . '.min'] = 'Tổng mua tối đa không được nhỏ hơn 0.';
            $messages['order_total_price_' . $level->id . '.max'] = 'Tổng mua tối đa vượt giới hạn.';
        }

        // Validate dữ liệu
        $validated = $request->validate($rules, $messages);

        foreach($loyalty as $level){
            $level->discount_rate = $request->input('discount_rate_'. $level->id) / 100;
            $level->order_total_price = $request->input('order_total_price_'. $level->id);

            $level->save();
        }

        toastr()->success('Cập nhật thành công!');
        return redirect()->route('loyalty.index');
    }
}
