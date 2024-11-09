@extends('user.layouts.app')
@section('title', $categories->name)
@section('content')
    <main class="pt-90">
        <div class="breadcrumbs">
            <div class="container">
                <ul>
                    <li><a href="{{ route('home.index') }}">Trang chủ</a> <i class="fa fa-angle-right" aria-hidden="true"></i>
                    </li>
                    <li><a href="{{ route('post.category',$categories->parent->slug ?? $categories->slug) }}">{{ $categories->parent->name ?? $categories->name}}</a><i class="fa fa-angle-right" aria-hidden="true"></i></li>
                    <li><a href="#">{{ $categories->name }}</a></li>
                </ul>
            </div>
        </div>

        <section class="post-single container">
                <h3 class="mb-4">{{ $categories->name }}</h3>
                <div class="row">
                    <div class="col-lg-9 cl-l">
                            @foreach ($posts as $post)
                            <div class="row post-category mt-3">
                                <div class="col-3">
                                    <div class="image">
                                        <a href="{{ route('post.detail',$post->slug) }}">
                                            <img src="{{ asset('uploads/posts/posts/' . $post->image  ) }}" alt="">
                                        </a>
                                        @if ($categories->parent->name == 'Video')
                                            <div class="icon_play">
                                                <a href="{{ route('post.detail',$post->slug) }}">
                                                    <i class="fa fa-play" aria-hidden="true"></i>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-9">
                                    <div class="post-category-content">
                                        <a href="{{ route('post.detail',$post->slug) }}" class="title">
                                            <h3>{{ $post->title }}</h3>
                                        </a>
                                        <a href="{{ route('post.detail',$post->slug) }}" class="post-category-description">
                                            <p>{!! $post->description !!}</p>
                                        </a>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span class="text-gray"><span class="me-3">{{ date('d/m/Y', strtotime($post->created_at)) }}</span>  {{ $post->view }} lượt xem </span>
                                            <a href="{{ route('post.detail',$post->slug) }}"><span>Xem chi tiết</span> <i class="fa-solid fa-angle-right"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="col-lg-3 cl-r">
                            @if (count($categorie_child) > 0)
                                <div class="box">
                                    <div class="title3 ">Danh mục liên quan</div>
                                    <ul style="list-style: none">
                                        @foreach ($categorie_child as $item)
                                            <li><a href="{{ route('post.category.all', $item->slug) }}">{{$item->name}}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <div class="box">
                                <div class="title3 ">Bài viết nổi bật nhất</div>
                                <div class="list-news list_news_top list_ad_top">
                                    @foreach ($post_hots as $item)
                                        
                                    <div class="item">
                                        <div class="item_l">
                                            <div class="image">
                                                <a href="{{ route('post.detail', $item->slug) }}">
                                                    <img src="{{ asset('uploads/posts/posts/'. $item->image) }}" alt="{{ $item->title }}" title="{{ $item->title }}">
                                                </a>
                                            </div>
                                        </div>
                                        <div class="item_r">
                                            <div class="caption">
                                                <div class="name"><a href="http://127.0.0.1:8000/post/detail/mo-rong-kho-tang-am-thanh-voi-bo-tieng-gx-10-va-cac-san-pham-boss-moi-nhat-tren-boss-exchange">
                                                    <h3>{{ $item->title }}</h3>
                                                    <span class="date_add text-gray">{{ $post->view }} lượt xem </span>
                                                </a></div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    @endforeach
                                </div>
                            </div>
                            
                        </div>
                </div>
               
               <div class="flex items-center justify-between flex-wrap gap-10 wgp-pagination">
                {{ $posts->appends(request()->all())->links('pagination::bootstrap-5') }}
                </div>
        </section>
    </main>
@endsection
