@extends('user.layouts.app')
@section('title', $categories->name)
@section('content')
    <main class="pt-135">
        <section class="post-single container">
            <div class="advisory_latest post_banner">
                @if (!empty($post_view->first()))
                <div class="row-10 post-view">
                    <div class="col-left">
                        <div class="item ">
                            <div class="image"><a
                                    href="{{ route('post.detail', $post_view->first()->slug) }}"><img
                                        src="{{ asset('uploads/posts/posts/' . $post_view->first()->image) }}"
                                        alt="{{ $post_view->first()->title }}"
                                        title="{{ $post_view->first()->title }}"></a>
                            </div>
                            <div class="caption">
                                <div class="name"><a
                                        href="{{ route('post.detail', $post_view->first()->slug) }}">
                                        <h3>{{ $post_view->first()->title }}</h3>
                                    </a></div>
                                <div class="description">{!! $post_view->first()->description !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-right">
                        @php
                            $nextTwoPosts = $post_view->skip(1)->take(2);
                        @endphp
                        @foreach ($nextTwoPosts as $item)
                            <div class="item ">
                                <div class="image"><a
                                        href="{{ route('post.detail', $item->slug) }}"><img
                                            src="{{ asset('uploads/posts/posts/' . $item->image) }}"
                                            alt="{{ $item->title }}"
                                            title="{{ $item->title }}"></a>
                                </div>
                                <div class="caption">
                                    <div class="name"><a
                                            href="{{ route('post.detail', $item->slug) }}">
                                            <h3>{{ $item->title }}</h3>
                                        </a></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
                <nav class="nav-post">
                    @php
                        $lastSegment = last(request()->segments());
                    @endphp
                    <ul class="list-unstyled d-flex justify-content-center">
                        @foreach ($postCategoriesParent as $index => $category)
                            <li style="{{ $index > 0 && $index < count($postCategoriesParent) ? 'border-left: 1px solid gray' : '' }}" class="navigation__item">
                                <a href="{{ route('post.category',$category->slug ) }}" class="navigation__link post__link {{ $category->slug == $lastSegment ? 'active' : ''}}">{{ $category->name }}</a>
                            </li>
                        @endforeach    
                    </ul>
                </nav>
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        @foreach ($post_categories as $category)
                            <div class="list_advisory">
                                <h2 class="title_home2">{{ $category->name }}</h2>
                                <div class="xemtatca">
                                    <a class="post_category" href="{{ route('post.category.all', $category->slug) }}">Xem tất cả</a>
                                </div>
                                <div class="row">
                                    @foreach ($category->posts as $post)
                                        <div class="col-lg-3 col-md-4 col-sm-6 col-12 list-post mb-3">
                                            <div class="item">
                                                <div class="image">
                                                    <a href="{{ route('post.detail', $post->slug) }}">
                                                        <img src="{{ asset('uploads/posts/posts/' . $post->image) }}" alt="{{ $post->title }}" title="{{ $post->title }}">
                                                    </a>
                                                    @if ($categories->name == 'Video')
                                                        <div class="icon_play">
                                                            <a href="{{ route('post.detail', $post->slug) }}">
                                                                <i class="fa fa-play" aria-hidden="true"></i>
                                                            </a>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="name name_post">
                                                    <a href="{{ route('post.detail', $post->slug) }}">
                                                        <h3 class="title">{{ $post->title }}</h3>
                                                    </a>
                                                </div>
                                                <div class="read-more d-flex align-items-center justify-content-between">
                                                    <a href="{{ route('post.detail', $post->slug) }}">Đọc thêm</a>
                                                    <span>{{ date('d/m/Y', strtotime($post->created_at)) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="flex items-center justify-center flex-wrap gap-10 wgp-pagination">
                    {{ $post_categories->appends(request()->all())->links('pagination::bootstrap-5') }}
                </div>
        </section>
       
    </main>
@endsection
