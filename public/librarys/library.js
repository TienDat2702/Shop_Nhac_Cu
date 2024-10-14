(function ($) {
    "use strict";
    var HT = {};
    var _token = $('meta[name="csrf-token"]').attr('content');

    HT.changeStatus = () => {
        $('.toggleswitch').on('change', function () { 
            let _this = $(this);
            let option = {
                'value': _this.prop('checked') ? 1 : 0, //prop('checked'): nếu ô được check thì bằng 1 còn lại bằng 0
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

    $(document).ready(function () {
        HT.changeStatus();
    });
})(jQuery);
