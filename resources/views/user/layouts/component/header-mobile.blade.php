<div class="header-mobile header_sticky">
    <div class="container d-flex align-items-center h-100">
        <a class="mobile-nav-activator d-block position-relative" href="#">
            <svg class="nav-icon" width="25" height="18" viewBox="0 0 25 18"
                xmlns="http://www.w3.org/2000/svg">
                <use href="#icon_nav" />
            </svg>
            <button class="btn-close-lg position-absolute top-0 start-0 w-100"></button>
        </a>

        <div class="logo">
            <a href="{{ route('home.index') }}">
                <img src="{{ asset('assets/images/logo.jpg') }}" alt="Uomo" class="logo__image d-block" />
            </a>
        </div>

        <a href="#" class="header-tools__item header-tools__cart js-open-aside" data-aside="cartDrawer">
            <svg class="d-block" width="20" height="20" viewBox="0 0 20 20" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <use href="#icon_cart" />
            </svg>
            <span class="cart-amount d-block position-absolute js-cart-items-count">3</span>
        </a>
    </div>

    @include('user.layouts.component.menu')
</div>