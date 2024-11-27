<footer class="footer footer_type_2 bg-dark text-white">
    <div class="footer-middle container">
        <div class="row row-cols-lg-4 row-cols-2">
            <div class="footer-column footer-store-info col-12 mb-4 mb-lg-0">
                <div class="logo">
                    <a href="{{ route('home.index') }}">
                        <img src="{{ asset('images/logo/logo_light.png') }}" alt="TuneNest" class="logo__image d-block" />
                    </a>
                </div>
                <h6 class="sub-menu__title text-uppercase  text-white">Thông tin Liên hệ TuneNests</h6>
                <p class="m-0">Địa chỉ kho tổng: 125/5 An Phú Đông , Quận 12</p>
                <p class="footer-address">Thời gian phục vụ: 8h-22h</p>
                <p class="m-0"><strong class="fw-medium">Email liên hệ: <span class="footer_text_contact">contact@TuneNest.com</span></strong></p>
                <p class="m-0"><strong class="fw-medium">Email hỗ trợ: <span class="footer_text_contact">support@TuneNest.com</span></strong></p>
                <p class="m-0"><strong class="fw-medium">Điện thoại mua hàng: <span class="footer_text_contact">0353397312</span></strong></p>
                <p><strong class="fw-medium">Khiếu nại, Bảo hành: <span class="footer_text_contact">0353397312</span></strong></p>
             
            </div>
            
            <div class="footer-column footer-menu mb-4 mb-lg-0">
                <h6 class="sub-menu__title text-uppercase  text-white">Thông tin</h6>
                <ul class="sub-menu__list list-unstyled">
                    <li class="sub-menu__item"><a href="{{ route('about') }}" class="menu-link menu-link_us-s">Về Chúng Tôi</a></li>
                    <li class="sub-menu__item"><a href="{{ route('contact') }}" class="menu-link menu-link_us-s">Tin tức</a></li>
                    <li class="sub-menu__item"><a href="{{ route('contact') }}" class="menu-link menu-link_us-s">Liên Hệ</a></li>
                    <li class="sub-menu__item"><a href="{{ route('showrooms.map') }}" class="menu-link menu-link_us-s">Showroom</a></li>
                </ul>
            </div>
            
            <div class="footer-column footer-menu mb-4 mb-lg-0">
                <h6 class="sub-menu__title text-uppercase">Danh Mục Sản Phẩm</h6>
                <ul class="sub-menu__list list-unstyled">
                @foreach ($categorie_parent_product as $category)
                    <li class="sub-menu__item">
                        <a href="{{ route('shop.category', $category->slug) }}" class="menu-link menu-link_us-s">{{ $category->name }}</a>
                    </li>
                @endforeach

                    <li class="sub-menu__item"><a href="{{ route('shop.index') }}" class="menu-link menu-link_us-s">Tất cả Sản Phẩm</a></li>
                </ul>
            </div>
            
            <div class="footer-column footer-menu mb-4 mb-lg-2">
                <h6 class="sub-menu__title text-uppercase">Các mạng xã hội </h6>
                <ul class="social-links list-unstyled d-flex flex-wrap mb-0">
                    {{-- <li><a href="#" class="footer__social-link d-block"><svg class="svg-icon svg-icon_facebook" width="9" height="15" viewBox="0 0 9 15" xmlns="http://www.w3.org/2000/svg"><use href="#icon_facebook" /></svg></a></li>
                    <li><a href="#" class="footer__social-link d-block"><svg class="svg-icon svg-icon_twitter" width="14" height="13" viewBox="0 0 14 13" xmlns="http://www.w3.org/2000/svg"><use href="#icon_twitter" /></svg></a></li>
                    <li><a href="#" class="footer__social-link d-block"><svg class="svg-icon svg-icon_instagram" width="14" height="13" viewBox="0 0 14 13" xmlns="http://www.w3.org/2000/svg"><use href="#icon_instagram" /></svg></a></li>
                    <li><a href="#" class="footer__social-link d-block"><svg class="svg-icon svg-icon_youtube" width="16" height="11" viewBox="0 0 16 11" xmlns="http://www.w3.org/2000/svg"><path d="M15.0117 1.8584C14.8477 1.20215 14.3281 0.682617 13.6992 0.518555C12.5234 0.19043 7.875 0.19043 7.875 0.19043C7.875 0.19043 3.19922 0.19043 2.02344 0.518555C1.39453 0.682617 0.875 1.20215 0.710938 1.8584C0.382812 3.00684 0.382812 5.46777 0.382812 5.46777C0.382812 5.46777 0.382812 7.90137 0.710938 9.07715C0.875 9.7334 1.39453 10.2256 2.02344 10.3896C3.19922 10.6904 7.875 10.6904 7.875 10.6904C7.875 10.6904 12.5234 10.6904 13.6992 10.3896C14.3281 10.2256 14.8477 9.7334 15.0117 9.07715C15.3398 7.90137 15.3398 5.46777 15.3398 5.46777C15.3398 5.46777 15.3398 3.00684 15.0117 1.8584ZM6.34375 7.68262V3.25293L10.2266 5.46777L6.34375 7.68262Z" /></svg></a></li> --}}
                    <li>
                        <a href="#" class="footer__social-link d-block">
                            <img src="{{ asset('images/socal/icon_facebook.png') }}" alt="">
                        </a>
                    </li>
                    <li>
                        <a href="#" class="footer__social-link d-block">
                            <img src="{{ asset('images/socal/icon_youtube.png') }}" alt="">
                        </a>
                    </li>
                    <li>
                        <a href="#" class="footer__social-link d-block">
                            <img src="{{ asset('images/socal/icon_zalo.png') }}" alt="">
                        </a>
                    </li>
                </ul>
                <ul class="sub-menu__list list-unstyled ">
                    <li class="sub-menu__item"><h6 class="sub-menu__title text-uppercase">Hệ thống cửa hàng</h6></a></li>
                    @foreach ($list_showroom as $showroom)
                        <li class="sub-menu__item"><a href="{{ route('showrooms.map') }}" class="menu-link menu-link_us-s">{{ $showroom->name }}</a></li>
                    @endforeach
                </ul>
                <ul class="sub-menu__list list-unstyled d-flex flex-wrap">
                    <li class="sub-menu__item"><h6 class="sub-menu__title text-uppercase">Phương thức thanh toán</h6></a></li>
                    <li><a href="#" class="pay-link d-block">
                        <img src="{{ asset('images/pays/vnpay.jpg') }}" alt="VNPay" width="50" height="30">
                    </a></li>
                    <li><a href="#" class="pay-link d-block">
                        <img src="{{ asset('images/pays/momo.jpg') }}" alt="Momo" width="50" height="30">
                    </a></li>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="footer-bottom">
        <div class="container d-md-flex align-items-center">
          <span class="footer-copyright me-auto">©2024 Shop bán nhạc cụ TuneNests</span>
          <div class="footer-settings d-md-flex align-items-center">
            <a href="{{ route('page.chinh_sach_bao_hanh') }}">Chính sách bảo hành &amp; bảo trì</a> &nbsp;|&nbsp; <a href="{{ route('page.chinh_sach_giao_hang') }}">Chính sách giao hàng</a>
          </div>
        </div>
      </div>
</footer>