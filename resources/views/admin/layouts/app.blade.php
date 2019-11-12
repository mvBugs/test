@include('admin.layouts.inc.begin')
<div class="wrapper">
    @include('admin.layouts.inc.header')
    @include('admin.layouts.inc.aside')
    <div class="content-wrapper">

        @include('admin.layouts.inc.content-header', array_merge([
            'page_title' => 'Админ панель',
            'small_page_title' => '',
            'url_back' => '',
            'url_create' => ''
        ], $content_header ?? []))
{{--

        <div class="col-md-12">
            <br> <!-- TODO -->
            @include('admin.inc.notifications')
        </div>
--}}

        @yield('content')
    </div>
    @include('admin.layouts.inc.footer')
    <div class="control-sidebar-bg"></div>
</div>
@include('admin.layouts.inc.end')

@include('admin.inc.toastr')
