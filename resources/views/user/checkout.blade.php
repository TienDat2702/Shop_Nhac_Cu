@extends('user.layouts.app')
@section('title', 'Thanh toán')
@section('content')
<main class="pt-90">
    <div class="mb-4 pb-4"></div>
    <section class="shop-checkout container">
      <h2 class="page-title">Thanh Toán</h2>
      <div class="checkout-steps">
        <a href="{{ route('cart.index') }}" class="checkout-steps__item active">
          <span class="checkout-steps__item-number">01</span>
          <span class="checkout-steps__item-title">
            <span>GIỏ hàng</span>
            <em>Quản lý danh sách mặt hàng của bạn</em>
          </span>
        </a>
        <a href="{{ route('checkout') }}" class="checkout-steps__item active">
          <span class="checkout-steps__item-number">02</span>
          <span class="checkout-steps__item-title">
            <span> Vận chuyển và thanh toán</span>
            <em>Kiểm tra danh sách mặt hàng của bạn</em>
          </span>
        </a>
        <a href="" class="checkout-steps__item">
          <span class="checkout-steps__item-number">03</span>
          <span class="checkout-steps__item-title">
            <span>Xác nhận</span>
                            <em>Xem xét và gửi đơn đặt hàng của bạn</em>
          </span>
        </a>
      </div>
      <form id="checkoutForm" name="checkout-form" method="POST" action="{{ route('checkout.online') }}" data-url="{{ route('checkout.online') }}">
        @csrf
        <div class="checkout-form">
          <div class="billing-info__wrapper">
            <div class="row">
              <div class="col-6">
                <h4>CHI TIẾT VẬN CHUYỂN</h4>
              </div>
              <div class="col-6">
              </div>
            </div>

            <div class="row mt-5">
              <div class="col-md-6">
                <div class="form-floating my-3">
                  <input type="text" class="form-control" value="{{ old('name') ?? $customer->name }}" name="name">
                  <label for="name">Họ và tên *</label>
                  @if ($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                  @endif
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-floating my-3">
                  <input type="text" class="form-control" value="{{ old('phone') ?? $customer->phone }}" name="phone">
                  <label for="phone">Số điện thoại *</label>
                  @if ($errors->has('phone'))
                    <span class="text-danger">{{ $errors->first('phone') }}</span>
                  @endif
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-floating my-3">
                  <input type="text" class="form-control" id="address" name="province" value="{{ old('province') }}">
                  <input type="hidden" id="latitude" name="latitude" value="{{ $customer->latitude }}">
                  <input type="hidden" id="longitude" name="longitude" value="{{ $customer->longitude }}">
                  <label for="address">Địa chỉ thành phố*</label>
                  <span class="text-danger"></span>
                  <div id="showrooms"></div>  <!-- Hiển thị danh sách showroom ở đây -->

                </div>
              </div>

              <div class="col-md-6">
              <div class="form-floating my-3">
                  <input type="text" class="form-control" id="specificAddress" name="address" value="{{ old('address') ?? $customer->address }}" placeholder="Địa chỉ cụ thể">
                  <label for="specificAddress">Địa chỉ cụ thể*</label>
                  @if ($errors->has('address'))
                    <span class="text-danger">{{ $errors->first('address') }}</span>
                  @endif
              </div>
          </div>

              <div class="col-md-6">
                <div class="form-floating my-3">
                  <input type="text" class="form-control" value="{{ old('email') ??  $customer->email }}"  name="email">
                  <label for="email">Email *</label>
                  @if ($errors->has('email'))
                  <span class="text-danger">{{ $errors->first('email') }}</span>
                @endif
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-floating my-3">
                  <textarea style="height: 300px" class="form-control" name="customer_note">{{old('customer_note')}} </textarea>
                  <label for="customer_note">Ghi chú</label>
                  @if ($errors->has('customer_note'))
                    <span class="text-danger">{{ $errors->first('customer_note') }}</span>
                  @endif
                </div>
              </div>
            </div>
          </div>
          <div class="checkout__totals-wrapper">
            <div class="sticky-content">
              <div class="checkout__totals">
                <h3>Đơn Hàng Của Bạn</h3>
                <table class="checkout-cart-items">
                  <thead>
                    <tr>
                      <th>SẢN PHẨM</th>
                      <th align="right">ĐƠN GIÁ</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($products as $item)
                      <tr>
                        <td>
                          <img width="50px" src="{{ asset('uploads/products/product/' . $item->image) }}" alt="">
                          {{ $item->name
                          . ' x '
                          . session('carts')[$item->id]['quantity']

                        }}
                        </td>
                        <td align="right">
                          {{ number_format(($item->price_sale ?? $item->price) * session('carts')[$item->id]['quantity'], 0, '.', ',') . ' VNĐ'}}
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
                <table class="checkout-totals">
                  <tbody>
                    <tr>
                      <th>TỔNG PHỤ</th>
                      <td align="right">{{ number_format($subtotal , 0, '.', ',') . ' VNĐ'}}</td>
                    </tr>
                    <tr>
                      <th>GIẢM GIÁ</th>
                      <td id="discountAmount" align="right">
                          {{ number_format($discountAmount, 0, '.', ',') . ' VNĐ' }}</td>
                    </tr>
                    {{-- <tr>
                      <th>VAT</th>
                      <td align="right">$19</td>
                    </tr> --}}
                    <tr>
                      <th>TỔNG THANH TOÁN</th>
                      <td id="totalAmount" align="right">
                          {{ number_format($subtotal - $discountAmount, 0, '.', ',') . ' VNĐ' }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
                <div class="checkout__payment-methods">
                  <div class="form-check">
                    <input class="form-check-input form-check-input_fill" type="radio" name="checkout_payment_method"
                      id="checkout_payment_method_3" value="Thanh toán khi nhận hàng" checked>
                    <label class="form-check-label" for="checkout_payment_method_3">
                      Thanh toán khi giao hàng
                      <p class="option-detail">
                        Thanh toán khi nhận hàng. Vui lòng kiểm tra hàng trước khi nhận
                      </p>
                    </label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input form-check-input_fill" type="radio" name="checkout_payment_method"
                      id="checkout_payment_method_1" value="Thanh toán VNPAY" >
                    <label class="form-check-label" for="checkout_payment_method_1">
                      Thanh toán VNPAY
                      <p class="option-detail">

                      </p>
                    </label>
                  </div>
                  <div class="form-check">
                    <div class="method-online">
                      <input class="form-check-input form-check-input_fill" type="radio" name="checkout_payment_method"
                        id="checkout_payment_method_momo" value="Thanh toán MoMo">
                      <label class="form-check-label" for="checkout_payment_method_momo">
                        Thanh toán MoMo
                      </label>
                      <div class="logo-method">
                        <img src="{{ asset('images/momo.png') }}" alt="">
                      </div>
                    </div>
                  </div>
                  <div class="policy-text">
                    Dữ liệu cá nhân của bạn sẽ được sử dụng để xử lý đơn đặt hàng của bạn, hỗ trợ trải nghiệm của bạn trong suốt quá trình này
                    trang web và cho các mục đích khác được mô tả trong <a href="terms.html" target="_blank">sự riêng tư
                      chính sách</a>.
                  </div>
                </div>
              <button id="checkoutButton" type="submit" class="btn btn-primary btn-checkout">ĐẶT HÀNG</button>
            </div>
          </div>
        </div>
      </form>
    </section>
  </main>
  <script>
// Mảng lưu trữ showroom gần nhất
let nearestShowrooms = [];

// Lắng nghe sự kiện blur của ô địa chỉ
document.getElementById('address').addEventListener('blur', function (event) {
    var address = event.target.value;

    // Sử dụng Nominatim API từ OpenStreetMap
    if (address.length > 5){
      var geocodeUrl = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}&addressdetails=1&countrycodes=VN`;

    fetch(geocodeUrl)
        .then(response => response.json())
        .then(data => {
            if (data && data.length > 0) {
                // Lấy vĩ độ và kinh độ
                var lat = data[0].lat;
                var lon = data[0].lon;

                // Cập nhật tọa độ vào các trường ẩn
                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lon;

                // Gọi API để tìm showroom gần nhất với tọa độ đã lấy
                findNearestShowrooms(lat, lon);
            } else {
                console.log("Không thể tìm thấy địa chỉ. Vui lòng thử lại.");
            }
        })
        .catch(error => {
            console.error("Error fetching geocoding data:", error);
        });
      }
});

// Hàm tìm showroom gần nhất
function findNearestShowrooms(lat, lon) {
    var checkoutShowroomRoute = @json(route('checkout.showroom'));

    // Tạo URL API tìm showroom gần nhất
    var url = `${checkoutShowroomRoute}?lat=${lat}&lon=${lon}`;

    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data && data.showrooms) {
                var showroomList = data.showrooms;

                // Sắp xếp showroom theo khoảng cách từ gần đến xa
                showroomList.sort((a, b) => a.distance - b.distance);

                // Giới hạn chỉ 3 showroom gần nhất
                 nearestShowrooms = showroomList.slice(0, 1);

                // Hiển thị các showroom gần nhất
                if (nearestShowrooms.length > 0) {
                    let showroomListHTML = '';
                    nearestShowrooms.forEach(showroom => {
                        // Kiểm tra nếu khoảng cách hợp lệ và không phải là null
                        if (showroom.distance !== null && showroom.distance !== undefined) {
                            showroomListHTML += `<p>Showroom gần nhất: ${showroom.name}, Khoảng cách: ${showroom.distance.toFixed(2)} km</p>`;
                        }
                    });
                    document.getElementById('showrooms').innerHTML = showroomListHTML;
                } else {
                    alert("Không tìm thấy showroom gần nhất.");
                }
            }
        })
        .catch(error => {
            console.error("Error finding nearest showrooms:", error);
        });
}


// Lấy thông tin showroom gần nhất khi người dùng nhấn thanh toán
document.getElementById('checkoutForm').addEventListener('submit', function(event) {
    const cityAddress = document.getElementById('address').value.trim().toLowerCase();
    const specificAddress = document.getElementById('specificAddress').value.trim().toLowerCase();
    let isValid = true;

    // Kiểm tra nếu các trường đều đã được điền
    if (!cityAddress) {
        toastr.error("Vui lòng nhập Địa Chỉ Thành Phố.", "Lỗi");
        isValid = false;
    }

    if (!specificAddress) {
        toastr.error("Vui lòng nhập Địa chỉ cụ thể.", "Lỗi");
        isValid = false;
    }

    // Kiểm tra xem quận/thành phố có trong địa chỉ cụ thể hay không
    if (isValid && !specificAddress.includes(cityAddress)) {
        toastr.error("Tỉnh hoặc thành phố phải trùng với địa chỉ thành phố", "Lỗi");
        isValid = false;
    }

    // Nếu có lỗi thì ngăn chặn gửi form
    if (!isValid) {
        event.preventDefault();
    } else {
        toastr.success("Thông tin hợp lệ! Đang gửi...", "Thành công");

        // Thêm thông tin showroom vào form trước khi gửi
        const showroomDataInput = document.createElement('input');
        showroomDataInput.type = 'hidden';
        showroomDataInput.name = 'nearest_showrooms';
        showroomDataInput.value = JSON.stringify(nearestShowrooms); // Chuyển đổi mảng showroom thành chuỗi JSON
        this.appendChild(showroomDataInput);
    }
});



</script>
@endsection

