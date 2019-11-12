<header class="main-header">
    <!-- Logo -->
    <a href="/" class="logo" target="_blank">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>A</b>LT</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>Admin</b>LTE</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle"
           data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                {{--
                <li class="messages-menu">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-shopping-cart"></i>
                    <span class="label label-success">4</span>
                  </a>
                </li>
                <li class="notifications-menu">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-envelope-o"></i>
                    <span class="label label-warning">10</span>
                  </a>
                </li>
                <li class="tasks-menu">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-bell-o"></i>
                    <span class="label label-danger">9</span>
                  </a>
                </li>
                --}}
                <li class="dropdown user user-menu">
                    @auth
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <span class="account-icon"><i class="fa fa-sign-in"></i></span>
                            <span class="hidden-xs">{{ auth()->user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="{{--{{ route('admin.account.edit') }}--}}"
                                       class="btn btn-default btn-flat">Аккаунт</a>
                                </div>
                                <div class="pull-right">
                                    <a href="#"
                                       class="btn btn-default btn-flat js-action-click"
                                       data-url="{{ route('logout') }}"
                                       data-confirm="Подтверждаете выход?"
                                    >Выйти</a>
                                </div>
                            </li>
                        </ul>
                    @endauth
                </li>
            </ul>
        </div>
    </nav>
</header>
