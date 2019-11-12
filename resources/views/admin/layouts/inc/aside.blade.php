<aside class="main-sidebar">
    <section class="sidebar">
        {!! \App\Models\Menu\Menu::renderByName('admin_menu', 'admin.layouts.inc.sidebar-menu.menu') !!}
{{--        @include('admin.layouts.inc.sidebar-menu.menu-static-example')--}}
    </section>
</aside>
