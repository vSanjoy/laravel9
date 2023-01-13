<div class="col-span-12 lg:col-span-4 xxl:col-span-3 flex lg:block flex-col-reverse">
	<div class="intro-y box mt-5">
		<div class="relative flex items-center p-5">
			<div class="w-12 h-12 image-fit">
				<img alt="Midone Tailwind HTML Admin Template" class="rounded-full" src="{{asset('images/admin/dist/profile-4.jpg')}}">
			</div>
			<div class="ml-4 mr-auto">
				<div class="font-medium text-base">{!! \Auth::guard('admin')->user()->full_name !!}</div>
				@if (\Auth::guard('admin')->user()->role_id == 1)
				<div class="text-gray-600">@lang('custom_admin.label_super_admin')</div>
				@endif
			</div>					
		</div>
		<div class="p-5 border-t border-gray-200 dark:border-dark-5">
			<a href="{{ route('admin.profile') }}" class="flex items-center @if (Route::currentRouteName() == 'admin.profile')text-theme-1 dark:text-theme-10 font-medium @endif"> <i data-feather="user" class="w-4 h-4 mr-2"></i> @lang('custom_admin.label_profile') </a>
			<a href="{{ route('admin.change-password') }}" class="flex items-center  @if (Route::currentRouteName() == 'admin.change-password')text-theme-1 dark:text-theme-10 font-medium @endif mt-5"> <i data-feather="lock" class="w-4 h-4 mr-2"></i> @lang('custom_admin.label_change_password') </a>
			<a href="{{ route('admin.upload-picture') }}" class="flex items-center  @if (Route::currentRouteName() == 'admin.upload-picture')text-theme-1 dark:text-theme-10 font-medium @endif mt-5"> <i data-feather="settings" class="w-4 h-4 mr-2"></i> @lang('custom_admin.label_upload_picture') </a>
		</div>				
	</div>			
</div>