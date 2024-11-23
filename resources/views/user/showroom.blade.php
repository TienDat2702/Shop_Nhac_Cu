@extends('user.layouts.app')
@section('title', 'TuneNest - Bản đồ showroom')
@section('content')
<h2><strong style="margin: 255px;">Hệ Thống Showroom</strong></h2>
<hr>
<div class="container mt-5">
    <div class="row d-flex justify-content-center" style="height: 500px;">
        <!-- Cột bản đồ -->
        <div class="col-md-6 d-flex flex-column" style="height: 100%;">
            <h4>Bản Đồ Showroom</h4>
            <div id="map" style="flex-grow: 1;"></div>
        </div>

        <!-- Cột danh sách showroom -->
        <div class="col-md-4 d-flex flex-column" style="height: 100%;">
            <h4>Danh Sách Showroom</h4>
            <div class="list-group flex-grow-0">
                <!-- Tiêu đề "Danh Sách Showroom Hiện Có" -->
                <a href="#" class="list-group-item list-group-item-action active text-center" aria-current="true">
                  Danh Sách Showroom Hiện Có
                </a>
            </div>

            <!-- Phần danh sách showroom cuộn -->
            <div class="list-group" style="flex-grow: 1; max-height: 400px; overflow-y: auto;">
                @foreach($showrooms as $showroom)
                <div class="list-group-item list-group-item-action">
                    <ul class="list-unstyled">
                        <li><strong class="showroom-name">{{$showroom->name}}</strong></li>
                        <li>Địa Chỉ: {{$showroom->address}}</li>
                        <li>Số Điện Thoại: {{$showroom->phone}}</li>
                    </ul>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Thêm CSS cho Leaflet -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />

<!-- Thêm JavaScript cho Leaflet -->
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>

<script>
    // Khởi tạo bản đồ, đặt trung tâm tại Việt Nam
    var map = L.map('map').setView([14.058324, 108.277199], 6.4);

    // Thêm tile OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 25,
    }).addTo(map);

    // Lấy danh sách showroom từ server (được truyền từ Laravel)
    var showrooms = @json($showrooms);

    // Tạo icon tùy chỉnh với Font Awesome
    var customIcon = L.divIcon({
        className: 'fa-solid fa-store',  // Đây là class Font Awesome cho biểu tượng "map-marker"
        iconSize: [50, 50],             // Kích thước của icon
        iconAnchor: [16, 32],           // Chỉ định điểm gốc của icon
        popupAnchor: [0, -32]           // Vị trí của popup so với icon
    });

    // Lặp qua danh sách showroom và thêm marker vào bản đồ
    showrooms.forEach(function(showroom) {
        // Kiểm tra nếu có dữ liệu latitude và longitude
        if (showroom.latitude && showroom.longitude) {
L.marker([showroom.latitude, showroom.longitude], {icon: customIcon})  // Thêm icon Font Awesome vào marker
                .addTo(map)
                .bindPopup(
                    `<strong>${showroom.name}</strong><br>${showroom.address}` // Thông tin hiển thị khi click vào marker
                );
        }
    });
</script>

@endsection

<!-- Thêm CSS để bỏ dấu chấm của ul, li và in hoa tên showroom -->
@section('styles')
    <style>
        .list-unstyled {
            padding-left: 0;
        }
        .showroom-name {
            text-transform: uppercase;
            text-align: center; /* Căn giữa tên showroom */
        }
        .text-center {
            text-align: center; /* Căn giữa các nội dung có class text-center */
        }
    </style>
@endsection