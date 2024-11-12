
(function ($) {
    "use strict";
    var HT = {};
    var _token = $('meta[name="csrf-token"]').attr('content');
    
    HT.addToCart = () => {
        $('.add-to-cart').on('click', function(event){
            event.preventDefault();
            let _this = $(this);
            let urlCart = _this.data('url');
            let quantity = $('#quantity').val();
            $.ajax({
                type: 'POST',
                url: urlCart,
                dataType: 'json',
                data: {
                    quantity: quantity,
                    _token: _token, // Lấy CSRF token
                },
                success: function(data){
                    toastr.success(data.message);
                    // Cập nhật số lượng giỏ hàng
                    $('.js-cart-items-count').text(data.cartCount);
                },
                error: function(){
                    let errorMsg = jqXHR.responseJSON && jqXHR.responseJSON.error ? jqXHR.responseJSON.error : 'Đã có lỗi xảy ra, vui lòng thử lại.';
                    toastr.error(errorMsg);
                }
            });
        })
    }
    
    HT.removeCart = () => {
        $('.remove-cart').on('click', function(event){
            event.preventDefault();
            let _this = $(this);
            let urlCart = _this.data('url');
            let product_id = _this.data('id'); // Lấy ID sản phẩm từ thuộc tính data-id
    
            $.ajax({
                type: 'POST',
                url: urlCart, // Sử dụng đường dẫn tương đối
                data: {
                    _token: _token, // Lấy CSRF token
                },
                success: function(data){
                    toastr.success(data.message);
                    $('.js-cart-items-count').text(data.cartCount);
                    $('#subtotalAmount').text(data.subtotal + ' VNĐ');
                    $('#discountAmount').text(data.discountAmount.toLocaleString() + ' VNĐ')

                    var total = parseFloat(data.total.toString().replace(/,/g, '')) || 0;
                    var loyaltyAmount = parseFloat(data.loyaltyAmount.toString().replace(/,/g, '')) || 0;

                    var totalAmount = total - loyaltyAmount;
                    
                    // Cập nhật giá trị vào giao diện người dùng
                    $('#totalAmount').text(totalAmount.toLocaleString() + ' VNĐ');

                    $('#loyalty_rate_Amount').text(loyaltyAmount.toLocaleString() + ' VNĐ');

                    // Xóa sản phẩm khỏi bảng
                    _this.closest('tr').remove();
                    if (data.cartCount === 0) {
                        // Cập nhật nội dung hiển thị giỏ hàng trống
                        $('.shop-checkout').html(` <div class="cart-null">
                            <img src="/images/carts-null.png" alt="">
                            <a class="btn-comeback btn-comeback-pst" href="{{ route('home.index') }}"> Mua ngay </a>
                            </div>`);
                        }
                    // cập nhật voucher
                    HT.updateVoucherList(data.validDiscounts, total);
                },
                error: function(jqXHR) {
                    let errorMsg = jqXHR.responseJSON && jqXHR.responseJSON.error ? jqXHR.responseJSON.error : 'Đã có lỗi xảy ra, vui lòng thử lại.';
                    toastr.error(errorMsg);
                }
            });
        });
    }

    HT.applyDiscount = () => {
        $('#apply-voucher').on('click', function (e) { // Thêm sự kiện cho nút bấm "Áp dụng"
            e.preventDefault();
            var couponCode = $('input[name="voucher"]:checked').val(); // Lấy giá trị mã giảm giá đã chọn từ radio button
            var url = $(this).data('url'); // Lấy URL từ dữ liệu của nút bấm
        
            // if (!couponCode) {
            //     toastr.error('Vui lòng chọn mã giảm giá!');
            //     return; // Dừng lại nếu chưa chọn mã giảm giá
            // }
        
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    coupon_code: couponCode,
                    _token: _token // Đảm bảo _token đã được định nghĩa
                },
                success: function (data) {
                    if (data.message) {
                        toastr.success(data.message);
                    }
                    // Cập nhật các giá trị hiển thị
                    var total = parseFloat(data.total.toString().replace(/,/g, '')) || 0;
                    var loyaltyAmount = parseFloat(data.loyaltyAmount.toString().replace(/,/g, '')) || 0;
    
                    var totalAmount = total - loyaltyAmount;
    
                    // Cập nhật giá trị vào giao diện người dùng
                    $('#totalAmount').text(totalAmount.toLocaleString() + ' VNĐ');
                    $('#loyalty_rate_Amount').text(loyaltyAmount.toLocaleString() + ' VNĐ');
                    $('#discountAmount').text(data.discountAmount.toLocaleString() + ' VNĐ'); // Cập nhật giảm giá
                },
                error: function (xhr) {
                    toastr.error(xhr.responseJSON.message || 'Có lỗi xảy ra.');
                }
            });

            $('.voucher_overlay').hide();            // Ẩn lớp mờ
            $('.box-voucher').hide();  // Ẩn hộp voucher
        });
    };
    

    HT.changeQuantity = () => {
        $('.qty-control__number').on('change', function() {
            HT.change_quantity($(this))
        })
        $('.qty-control__reduce').on('click', function(){
            var input = $(this).closest('tr').find('.qty-control__number');
            HT.change_quantity(input)
        })
        $('.qty-control__increase').on('click', function(){
            var input = $(this).closest('tr').find('.qty-control__number');
            HT.change_quantity(input)
        })
    }
    HT.change_quantity = (input) => {
        var productId = input.data('id'); // Lấy ID sản phẩm
        var quantity = input.val(); // Lấy số lượng từ input
        var url = input.data('url')

        $.ajax({
            type: 'POST',
            url: url, // Cập nhật đường dẫn tương ứng
            data: {
                _token: _token,
                product_id: productId, // Gửi ID sản phẩm
                quantity: quantity // Gửi số lượng mới
            },
            success: function(response) {
                if (response.success) {
                    var total = parseFloat(response.total.toString().replace(/,/g, '')) || 0;
                    var loyaltyAmount = parseFloat(response.loyaltyAmount.toString().replace(/,/g, '')) || 0;

                    var totalAmount = total - loyaltyAmount;

                    // Cập nhật giá trị vào giao diện người dùng
                    $('#totalAmount').text(totalAmount.toLocaleString() + ' VNĐ');
                    $('#loyalty_rate_Amount').text(loyaltyAmount.toLocaleString() + ' VNĐ');
                    $('#subtotalAmount').text(response.subtotal + ' VNĐ');
                    $('#discountAmount').text(response.discountAmount.toLocaleString() + ' VNĐ')
                    $(`.shopping-cart__subtotal-${productId}`).text(response.productTotal + ' VNĐ'); 
                    
                    // cập nhật voucher
                    HT.updateVoucherList(response.validDiscounts, total);
                    

                }
            },
            error: function(xhr, status, error) {
                // toastr.error('Có lỗi xảy ra, vui lòng thử lại.');
            }
        });
    }

    // Hàm cập nhật lại danh sách voucher
    HT.updateVoucherList = (discounts, total) => {
        var voucherBody = $('.voucher_body'); // Lấy phần chứa danh sách voucher

        voucherBody.empty(); // Xóa tất cả các voucher hiện tại

        // Duyệt qua từng voucher và thêm vào lại phần giao diện
        discounts.forEach(val => {
            var disabledClass = (val.use_count >= val.use_limit || total < val.minimum_total_value) ? 'disabled-voucher' : '';
            var disabledAttribute = (val.use_count >= val.use_limit || total < val.minimum_total_value) ? 'disabled' : '';
            var daysLeft = Math.ceil((new Date(val.end_date) - new Date()) / (1000 * 60 * 60 * 24));
            var voucherItem = `
                <div class="voucher_item ${disabledClass}">
                    <div class="voucher_image">
                        <img src="http://127.0.0.1:8000/images/voucher1.png" alt="Voucher Logo">
                    </div>
                    <div class="voucher_content">
                        <div class="voucher_name">${val.code}</div>
                        <div class="voucher_des">
                            <span>Đơn tối thiểu ${Number(val.minimum_total_value).toLocaleString()} VNĐ</span>
                        </div>
                        <div class="voucher_des">
                            <span>Giảm tối đa ${Number(val.max_value).toLocaleString()} VNĐ </span>
                        </div>
                        <div class="voucher_des d-flex align-items-center justify-content-between">
                            <span>Hạn còn: ${ daysLeft } ngày</span>
                            <span>SL: ${val.use_limit - val.use_count}</span>
                        </div>
                    </div>
                    <div class="voucher_radio">
                        <input type="radio" name="voucher" value="${val.id }" ${disabledAttribute}>
                    </div>
                </div>
            `;

            voucherBody.append(voucherItem); // Thêm voucher vào giao diện
        });
    }

    HT.ClearCart = () => {
        $('.btn-clear').on('click', function(e){
            e.preventDefault();
            let _this = $(this)
            var url = _this.data('url');
            Swal.fire({
                title: "Bạn có chắc chắn?",
                html: _this.attr('data-text'),
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Có, Xóa nó!",
                cancelButtonText: "Hủy"
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: "Xóa!",
                        text: "Dữ liệu của bạn đã được xóa.",
                        icon: "success"
                    }).then(() => {
                        $.ajax({
                            type: 'POST',
                            url: url, // Sử dụng đường dẫn tương đối
                            data: {
                                _token: _token, // Lấy CSRF token
                            },
                            success: function(data){
                                toastr.success(data.message);
                                $('.shop-checkout').html(` <div class="cart-null">
                                    <img src="/images/carts-null.png" alt="">
                                    <a class="btn-comeback btn-comeback-pst" href="{{ route('home.index') }}"> Mua ngay </a>
                                    </div>`);
                                $('.js-cart-items-count').text(0)
                            },
                            error: function(jqXHR) {
                                let errorMsg = jqXHR.responseJSON && jqXHR.responseJSON.error ? jqXHR.responseJSON.error : 'Đã có lỗi xảy ra, vui lòng thử lại.';
                                toastr.error(errorMsg);
                            }
                        });
                    });
                }
            });
            
        })
    }

    HT.ClickVoucher = () => {
        $('.btn-voucher').on('click', function(e){
            e.preventDefault();
            $('body').css('overflow', 'hidden'); // Chặn cuộn trang
            $('.voucher_overlay').show(); // Hiện lớp mờ
            $('.box-voucher').show();     // Hiện hộp voucher
        })
        // Khi nhấn vào lớp mờ, ẩn cả lớp mờ và hộp voucher
        $('.voucher_overlay, .close-voucher, #apply-voucher').on('click', function () {
            $('body').css('overflow', ''); // Mở lại cuộn trang
            $('.voucher_overlay').hide(); // Ẩn lớp mờ
            $('.box-voucher').hide();  // Ẩn hộp voucher
        });
    }

    $(document).ready(function () {
        HT.addToCart();
        HT.removeCart();
        HT.applyDiscount();
        HT.changeQuantity();
        HT.ClearCart();
        HT.ClickVoucher()

    });

})(jQuery);
