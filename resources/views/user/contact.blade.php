@extends('user.layouts.app')
@section('title', 'Liên hệ')
@section('content')
    <main class="pt-135">
        <div class="mb-4 pb-4"></div>
        <section class="contact-us container">
            <div class="mw-930">
                <h2 class="page-title">LIÊN HỆ VỚI CHÚNG TÔI</h2>
            </div>
        </section>

        <hr class="mt-2 text-secondary " />
        <div class="mb-4 pb-4"></div>

        <section class="contact-us container">
            <div class="icon_contact row">
                <div class="col-4 d-flex align-items-center justify-content-center">
                    <div class="image_icon">
                        <img src="{{ asset('images/icon/call_icon.png') }}" alt="">
                    </div>
                    <div class="info_contact ms-4">
                        <p>Mua hàng: 0353397312</p>
                        <p>Bảo hành: 0353397312</p>
                    </div>
                </div>
                <div class="col-4 d-flex align-items-center justify-content-center">
                    <div class="image_icon">
                        <img src="{{ asset('images/icon/email_icon.png') }}" alt="">
                    </div>
                    <div class="info_contact ms-4">
                        <p>contact@TuneNest.com</p>
                        <p>support@TuneNest.com</p>
                    </div>
                </div>
                <div class="col-4 d-flex align-items-center justify-content-center">
                    <div class="image_icon">
                        <img src="{{ asset('images/icon/location_icon.png') }}" alt="">
                    </div>
                    <div class="info_contact info_contact_showroom ms-4">
                        <p>- 1 Nguyễn Huệ, Bến Nghé, Quận 1, TP HCM</p>
                        <p>- 42 Bạch Đằng, Hải Châu, Đà Nẵng</p>
                        <p>- Trích Sài ,Tây Hồ ,Hà Nộ</p>
                    </div>
                </div>
                {{-- <div class="col-3 d-flex align-items-center justify-content-center">
                    <div class="image_icon">
                        <img src="{{ asset('images/icon/calendar_icon.png') }}" alt="">
                    </div>
                    <div class="info_contact ms-4">
                        <p>0353397312</p>
                    </div>
                </div> --}}
            </div>

            <div class="mb-4 pb-4"></div>

            <div class="mw-930 row">
                <div class="contact-us__form col-lg-6 col-sm-6">
                    <form name="contact-us-form" class="needs-validation" novalidate method="POST" action="{{ route('contact.post') }}">
                        @csrf
                        <h3 class="mb-5">Thông tin liên hệ của bạn</h3>

                        <!-- Tên -->
                        <div class="form-floating my-4">
                            <input type="text" class="form-control" name="name" placeholder="Tên *" value="{{ old('name') }}">
                            <label for="contact_us_name">Tên *</label>
                            @if ($errors->has('name'))
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            @endif
                        </div>

                        <!-- Số điện thoại -->
                        <div class="form-floating my-4">
                            <input type="text" class="form-control" name="phone" placeholder="Số điện thoại *" value="{{ old('phone') }}">
                            <label for="contact_us_phone">Số điện thoại *</label>
                            @if ($errors->has('phone'))
                                <span class="text-danger">{{ $errors->first('phone') }}</span>
                            @endif
                        </div>

                        <!-- Địa chỉ email -->
                        <div class="form-floating my-4">
                            <input type="email" class="form-control" name="email" placeholder="Địa chỉ email *" value="{{ old('email') }}">
                            <label for="contact_us_email">Địa chỉ email *</label>
                            @if ($errors->has('email'))
                                <span class="text-danger">{{ $errors->first('email') }}</span>
                            @endif
                        </div>

                        <!-- Tin nhắn -->
                        <div class="my-4">
                            <textarea class="form-control form-control_gray" name="comment" placeholder="Tin nhắn của bạn" cols="30" rows="8" >{{ old('comment') }}</textarea>
                            @if ($errors->has('comment'))
                                <span class="text-danger">{{ $errors->first('comment') }}</span>
                            @endif
                        </div>

                        <!-- Nút gửi -->
                        <div class="my-4">
                            <button type="submit" class="btn btn-primary">Gửi</button>
                        </div>
                    </form>

                </div>
                <div class="col-lg-6 col-sm-6 map">
                    <h3>Địa chỉ kho tổng TuneNest</h3>
                    <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d7836.888243204607!2d106.6265813!3d10.853786!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752b6c59ba4c97%3A0x535e784068f1558b!2zVHLGsOG7nW5nIENhbyDEkeG6s25nIEZQVCBQb2x5dGVjaG5pYw!5e0!3m2!1sen!2s!4v1730710965232!5m2!1sen!2s" width="100%" height="80%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </section>

        {{-- <section class="contact-us container mw-930">
            <h3 class="mw-930">Bản Đồ Địa Điểm</h3>
            <div id="mw-930"><iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d7836.888243204607!2d106.6265813!3d10.853786!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752b6c59ba4c97%3A0x535e784068f1558b!2zVHLGsOG7nW5nIENhbyDEkeG6s25nIEZQVCBQb2x5dGVjaG5pYw!5e0!3m2!1sen!2s!4v1730710965232!5m2!1sen!2s" width="100%" height="auto" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe></div>
        </section> --}}
    </main>
@endsection
