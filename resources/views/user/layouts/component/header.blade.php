
<header id="header" class="header header-fullwidth header-transparent-bg">
    <div class="container">
        <div class="header-desk header-desk_type_1">
            <div class="logo">
                <a href="{{ route('home.index') }}">
                    <img src="{{ asset('images/logo/logo.jpg') }}" alt="TuneNest"
                        class="logo__image d-block" />
                </a>
            </div>

            <nav class="navigation">
                <ul class="navigation__list list-unstyled d-flex">
                    <li class="navigation__item">
                        <a href="{{ route('home.index') }}" class="navigation__link">Home</a>
                    </li>
                    <li class="navigation__item">
                        <a href="
                        {{ route('shop.index') }}
                         " class="navigation__link">Shop</a>
                    </li>
                    <li class="navigation__item">
                        <a href="
                        {{-- {{ route('cart.index') }} --}}
                         " class="navigation__link">Cart</a>
                    </li>
                    <li class="navigation__item">
                        <a href="about.html" class="navigation__link">About</a>
                    </li>
                    <li class="navigation__item">
                        <a href="contact.html" class="navigation__link">Contact</a>
                    </li>
                </ul>
            </nav>

            <div class="header-tools d-flex align-items-center">
                <div class="header-tools__item hover-container">
                    <div class="js-hover__open position-relative">
                        <a class="js-search-popup search-field__actor" href="#">
                            <svg class="d-block" width="20" height="20" viewBox="0 0 20 20"
                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                <use href="#icon_search" />
                            </svg>
                            <i class="btn-icon btn-close-lg"></i>
                        </a>
                    </div>

                    <div class="search-popup js-hidden-content">
                        <form action="#" method="GET" class="search-field container">
                            <p class="text-uppercase text-secondary fw-medium mb-4">Bạn muốn tìm gì?</p>
                            <div class="position-relative">
                                <input class="search-field__input search-popup__input w-100 fw-medium"
                                    type="text" name="search-keyword" placeholder="Search products" />
                                <button class="btn-icon search-popup__submit" type="submit">
                                    <svg class="d-block" width="20" height="20" viewBox="0 0 20 20"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <use href="#icon_search" />
                                    </svg>
                                </button>
                                <button class="btn-icon btn-close-lg search-popup__reset" type="reset"></button>
                            </div>

                            <div class="search-popup__results">
                                <div class="sub-menu search-suggestion">
                                    <h6 class="sub-menu__title fs-base">Quicklinks</h6>
                                    <ul class="sub-menu__list list-unstyled">
                                        <li class="sub-menu__item"><a href="shop2.html"
                                                class="menu-link menu-link_us-s">New Arrivals</a>
                                        </li>
                                        <li class="sub-menu__item"><a href="#"
                                                class="menu-link menu-link_us-s">Dresses</a></li>
                                        <li class="sub-menu__item"><a href="shop3.html"
                                                class="menu-link menu-link_us-s">Accessories</a>
                                        </li>
                                        <li class="sub-menu__item"><a href="#"
                                                class="menu-link menu-link_us-s">Footwear</a></li>
                                        <li class="sub-menu__item"><a href="#"
                                                class="menu-link menu-link_us-s">Sweatshirt</a></li>
                                    </ul>
                                </div>

                                <div class="search-result row row-cols-5"></div>
                            </div>
                        </form>
                    </div>
                </div>

                

                @if (!Auth::guard('customer')->check())
                <div class="header-tools__item hover-container">
                        <a href="{{ route('customer.login') }}" class="header-tools__item">
                            <svg class="d-block" width="20" height="20" viewBox="0 0 20 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <use href="#icon_user" />
                            </svg>
                        </a>
                    </div>
                @else
                    <div class="header-tools__item hover-container">
                        <a href="{{ route('customer.profile') }}" class="header-tools__item">
                            {{-- <svg class="d-block" width="20" height="20" viewBox="0 0 20 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg"> --}}
                                <i class="fa fa-user" style="font-size: 25px;" aria-hidden="true"></i>
                            {{-- </svg> --}}
                        </a>
                    </div>
                @endif
                <a href="
                {{-- {{ route('wishlist.index') }} --}}
                 " class="header-tools__item header-tools__cart">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <use href="#icon_heart" />
                    </svg>
                    {{-- @if (Cart::instance('wishlist')->content()->count() > 0)
                        <span class="cart-amount d-block position-absolute js-cart-items-count">{{ Cart::instance('wishlist')->content()->count() }}</span>
                    @endif --}}

                </a>

                @php
                     $carts = session()->get('carts', [])
                @endphp

                <a href="{{ route('cart.index') }}" class="header-tools__item header-tools__cart">
                    <svg class="d-block" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <use href="#icon_cart"></use>
                    </svg>
                    <span class="cart-amount d-block position-absolute js-cart-items-count">{{ count($carts) }}</span>
                  </a>

                @if (Auth::guard('customer')->check())
                <a href="{{ route('customer.logout') }}" class="header-tools__item"><i class="fa fa-sign-out" style="font-size: 25px;" aria-hidden="true"></i></a>
                @endif
            </div>
        </div>
    </div>
</header>