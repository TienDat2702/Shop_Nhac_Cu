(function ($) {
    "use strict";
    var HT = {};

    HT.uploadImage = () => {
        $('.image').on('change', function(event){
            var input = event.target; // lấy input gây ra sự kiện

            if(input.files && input.files[0]){ //nếu input file tồn tại và có ít nhất 1 phần tử
                
                var reader = new FileReader(); // đối tượng fileReader giúp đọc file và chuyển sang URL Base6
                reader.onload = (function(e){ // e sự kiện được thực thi ngay lập tức khi đọc và tải thành công
                   $('.imgpreview').attr('src', e.target.result);
                   $('#imgpreview').css('display', 'block');
                   $('#oldImage').val(e.target.result);   
                });

                reader.readAsDataURL(input.files[0]); 
                //readAsDataURL() là thuộc tính của đối tượng FileReader
                //đọc và chuyển đổi nội dung của file image chuỗi Base64-encoded Data URL.
            }
            
        });
    }

    $(document).ready(function () {
        HT.uploadImage();
    });
})(jQuery);
