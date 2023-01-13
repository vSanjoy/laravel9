@php
$getAllRoles = getUserRoleSpecificRoutes();
$isSuperAdmin = false;
if (\Auth::guard('admin')->user()->id == 1 || \Auth::guard('admin')->user()->type == 'SA') {
    $isSuperAdmin = true;
}

$currentPageMergeRoute = explode('admin.', Route::currentRouteName());
if (count($currentPageMergeRoute) > 0) {
    $currentPage = $currentPageMergeRoute[1];
} else {
    $currentPage = Route::currentRouteName();
}

// Get site settings data
$getSiteSettings = getSiteSettings();
@endphp

<aside class="left-sidebar" data-sidebarbg="skin6">
	<!-- Sidebar scroll-->
	<div class="scroll-sidebar" data-sidebarbg="skin6">
		<!-- Sidebar navigation-->
		<nav class="sidebar-nav">
			<ul id="sidebarnav">
				<li class="sidebar-item @if ($currentPage == 'dashboard')selected @endif"> 
					<a class="sidebar-link sidebar-link @if ($currentPage == 'dashboard')active @endif" href="{{ route('admin.dashboard') }}" aria-expanded="false">
						<i data-feather="home" class="feather-icon"></i><span class="hide-menu">@lang('custom_admin.label_dashboard')</span>
					</a>
				</li>

				<li class="list-divider"></li>
				<li class="nav-small-cap"><span class="hide-menu">@lang('custom_admin.label_managements')</span></li>

				<!-- Customer Management Start -->
			@php
			$customerRoutes = ['customer.list','customer.add','customer.edit','customer.sort'];
			@endphp
			@if ( ($isSuperAdmin) || in_array('customer.list', $getAllRoles) )
				<li class="sidebar-item @if (in_array($currentPage, $customerRoutes))selected @endif">
					<a class="sidebar-link has-arrow @if (in_array($currentPage, $customerRoutes))active @endif" href="javascript:void(0)" aria-expanded="false">
						<i data-feather="users" class="feather-icon"></i><span class="hide-menu"> @lang('custom_admin.label_menu_customer')</span>
					</a>
					<ul aria-expanded="false" class="collapse first-level base-level-line @if (in_array($currentPage, $customerRoutes))in @endif">
						<li class="sidebar-item">
							<a href="{{ route('admin.customer.list') }}" class="sidebar-link sub-menu">
								<span class="hide-menu"> @lang('custom_admin.label_list')</span>
							</a>
						</li>
						@if ( ($isSuperAdmin) || (in_array('customer.add', $getAllRoles)) )
						<li class="sidebar-item">
							<a href="{{ route('admin.customer.add') }}" class="sidebar-link sub-menu">
								<span class="hide-menu"> @lang('custom_admin.label_add')</span>
							</a>
						</li>
						@endif
					</ul>
				</li>
			@endif

			<!-- Advertiser Management Start -->
			@php
			$advertiserRoutes = ['advertiser.list','advertiser.add','advertiser.edit','advertiser.sort'];
			@endphp
			@if ( ($isSuperAdmin) || in_array('advertiser.list', $getAllRoles) )
				<li class="sidebar-item @if (in_array($currentPage, $advertiserRoutes))selected @endif">
					<a class="sidebar-link has-arrow @if (in_array($currentPage, $advertiserRoutes))active @endif" href="javascript:void(0)" aria-expanded="false">
						<i data-feather="users" class="feather-icon"></i><span class="hide-menu"> @lang('custom_admin.label_menu_advertiser')</span>
					</a>
					<ul aria-expanded="false" class="collapse first-level base-level-line @if (in_array($currentPage, $advertiserRoutes))in @endif">
						<li class="sidebar-item">
							<a href="{{ route('admin.advertiser.list') }}" class="sidebar-link sub-menu">
								<span class="hide-menu"> @lang('custom_admin.label_list')</span>
							</a>
						</li>
						@if ( ($isSuperAdmin) || (in_array('advertiser.add', $getAllRoles)) )
						<li class="sidebar-item">
							<a href="{{ route('admin.advertiser.add') }}" class="sidebar-link sub-menu">
								<span class="hide-menu"> @lang('custom_admin.label_add')</span>
							</a>
						</li>
						@endif
					</ul>
				</li>
			@endif

				<!-- Category Management Start -->
			@php
			$categoryRoutes = ['category.list','category.add','category.edit','category.sort'];
			@endphp
			@if ( ($isSuperAdmin) || in_array('category.list', $getAllRoles) )
				<li class="sidebar-item @if (in_array($currentPage, $categoryRoutes))selected @endif">
					<a class="sidebar-link has-arrow @if (in_array($currentPage, $categoryRoutes))active @endif" href="javascript:void(0)" aria-expanded="false">
						<i data-feather="briefcase" class="feather-icon"></i><span class="hide-menu"> @lang('custom_admin.label_menu_category')</span>
					</a>
					<ul aria-expanded="false" class="collapse first-level base-level-line @if (in_array($currentPage, $categoryRoutes))in @endif">
						<li class="sidebar-item">
							<a href="{{ route('admin.category.list') }}" class="sidebar-link sub-menu">
								<span class="hide-menu"> @lang('custom_admin.label_list')</span>
							</a>
						</li>
						@if ( ($isSuperAdmin) || (in_array('category.add', $getAllRoles)) )
						<li class="sidebar-item">
							<a href="{{ route('admin.category.add') }}" class="sidebar-link sub-menu">
								<span class="hide-menu"> @lang('custom_admin.label_add')</span>
							</a>
						</li>
						@endif
						@if ( ($isSuperAdmin) || (in_array('category.sort', $getAllRoles)) )
						<li class="sidebar-item">
							<a href="{{ route('admin.category.sort') }}" class="sidebar-link sub-menu">
								<span class="hide-menu"> @lang('custom_admin.label_sort')</span>
							</a>
						</li>
						@endif
					</ul>
				</li>
			@endif

			<!-- Event Category Management Start -->
			@php
			$eventCategoryRoutes = ['eventCategory.list','eventCategory.add','eventCategory.edit','eventCategory.sort'];
			@endphp
			@if ( ($isSuperAdmin) || in_array('eventCategory.list', $getAllRoles) )
				<li class="sidebar-item @if (in_array($currentPage, $eventCategoryRoutes))selected @endif">
					<a class="sidebar-link has-arrow @if (in_array($currentPage, $eventCategoryRoutes))active @endif" href="javascript:void(0)" aria-expanded="false">
						<i data-feather="briefcase" class="feather-icon"></i><span class="hide-menu"> @lang('custom_admin.label_menu_event_category')</span>
					</a>
					<ul aria-expanded="false" class="collapse first-level base-level-line @if (in_array($currentPage, $eventCategoryRoutes))in @endif">
						<li class="sidebar-item">
							<a href="{{ route('admin.eventCategory.list') }}" class="sidebar-link sub-menu">
								<span class="hide-menu"> @lang('custom_admin.label_list')</span>
							</a>
						</li>
						@if ( ($isSuperAdmin) || (in_array('eventCategory.add', $getAllRoles)) )
						<li class="sidebar-item">
							<a href="{{ route('admin.eventCategory.add') }}" class="sidebar-link sub-menu">
								<span class="hide-menu"> @lang('custom_admin.label_add')</span>
							</a>
						</li>
						@endif
						@if ( ($isSuperAdmin) || (in_array('eventCategory.sort', $getAllRoles)) )
						<li class="sidebar-item">
							<a href="{{ route('admin.eventCategory.sort') }}" class="sidebar-link sub-menu">
								<span class="hide-menu"> @lang('custom_admin.label_sort')</span>
							</a>
						</li>
						@endif
					</ul>
				</li>
			@endif

			<!-- Event Management Start -->
			@php
			$eventRoutes = ['event.list','event.add','event.edit','event.sort','event.gallery'];
			@endphp
			@if ( ($isSuperAdmin) || in_array('event.list', $getAllRoles) )
				<li class="sidebar-item @if (in_array($currentPage, $eventRoutes))selected @endif">
					<a class="sidebar-link has-arrow @if (in_array($currentPage, $eventRoutes))active @endif" href="javascript:void(0)" aria-expanded="false">
						<i data-feather="sunrise" class="feather-icon"></i><span class="hide-menu"> @lang('custom_admin.label_menu_event')</span>
					</a>
					<ul aria-expanded="false" class="collapse first-level base-level-line @if (in_array($currentPage, $eventRoutes))in @endif">
						<li class="sidebar-item">
							<a href="{{ route('admin.event.list') }}" class="sidebar-link sub-menu">
								<span class="hide-menu"> @lang('custom_admin.label_list')</span>
							</a>
						</li>
						@if ( ($isSuperAdmin) || (in_array('event.add', $getAllRoles)) )
						<li class="sidebar-item">
							<a href="{{ route('admin.event.add') }}" class="sidebar-link sub-menu">
								<span class="hide-menu"> @lang('custom_admin.label_add')</span>
							</a>
						</li>
						@endif
						@if ( ($isSuperAdmin) || (in_array('event.sort', $getAllRoles)) )
						<li class="sidebar-item">
							<a href="{{ route('admin.event.sort') }}" class="sidebar-link sub-menu">
								<span class="hide-menu"> @lang('custom_admin.label_sort')</span>
							</a>
						</li>
						@endif
					</ul>
				</li>
			@endif

			<!-- Order Management Start -->
			{{-- @php
			$orderRoutes = ['order.list','order.view'];
			@endphp
			@if ( ($isSuperAdmin) || in_array('order.list', $getAllRoles) )
				<li class="sidebar-item @if (in_array($currentPage, $orderRoutes))selected @endif">
					<a class="sidebar-link has-arrow @if (in_array($currentPage, $orderRoutes))active @endif" href="javascript:void(0)" aria-expanded="false">
						<i data-feather="shopping-cart" class="feather-icon"></i><span class="hide-menu"> @lang('custom_admin.label_menu_order')</span>
					</a>
					<ul aria-expanded="false" class="collapse first-level base-level-line @if (in_array($currentPage, $orderRoutes))in @endif">
						<li class="sidebar-item">
							<a href="{{ route('admin.order.list') }}" class="sidebar-link sub-menu">
								<span class="hide-menu"> @lang('custom_admin.label_list')</span>
							</a>
						</li>
					</ul>
				</li>
			@endif --}}

			<!-- Authentication Management Start -->
			{{-- @php
			$subAdminRoutes = ['subAdmin.list','subAdmin.add','subAdmin.edit','subAdmin.slot'];
			$roleRoutes = ['role.list','role.add','role.edit'];
			@endphp
			@if ( ($isSuperAdmin) || in_array('subAdmin.list', $getAllRoles) || in_array('role.list', $getAllRoles) )
				<li class="list-divider"></li>
				<li class="nav-small-cap"><span class="hide-menu">@lang('custom_admin.label_authentication')</span></li>

				@if ( ($isSuperAdmin) || in_array('role.list', $getAllRoles) )
				<li class="sidebar-item @if (in_array($currentPage, $roleRoutes))selected @endif">
					<a class="sidebar-link has-arrow @if (in_array($currentPage, $roleRoutes))active @endif" href="javascript:void(0)" aria-expanded="false">
						<i data-feather="globe" class="feather-icon"></i><span class="hide-menu"> @lang('custom_admin.label_menu_role')</span>
					</a>
					<ul aria-expanded="false" class="collapse first-level base-level-line @if (in_array($currentPage, $roleRoutes))in @endif">
						<li class="sidebar-item">
							<a href="{{ route('admin.role.list') }}" class="sidebar-link sub-menu">
								<span class="hide-menu"> @lang('custom_admin.label_list')</span>
							</a>
						</li>
						@if ( ($isSuperAdmin) || (in_array('role.add', $roleRoutes)) )
						<li class="sidebar-item">
							<a href="{{ route('admin.role.add') }}" class="sidebar-link sub-menu">
								<span class="hide-menu"> @lang('custom_admin.label_add')</span>
							</a>
						</li>
						@endif
					</ul>
				</li>
				@endif

				@if ( ($isSuperAdmin) || in_array('subAdmin.list', $getAllRoles) )
				<li class="sidebar-item @if (in_array($currentPage, $subAdminRoutes))selected @endif">
					<a class="sidebar-link has-arrow @if (in_array($currentPage, $subAdminRoutes))active @endif" href="javascript:void(0)" aria-expanded="false">
						<i data-feather="users" class="feather-icon"></i><span class="hide-menu"> @lang('custom_admin.label_menu_sub_admin')</span>
					</a>
					<ul aria-expanded="false" class="collapse first-level base-level-line @if (in_array($currentPage, $subAdminRoutes))in @endif">
						<li class="sidebar-item">
							<a href="{{ route('admin.subAdmin.list') }}" class="sidebar-link sub-menu">
								<span class="hide-menu"> @lang('custom_admin.label_list')</span>
							</a>
						</li>
						@if ( ($isSuperAdmin) || (in_array('subAdmin.add', $subAdminRoutes)) )
						<li class="sidebar-item">
							<a href="{{ route('admin.subAdmin.add') }}" class="sidebar-link sub-menu">
								<span class="hide-menu"> @lang('custom_admin.label_add')</span>
							</a>
						</li>
						@endif
					</ul>
				</li>
				@endif				
			@endif --}}

			<!-- Website Settings & CMS Management Start -->
			@php
			$siteSettingRoutes 	= ['website-settings'];
			$cmsRoutes 			= ['cms.list','cms.add','cms.edit'];
			$allBookingRoutes	= ['booking-history'];
			@endphp
			@if ( ($isSuperAdmin) || in_array('website-settings', $getAllRoles) || in_array('cms.list', $getAllRoles) )
				<li class="list-divider"></li>
				<li class="nav-small-cap"><span class="hide-menu">@lang('custom_admin.label_miscellaneous')</span></li>

				@if ( ($isSuperAdmin) || in_array('cms.list', $getAllRoles) )
				<li class="sidebar-item @if (in_array($currentPage, $cmsRoutes))selected @endif">
					<a class="sidebar-link has-arrow @if (in_array($currentPage, $cmsRoutes))active @endif" href="javascript:void(0)" aria-expanded="false">
						<i data-feather="layers" class="feather-icon"></i><span class="hide-menu"> @lang('custom_admin.label_cms')</span>
					</a>
					<ul aria-expanded="false" class="collapse first-level base-level-line @if (in_array($currentPage, $cmsRoutes))in @endif">
						<li class="sidebar-item">
							<a href="{{ route('admin.cms.list') }}" class="sidebar-link sub-menu">
								<span class="hide-menu"> @lang('custom_admin.label_list')</span>
							</a>
						</li>
						@if ( ($isSuperAdmin) || (in_array('cms.add', $getAllRoles)) )
						<li class="sidebar-item">
							<a href="{{ route('admin.cms.add') }}" class="sidebar-link sub-menu">
								<span class="hide-menu"> @lang('custom_admin.label_add')</span>
							</a>
						</li>
						@endif
					</ul>
				</li>
				@endif
				@if ( ($isSuperAdmin) || (in_array('website-settings', $getAllRoles)) )
				<li class="sidebar-item @if (in_array($currentPage, $siteSettingRoutes))selected @endif"> 
					<a class="sidebar-link sidebar-link @if (in_array($currentPage, $siteSettingRoutes))active @endif" href="{{ route('admin.website-settings') }}" aria-expanded="false">
						<i data-feather="settings" class="feather-icon"></i><span class="hide-menu">@lang('custom_admin.label_website_settings')</span>
					</a>
				</li>
				@endif
			@endif

				<li class="list-divider"></li>
				<li class="sidebar-item">
					<a class="sidebar-link sidebar-link" href="{{ route('admin.logout') }}" aria-expanded="false">
						<i data-feather="log-out" class="feather-icon"></i><span class="hide-menu">@lang('custom_admin.label_signout')</span>
					</a>
				</li>
			</ul>
		</nav>
		<!-- End Sidebar navigation -->
	</div>
	<!-- End Sidebar scroll-->
</aside>