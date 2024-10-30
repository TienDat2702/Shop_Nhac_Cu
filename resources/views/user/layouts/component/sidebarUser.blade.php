<li><a href="{{ route('customer.profile') }}" class="menu-link menu-link_us-s {{ Route::currentRouteName() == 'customer.profile' ? 'menu-link_active' : '' }}">Dashboard</a></li>
<li><a href="{{ route('customer.orders') }}" class="menu-link menu-link_us-s {{ Route::currentRouteName() == 'customer.orders' ? 'menu-link_active' : '' }}">Orders</a></li>
<li><a href="account-address.html" class="menu-link menu-link_us-s ">Addresses</a></li>
<li><a href="account-details.html" class="menu-link menu-link_us-s">Account Details</a></li>
<li><a href="account-wishlist.html" class="menu-link menu-link_us-s">Wishlist</a></li>
<li><a href="{{ route('customer.orders.history') }}" class="menu-link menu-link_us-s {{ Route::currentRouteName() == 'customer.orders.history' ? 'menu-link_active' : '' }}">Lịch sử mua</a></li>
<li><a href="{{ route('customer.logout') }}" class="menu-link menu-link_us-s">Logout</a></li>
