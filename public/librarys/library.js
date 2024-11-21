(function ($) {
    "use strict";
    var HT = {};
    var _token = $('meta[name="csrf-token"]').attr('content');

    HT.changeStatus = () => {
        $('.toggleswitch').on('change', function () {
            let _this = $(this);
            let option = {
                'value': _this.prop('checked') ? 2 : 1, //prop('checked'): nếu ô được check thì bằng 1 còn lại bằng 0
                'id': _this.attr('data-id'),
                'model': _this.attr('data-model'),
                '_token': _token
            };

            $.ajax({
                url: '/ajax/dashboard/changeStatus',
                type: 'POST',
                data: option,
                dataType: 'json',
                success: function (res) {
                    if (res.flag === true) {
                        // nếu cập nhập thành công tức là flag = true cập nhật lại value cho input
                        _this.val(option.value);
                    } else {
                        alert('Có lỗi xảy ra: ' + res.message);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log('Lỗi: ' + textStatus + ' ' + errorThrown);
                }
            });
        });
    }

    HT.sweetalert2 = () => {
        $('.btn-delete').on('click', function (e) {
            e.preventDefault();
            let _this = $(this);
            let form = $(this).closest('form');
            
            // Kiểm tra và hiển thị các thông báo khác nhau
            let cancelButtonText = (_this.attr('data-text3') || _this.attr('data-text2')) ? "OK" : "Hủy";
            let cancelButtonColor = (_this.attr('data-text3') || _this.attr('data-text2')) ? "#7066E0" : "#d33";
            let title = (_this.attr('data-text3') || _this.attr('data-text2')) ? "Bạn không thể xóa danh mục" : "Bạn có chắc chắn?";
            
            // Quyết định nội dung thông báo
            let htmlContent = _this.attr('data-text3') && _this.attr('data-text2')
                ? '<span style="color: red">' + _this.attr('data-text2') + '</span>' + '<br>'+'<span style="color: red">' + _this.attr('data-text3') + '</span>'
                : _this.attr('data-text3')
                ? '<span style="color: red">' + _this.attr('data-text3') + '</span>'
                : (_this.attr('data-text2')
                    ? '<span style="color: red">' + _this.attr('data-text2') + '</span>'
                    : '<span>' + _this.attr('data-text') + '</span>');
    
            // Tùy chọn Swal
            let swalOptions = {
                title: title,
                html: htmlContent,
                icon: "warning",
                showCancelButton: true,
                showConfirmButton: !(_this.attr('data-text3') || _this.attr('data-text2')),
                cancelButtonColor: cancelButtonColor,
                cancelButtonText: cancelButtonText
            };

            Swal.fire(swalOptions).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: form.attr('action'),
                        type: 'POST',
                        data: form.serialize() + '&_method=DELETE',
                        success: function (response) {
                            Swal.fire({
                                title: "Xóa!",
                                text: "Dữ liệu của bạn đã được xóa.",
                                icon: "success"
                            }).then(() => {
                                location.reload();
                            });
                        }
                    });
                 }
            });
        });
    };

    HT.sweetalertshowroom = () => {
        $('.btn-delete-showroom').on('click', function(e) {
            let _this = $(this);
            let publishStatus = _this.attr('data-publish');
            let hasProducts = _this.attr('data-has-products');
    
            // Kiểm tra trạng thái publish
            if (publishStatus == 4) {
                Swal.fire({
                    title: "Không thể xóa!",
                    text: "Kho không được cho phép xóa.",
                    icon: "error"
                });
                e.preventDefault(); // Ngăn chặn hành vi mặc định
                return; // Dừng thực hiện tiếp
            }
    
            // Kiểm tra xem showroom có sản phẩm liên kết không
            if (hasProducts === 'true') {
                Swal.fire({
                    title: "Không thể xóa!",
                    text: _this.attr('data-text'),
                    icon: "error"
                });
                e.preventDefault(); // Ngăn chặn hành vi mặc định
                return; // Dừng thực hiện tiếp
            }
    
            // Nếu không có vấn đề gì, hiển thị hộp thoại xác nhận
            e.preventDefault(); // Ngăn chặn hành vi mặc định
            let form = $(this).closest('form'); // Lấy form gần nhất với button
            Swal.fire({
                title: "Bạn có chắc chắn?",
                html: '<span style="color: red">' + _this.attr('data-text2'),
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
                        form.submit(); // Gửi form với id cụ thể
                    });
                }
            });
        });
    }
    HT.sweetalertproduct = () => {
        $('.btn-delete-product').on('click', function(e) {
            e.preventDefault(); // Ngăn chặn hành vi mặc định
            let _this = $(this);
            let form = _this.closest('form'); // Lấy form gần nhất với button
    
            // Hiển thị hộp thoại xác nhận xóa
            Swal.fire({
                title: "Bạn có chắc chắn?",
                html: '<span style="color: red">' + _this.attr('data-text2') + '</span>',
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
                        form.submit(); // Gửi form
                    });
                }
            });
        });
    }
    HT.sweetalertbanner = () => {
        $('.btn-delete-banner').on('click', function(e) {
            e.preventDefault(); // Ngăn chặn hành vi mặc định
            let _this = $(this);
            let form = _this.closest('form'); 
            let publishStatus = _this.attr('data-publish');// Lấy form gần nhất với button
            if (publishStatus == 2) {
                Swal.fire({
                    title: "Không thể xóa!",
                    text: "Banner đang sử dụng không được phép xóa.",
                    icon: "error"
                });
                e.preventDefault(); // Ngăn chặn hành vi mặc định
                return; // Dừng thực hiện tiếp
            }
            // Hiển thị hộp thoại xác nhận xóa
            Swal.fire({
                title: "Bạn có chắc chắn?",
                html: '<span style="color: red">' + _this.attr('data-text2') + '</span>',
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
                        form.submit(); // Gửi form
                    });
                }
            });
        });
    }
    

    HT.setupCkeditor = () => {
        if ($('.ck-editor')) {
            $('.ck-editor').each(function () {
                let editor = $(this);
                let elementId = editor.attr('id');
                ClassicEditor
                    .create(document.querySelector('#' + elementId), {
                        ckfinder: {
                            uploadUrl: uploadUrl,
                        },
                        mediaEmbed: {
                            previewsInData: true // Bật chế độ xem trước trong dữ liệu đầu ra
                        },

                    })
                    .catch(error => {
                        console.error(error);
                    });

            });
        }
    }


    // Cập nhật hàm keyUpInput
    HT.keyUpInput = () => {
        $('input[name = slug]').on('keyup', function () {
            let input = $(this);
            input.val(HT.processString(input.val()));
        })

    };


    HT.processString = (value) => {

        // Hàm bỏ dấu
        const removeDiacritics = (str) => {
            return str.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
        };

        // Bỏ dấu cho chuỗi
        let normalizedStr = removeDiacritics(value).toLowerCase();
        // Thay thế ký tự "đ" thành "d"
        normalizedStr = normalizedStr.replace(/đ/g, 'd');
        // Thay thế khoảng trắng bằng dấu "-"
        let result = normalizedStr.replace(/\s+/g, '-');

        return result;

    }

    HT.trash = () => {
        $('.trash').on('click', function () {
            const path = window.location.pathname;
            window.location = `${path}?deleted=daxoa`;
        })
    }

    
    $(document).ready(function () {
        HT.changeStatus();
        HT.sweetalert2();
        HT.sweetalertshowroom();
        HT.sweetalertproduct();
        HT.sweetalertbanner();
        HT.setupCkeditor();
        HT.keyUpInput();
        HT.trash();
    });
})(jQuery);
