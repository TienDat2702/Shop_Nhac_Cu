

<li><a href="{{ route('customer.profile') }}" class="menu-link menu-link_us-s {{ Route::currentRouteName() == 'customer.profile' ? 'menu-link_active' : '' }}">Trang chủ</a></li>
<li><a href="{{ route('customer.orders') }}" class="menu-link menu-link_us-s {{ Route::currentRouteName() == 'customer.orders' ? 'menu-link_active' : '' }}">Tất cả đơn hàng</a></li>
<li><a href="{{ route('customer.update.profile') }}" class="menu-link menu-link_us-s {{ Route::currentRouteName() == 'customer.update' ? 'menu-link_active' : '' }}">Thông tin cá nhân</a></li>
<li><a href="{{ route('wishlist.index') }}" class="menu-link menu-link_us-s {{ Route::currentRouteName() == 'wishlist.index' ? 'menu-link_active' : '' }}">Sản phẩm yêu thích</a></li>
<li><a href="{{ route('customer.orders.history') }}" class="menu-link menu-link_us-s {{ Route::currentRouteName() == 'customer.orders.history' ? 'menu-link_active' : '' }}">Lịch sử mua</a></li>
<li><a href="{{ route('customer.logout') }}" class="menu-link menu-link_us-s">Đăng xuất</a></li>
