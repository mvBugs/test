{{--
    You can use vars: $menuItems, $menu
--}}
<ul class="sidebar-menu" data-widget="tree">
    @foreach($menuItems->toTree() as $item)
        @include('admin.layouts.inc.sidebar-menu.menu-item', ['item' => $item])
    @endforeach
</ul>
