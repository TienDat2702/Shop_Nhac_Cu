@extends('user.layouts.app')
@section('title', 'Giỏ Hàng')
@section('content')
    <main class="pt-90">
        <div class="mb-4 pb-4"></div>
        <section class="shop-checkout container">
            <h2 class="page-title">@yield('title')</h2>
            <div class="checkout-steps">
                <a href="cart.html" class="checkout-steps__item active">
                    <span class="checkout-steps__item-number">01</span>
                    <span class="checkout-steps__item-title">
                        <span>GIỏ hàng</span>
                        <em>Quản lý danh sách mặt hàng của bạn</em>
                    </span>
                </a>
                <a href="checkout.html" class="checkout-steps__item">
                    <span class="checkout-steps__item-number">02</span>
                    <span class="checkout-steps__item-title">
                        <span> Vận chuyển và thanh toán</span>
                        <em>Kiểm tra danh sách mặt hàng của bạn</em>
                    </span>
                </a>
                <a href="order-confirmation.html" class="checkout-steps__item">
                    <span class="checkout-steps__item-number">03</span>
                    <span class="checkout-steps__item-title">
                        <span>Xác nhận</span>
                        <em>Xem xét và gửi đơn đặt hàng của bạn</em>
                    </span>
                </a>
            </div>
            <div class="shopping-cart">
                @if (session('carts') && count(session('carts')) > 0)
                <div class="cart-table__wrapper">
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th>Sản phẩm</th>
                                <th></th>
                                <th>giá</th>
                                <th>Số lượng</th>
                                <th>Tổng Tiền</th>
                                <th></th>
                            </tr>
                        </thead>
                        <form action="{{ route('cart.update') }}" method="POST">
                        @csrf
                        <tbody>
                            @foreach ($carts as $item)
                            <tr>
                                <td>
                                    <div class="shopping-cart__product-item">
                                        <img loading="lazy" src="{{ asset('uploads/products/product/' . $item['image']) }}" width="120"
                                            height="120" alt="" />
                                    </div>
                                </td>
                                <td>
                                    <div class="shopping-cart__product-item__detail">
                                        <h4>{{ $item['name'] }}</h4>
                                        {{-- <ul class="shopping-cart__product-item__options">
                                            <li>Color: Yellow</li>
                                            <li>Size: L</li>
                                        </ul> --}}
                                    </div>
                                </td>
                                <td>
                                    <span class="shopping-cart__product-price">
                                        {{ number_format($item['price'], 0, '.', ',') . ' VNĐ'}}
                                    </span>
                                </td>
                                <td>
                                    <div class="qty-control position-relative">
                                        <input type="number" name="quantity[{{ $item['id'] }}]" value="{{ $item['quantity'] }}" min="1"
                                            class="qty-control__number text-center">
                                        <div class="qty-control__reduce">-</div>
                                        <div class="qty-control__increase">+</div>
                                    </div>
                                </td>
                                <td>
                                    <span id="subtotal" class="shopping-cart__subtotal">{{ $item['subtotal'] }}</span>
                                </td>
                                <td>
                                    
                                    <button type="submit" class="remove-cart" formaction="{{ route('cart.remove', $item['id']) }}" formmethod="POST">
                                        @csrf
                                        <svg width="10" height="10" viewBox="0 0 10 10" fill="#767676"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M0.259435 8.85506L9.11449 0L10 0.885506L1.14494 9.74056L0.259435 8.85506Z" />
                                            <path
                                                d="M0.885506 0.0889838L9.74057 8.94404L8.85506 9.82955L0 0.97449L0.885506 0.0889838Z" />
                                        </svg>
                                    </button>
                                    
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="cart-table-footer">
                        {{-- <form action="#" class="position-relative bg-body"> --}}
                            <input class="form-control" type="text" name="coupon_code" placeholder="Mã Giảm giá">
                            {{-- <input class="btn-link fw-medium position-absolute top-0 end-0 h-100 px-4" type="submit"
                                value="APPLY COUPON"> --}}
                        {{-- </form> --}}
                        <button type="submit" class="btn btn-light btn-update-cart">CẬP NHẬT</button>
                    </div>
                    </form>
                </div>
                <div class="shopping-cart__totals-wrapper">
                    <div class="sticky-content">
                        <div class="shopping-cart__totals">
                            <h3>Cart Totals</h3>
                            <table class="cart-totals">
                                <tbody>
                                    <tr>
                                        <th>Tổng phụ</th>
                                        <td>{{ number_format($total, 0, '.', ',') . ' VNĐ'}}</td>
                                    </tr>
                                    <tr>
                                        <th>Shipping</th>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input form-check-input_fill" type="checkbox"
                                                    value="" id="free_shipping">
                                                <label class="form-check-label" for="free_shipping">Free shipping</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input form-check-input_fill" type="checkbox"
                                                    value="" id="flat_rate">
                                                    <label class="form-check-label" for="flat_rate">Flat rate: $49</label>
                                                </div>
                                            <div class="form-check">
                                                <input class="form-check-input form-check-input_fill" type="checkbox"
                                                    value="" id="local_pickup">
                                                <label class="form-check-label" for="local_pickup">Local pickup:
                                                    $8</label>
                                            </div>
                                            <div>Shipping to AL.</div>
                                            <div>
                                                <a href="#" class="menu-link menu-link_us-s">CHANGE ADDRESS</a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>VAT</th>
                                        <td>$19</td>
                                    </tr>
                                    <tr>
                                        <th>Tổng thanh toán</th>
                                        <td> {{ number_format($total, 0, '.', ',') . ' VNĐ'}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="mobile_fixed-btn_wrapper">
                            <div class="button-wrapper container">
                                <a href="checkout.html" class="btn btn-primary btn-checkout">PROCEED TO CHECKOUT</a>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                    <div class="cart-null">
                        <img src="{{ asset('images/carts-null.png') }}" alt="">
                        {{-- <a class="btn-comeback" href="{{ route('home.index') }}"> Mua ngay </a> --}}
                    </div>
                @endif
            </div>
        </section>
    </main>
@endsection
