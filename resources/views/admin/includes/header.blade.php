<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
    id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>

    @php
    $image = asset("images/admin/avatars/1.png");
    if (Auth::guard('admin')->user()->profile_pic != null && file_exists(public_path('images/uploads/account/thumbs/'.Auth::guard('admin')->user()->profile_pic))) :
        $image = asset("images/uploads/account/thumbs/".Auth::guard('admin')->user()->profile_pic);
    endif;
    @endphp

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <ul class="navbar-nav flex-row align-items-center ms-auto">
            <!-- User -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <img src="{{ $image }}" alt class="w-px-40 rounded-circle" />
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="javascript:void(0);">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        <img src="{{ $image }}" alt class="w-px-40 rounded-circle" />
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-semibold d-block">{{ Auth::guard('admin')->user()->full_name }}</span>
                                    <small class="text-muted">{{ getAdminType(Auth::guard('admin')->user()->type) }}</small>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.account.profile') }}">
                            <i class="bx bx-user me-2"></i>
                            <span class="align-middle">{{ __('custom_admin.label_profile') }}</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.account.change-password') }}">
                            <span class="d-flex align-items-center align-middle">
                                <i class='bx bxs-lock-alt me-2'></i>
                                <span class="flex-grow-1 align-middle">{{ __('custom_admin.label_change_password') }}</span>
                            </span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.account.settings') }}">
                            <i class="bx bx-cog me-2"></i>
                            <span class="align-middle">{{ __('custom_admin.label_settings') }}</span>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.auth.logout') }}">
                            <i class="bx bx-power-off me-2"></i>
                            <span class="align-middle">{{ __('custom_admin.label_log_out') }}</span>
                        </a>
                    </li>
                </ul>
            </li>
            <!--/ User -->
        </ul>
    </div>
</nav>
