
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">

{{-- head --}}
@include('admin.layout.component.head')
{{-- end head --}}

<body class="body">
    <div id="wrapper">
        <div id="page" class="">
            <div class="layout-wrap">
                {{-- sidebar --}}
                @include('admin.layout.component.sidebar')
                {{-- end sidebar --}}
                <div class="section-content-right">

                    {{-- header --}}
                    @include('admin.layout.component.header')
                    {{-- end header --}}
                    
                    <div class="main-content">

                        {{-- main --}}
                        @yield('main')
                        {{-- end main --}}

                        {{-- footer --}}
                        @include('admin.layout.component.footer')
                        {{-- end footer --}}
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div id="loading-spinner" class="loading-spinner">
        <div class="lds-spinner"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
    </div>
{{-- script --}}
@include('admin.layout.component.script')
{{-- end script --}}

</body>

</html>