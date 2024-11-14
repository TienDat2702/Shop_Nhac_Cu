@extends('user.layouts.app')
@section('title', 'Thương hiệu ' . $brand->name)
@section('content')
    <main class="pt-90">
        <section class="shop-main container d-flex pt-4 pt-xl-5">
            <h2>{{ $brand->name }}</h2>
        </section>
        <section class="shop-main container d-flex pt-4 pt-xl-5">
            @foreach ($products as $product)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="product-card product-card_style3 mb-3 mb-md-4 mb-xxl-5">
                        <div class="pc__img-wrapper">
                            <a href="{{ route('product.detail', $product->slug) }}">
                                <img loading="lazy" src="{{ asset('uploads/products/product/' . $product->image) }}"
                                    width="330" height="400" alt="{{ $product->name }}" class="pc__img">
                            </a>
                        </div>

                        <div class="pc__info position-relative">
                            <h6 class="pc__title">{{ $product->name }}</h6>
                            <div class="product-card__price d-flex align-items-center">
                                @if ($product->price_sale == null)
                                    <span class="money price text-secondary">{{ number_format($product->price) }}
                                        VNĐ</span>
                                @else
                                    <span class="money price-old">{{ number_format($product->price) }} VNĐ</span>
                                    <span class="money price text-secondary">{{ number_format($product->price_sale) }}
                                        VNĐ</span>
                                @endif
                            </div>

                            <div
                                class="anim_appear-bottom position-absolute bottom-0 start-0 d-none d-sm-flex align-items-center bg-body">
                                <a href="#" data-url="{{ route('cart.add', $product->id) }}"
                                    class="btn-link btn-link_lg me-4 text-uppercase fw-medium add-to-cart"
                                    data-aside="cartDrawer" title="Add To Cart">Thêm Giỏ Hàng</a>
                                <a href="{{ route('product.detail', $product->slug) }}"
                                    class="btn-link btn-link_lg me-4 text-uppercase fw-medium" title="Quick view">
                                    <span class="">Xem Ngay</span>
                                </a>
                                <button class="pc__btn-wl bg-transparent border-0 js-add-wishlist" title="Add To Wishlist">
                                    <svg width="16" height="16" viewBox="0 0 20 20" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <use href="#icon_heart" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

        </section>
    </main>
@endsection
