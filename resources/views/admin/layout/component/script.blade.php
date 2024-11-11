<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/bootstrap-select.min.js') }}"></script>   
<script src="{{ asset('js/sweetalert.min.js') }}"></script>  

<script>
    // Hiển thị vòng xoay khi bắt đầu tải hoặc chuyển trang
    function showLoader() {
        document.getElementById('loading-spinner').style.display = 'flex';
    }

    // Ẩn vòng xoay khi tải xong hoặc chuyển trang xong
    function hideLoader() {
        document.getElementById('loading-spinner').style.display = 'none';
    }

</script> 

<script src="{{ asset('js/main.js') }}"></script>
<script src="{{ asset('js/custom.js') }}"></script>
<script src="{{ asset('librarys/library.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.3/dist/sweetalert2.all.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
    window.onbeforeunload = function() {
    showLoader(); // Hiển thị vòng xoay khi rời khỏi trang
    };
</script>
@yield('script')

