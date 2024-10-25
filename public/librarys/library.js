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
        $('.btn-delete').on('click', function(e){
            let _this = $(this)
            let text = 

            e.preventDefault(); //ngăn chặn hành vi mặc định
            let form = $(this).closest('form'); // lấy form gần nhất với button
            Swal.fire({
                title: "Bạn có chắc chắn?",
                html: '<span style="color: red">' + _this.attr('data-text2') + '</span>' + "<br>" + _this.attr('data-text'),
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
        })
    }


    HT.setupCkeditor = () => {
        if ($('.ck-editor')) {
            $('.ck-editor').each(function() {
                let editor = $(this);
                let elementId = editor.attr('id');
                let elementHeight = editor.attr('data-height');
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
       $('input[name = slug]').on('keyup', function(){
            let input = $(this);
            input.val(HT.processString(input.val()));
       })

    };


    HT.processString = (value) =>{

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
        $('.trash').on('click', function(){
            const path = window.location.pathname;
            window.location = `${path}?deleted=daxoa`;
        })
    }



    HT.checkFilters = () => {
        let selectedCategories = [];
        let selectedBrands = [];
        let priceRange = [0, Infinity]; // Mặc định là không có giới hạn giá
        let priceChanged = false; // Biến kiểm tra xem giá đã thay đổi chưa
    
        // Lắng nghe sự kiện change trên các danh mục
        $('.chk-category').on('change', function() {
            selectedCategories = [];
            $('.chk-category:checked').each(function() {
                selectedCategories.push($(this).val());
            });
            filterProducts();
        });
    
        // Lắng nghe sự kiện change trên các thương hiệu
        $('.chk-brand').on('change', function() {
            selectedBrands = [];
            $('.chk-brand:checked').each(function() {
                selectedBrands.push($(this).val());
            });
            filterProducts();
        });
    
        // Lắng nghe sự kiện thay đổi giá trị của slider
        $('.price-range-slider').on('change', function() {
            priceRange = $(this).val().split(',').map(value => parseInt(value));
            priceChanged = true; // Đánh dấu là giá đã thay đổi
            filterProducts(); // Gọi hàm lọc sản phẩm
        });
    
        // Hàm lọc sản phẩm
        function filterProducts() {
            $.ajax({
                url: '/shop',
                method: 'GET',
                data: {
                    brands: selectedBrands,
                    categories: selectedCategories,
                    min_price: priceChanged ? priceRange[0] : null, // Chỉ gửi giá nếu đã thay đổi
                    max_price: priceChanged ? priceRange[1] : null  // Chỉ gửi giá nếu đã thay đổi
                },
                success: function(response) {
                    $('#products-grid').html($(response).find('#products-grid').html());
                },
                error: function(xhr, status, error) {
                    console.error("Lỗi: " + error);
                }
            });
        }
        // Gọi hàm lọc sản phẩm lần đầu khi trang được tải
        filterProducts(); // Lọc sản phẩm ban đầu với giá trị mặc định
    }
    
    $(document).ready(function () {
        HT.changeStatus();
        HT.sweetalert2();
        HT.setupCkeditor();
        HT.keyUpInput();
        HT.trash();
        HT.checkFilters(); // Khởi tạo chức năng lọc
        HT.checkBrand();
    });
})(jQuery);
