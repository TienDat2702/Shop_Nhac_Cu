

{{-- <li><a href="{{ route('customer.profile') }}" class="menu-link menu-link_us-s {{ Route::currentRouteName() == 'user.profile' ? 'menu-link_active' : '' }}">Dashboard</a></li>
<li><a href="{{ route('customer.orders') }}" class="menu-link menu-link_us-s {{ Route::currentRouteName() == 'user.orders' ? 'menu-link_active' : '' }}">Orders</a></li> --}}

<li><a href="{{ route('customer.profile') }}" class="menu-link menu-link_us-s {{ Route::currentRouteName() == 'customer.profile' ? 'menu-link_active' : '' }}">Trang chủ</a></li>
<li><a href="{{ route('customer.orders') }}" class="menu-link menu-link_us-s {{ Route::currentRouteName() == 'customer.orders' ? 'menu-link_active' : '' }}">Tất cả đơn hàng</a></li>
<li><a href="account-address.html" class="menu-link menu-link_us-s {{ Route::currentRouteName() == 'customer.orders.history' ? 'menu-link_active' : '' }}">Địa chỉ</a></li>
<li><a href="account-details.html" class="menu-link menu-link_us-s {{ Route::currentRouteName() == 'customer.orders.history' ? 'menu-link_active' : '' }}">Thông tin cá nhân</a></li>
<li><a href="account-wishlist.html" class="menu-link menu-link_us-s {{ Route::currentRouteName() == 'customer.orders.history' ? 'menu-link_active' : '' }}">Sản phẩm yêu thích</a></li>
<li><a href="{{ route('customer.orders.history') }}" class="menu-link menu-link_us-s {{ Route::currentRouteName() == 'customer.orders.history' ? 'menu-link_active' : '' }}">Lịch sử mua</a></li>
<li><a href="{{ route('customer.logout') }}" class="menu-link menu-link_us-s">Đăng xuất</a></li>

{{-- <li><a href="{{ route('user.profile') }}" class="menu-link menu-link_us-s {{ Route::currentRouteName() == 'user.profile' ? 'menu-link_active' : '' }}">Dashboard</a></li>
<li><a href="{{ route('user.orders') }}" class="menu-link menu-link_us-s {{ Route::currentRouteName() == 'user.orders' ? 'menu-link_active' : '' }}">Orders</a></li> --}}
{{-- 
<li><a href="account-address.html" class="menu-link menu-link_us-s ">Địa chỉ</a></li>
<li><a href="account-details.html" class="menu-link menu-link_us-s">Thông tin tài khoản</a></li>
<li><a href="account-wishlist.html" class="menu-link menu-link_us-s">Yêu thích</a></li>
<li><a href="login.html" class="menu-link menu-link_us-s">Đăng xuất</a></li> --}}

