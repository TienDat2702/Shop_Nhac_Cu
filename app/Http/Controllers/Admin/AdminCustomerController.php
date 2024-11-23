<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerCreateRequest;
use App\Http\Requests\CustomerUpdateRequest;
use App\Models\Customer;
use App\Models\LoyaltyLevel;
use Illuminate\Http\Request;
use App\Services\UploadImageService;

class AdminCustomerController extends Controller
{
    protected $uploadImageService;

    public function __construct(UploadImageService $uploadImageService)
    {
        $this->uploadImageService = $uploadImageService;
    }

    //--------------------------------- Hiển thị danh sách khách hàng ----------------------------------------

    public function index(Request $request)
    {
        $date = Customer::Date();
        $countDeleted = Customer::onlyTrashed()->get();

        if ($request['deleted'] == 'daxoa') {
            $config = 'deleted';
            $getDeleted = Customer::onlyTrashed()->Search($request->all());
            return view('admin.customers.customer.index', compact('config', 'countDeleted', 'getDeleted', 'date'));
        } else {
            $config = 'index';
            $customers = Customer::query()->Search($request->all());
            return view('admin.customers.customer.index', compact('customers', 'countDeleted', 'config', 'date'));
        }
    }

    //--------------------------------- Hiện thêm khách hàng ----------------------------------------

    public function create()
    {
        $loyaltyLevels = LoyaltyLevel::all(); 
        return view('admin.customers.customer.create', compact('loyaltyLevels'));
    }

    //--------------------------------- Xử lý thêm khách hàng ----------------------------------------

    public function store(CustomerCreateRequest $request)
    {
        $customer = Customer::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
            'password' => bcrypt($request->input('password')),
            'loyalty_level_id' => $request->input('loyalty_level_id'),
            'publish' => $request->input('publish', 1),
        ]);

        $uploadPath = public_path('uploads/customers');
        $this->uploadImageService->uploadImage($request, $customer, $uploadPath);

        if ($customer) {
            toastr()->success('Thêm khách hàng mới thành công!');
        } else {
            toastr()->error('Thêm khách hàng mới không thành công.');
        }
        return redirect()->route('customer.index');
    }

    //--------------------------------- Hiển thị chỉnh sửa khách hàng ----------------------------------------

    public function edit(string $id)
    {
        $customer = Customer::findOrFail($id);
        $loyaltyLevels = LoyaltyLevel::all();
        return view('admin.customers.customer.update', compact('customer', 'loyaltyLevels'));
    }

    //--------------------------------- Xử lý chỉnh sửa khách hàng ----------------------------------------

    public function update(CustomerUpdateRequest $request, string $id)
    {
        $customer = Customer::findOrFail($id);

        $data = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
            'loyalty_level_id' => $request->input('loyalty_level_id'),
            'publish' => $request->input('publish', 1),
        ];

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->input('password'));
        }

        if ($request->hasFile('image')) {
            if ($customer->image) {
                $imagePath = public_path('uploads/customers/' . $customer->image);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
        }

        $uploadPath = public_path('uploads/customers');
        $this->uploadImageService->uploadImage($request, $customer, $uploadPath);

        if ($customer->update($data)) {
            toastr()->success('Cập nhật khách hàng thành công!');
        } else {
            toastr()->error('Cập nhật khách hàng không thành công.');
        }
        return redirect()->route('customer.index');
    }

    //--------------------------------- Xử lý xóa mềm khách hàng ----------------------------------------

    public function destroy(string $id)
    {
        $customer = Customer::findOrFail($id);

        if ($customer->image) {
            $imagePath = public_path('uploads/customers/' . $customer->image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        if ($customer->delete()) {
            toastr()->success('Xóa khách hàng thành công!');
        } else {
            toastr()->error('Xóa khách hàng không thành công!');
        }
        return redirect()->back();
    }

    //--------------------------------- Khôi phục khách hàng ----------------------------------------

    public function restore(string $id)
    {
        $customer = Customer::onlyTrashed()->findOrFail($id);

        if ($customer->restore()) {
            toastr()->success('Khôi phục khách hàng thành công!');
        } else {
            toastr()->error('Khôi phục khách hàng không thành công!');
        }
        return redirect()->back();
    }

    //--------------------------------- Xóa vĩnh viễn khách hàng ----------------------------------------

    public function forceDelete(string $id)
    {
        $customer = Customer::onlyTrashed()->findOrFail($id);

        if ($customer->image) {
            $imagePath = public_path('uploads/customers/' . $customer->image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        if ($customer->forceDelete()) {
            toastr()->success('Xóa vĩnh viễn khách hàng thành công!');
        } else {
            toastr()->error('Xóa vĩnh viễn khách hàng không thành công!');
        }
        return redirect()->back();
    }
}
