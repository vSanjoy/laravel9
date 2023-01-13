<header class="topbar" data-navbarbg="skin6">
    <nav class="navbar top-navbar navbar-expand-md">
        <div class="navbar-header" data-logobg="skin6">
            <!-- This is for the sidebar toggle which is visible on mobile only -->
            <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)"><i
                    class="ti-menu ti-close"></i></a>
            <!-- ============================================================== -->
            <!-- Logo -->
            <!-- ============================================================== -->
            <div class="navbar-brand">
                <!-- Logo icon -->
                <a href="{{ url('/adminpanel') }}">
                    <!-- Logo text -->
                    <span class="logo-text">
                        <!-- dark Logo text -->
                        <img src="{{asset('images/admin/logo-text.png')}}" alt="" class="dark-logo" width="230" />
                        <!-- Light Logo text -->
                        <img src="{{asset('images/admin/logo-light-text.png')}}" class="light-logo" alt="" />
                    </span>
                </a>
            </div>
            <!-- ============================================================== -->
            <!-- End Logo -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Toggle which is visible on mobile only -->
            <!-- ============================================================== -->
            <a class="topbartoggler d-block d-md-none waves-effect waves-light" href="javascript:void(0)" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <i class="ti-more"></i>
            </a>
        </div>
        <!-- ============================================================== -->
        <!-- End Logo -->
        <!-- ============================================================== -->
        <div class="navbar-collapse collapse" id="navbarSupportedContent">
            <!-- ============================================================== -->
            <!-- toggle and nav items -->
            <!-- ============================================================== -->
            <ul class="navbar-nav float-left mr-auto ml-3 pl-1">
                <!-- ============================================================== -->
                <!-- create new -->
                <!-- ============================================================== -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="{{ route('admin.website-settings') }}">
                        <i data-feather="settings" class="svg-icon"></i>
                    </a>
                </li>
            </ul>
            <!-- ============================================================== -->
            <!-- Right side toggle and nav items -->
            <!-- ============================================================== -->
            <ul class="navbar-nav float-right">
                <!-- ============================================================== -->
                <!-- Search -->
                <!-- ============================================================== -->
                {{-- <li class="nav-item d-none d-md-block">
                    <a class="nav-link" href="javascript:void(0)">
                        <form>
                            <div class="customize-input">
                                <input class="form-control custom-shadow custom-radius border-0 bg-white" type="search" placeholder="Search" aria-label="Search">
                                <i class="form-control-icon" data-feather="search"></i>
                            </div>
                        </form>
                    </a>
                </li> --}}
                <!-- ============================================================== -->
                <!-- User profile and search -->
                <!-- ============================================================== -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="javascript:void(0)" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span id="profile-pic-holder">
                        @if (Auth::guard('admin')->user()->profile_pic == null)
                            <img src="{{asset('images/admin/users/avatar5.png')}}" alt="" class="rounded-circle" width="40">
                        @else
                            @php
                            if (Auth::guard('admin')->user()->profile_pic != null && file_exists(public_path('images/uploads/'.$pageRoute.'/'.Auth::guard('admin')->user()->profile_pic))) {
                                echo '<img src="'.asset("images/uploads/".$pageRoute."/thumbs/".Auth::guard('admin')->user()->profile_pic).'" class="rounded-circle" width="40" />';
                            } else {
                                echo '<img src="'.asset("images/admin/users/avatar5.png").'" alt="" class="rounded-circle" width="40">';
                            }
                            @endphp
                        @endif
                        </span>
                        <span class="ml-2 d-none d-lg-inline-block"><span>@lang('custom_admin.label_hello'),</span> <span class="text-dark">{!! \Auth::guard('admin')->user()->full_name !!}</span> <i data-feather="chevron-down" class="svg-icon"></i></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right user-dd animated flipInY">
                        <a class="dropdown-item" href="{{ route('admin.profile') }}"><i data-feather="user" class="svg-icon mr-2 ml-1"></i> @lang('custom_admin.label_profile')</a>
                        <a class="dropdown-item" href="{{ route('admin.change-password') }}"><i data-feather="lock" class="svg-icon mr-2 ml-1"></i> @lang('custom_admin.label_change_password')</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('admin.logout') }}"><i data-feather="power" class="svg-icon mr-2 ml-1"></i> @lang('custom_admin.label_signout')</a>                        
                    </div>
                </li>
                <!-- ============================================================== -->
                <!-- User profile and search -->
                <!-- ============================================================== -->
            </ul>
        </div>
    </nav>
</header>