@extends('admin.layouts.app', ['title' => $pageTitle])

@section('content')

    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Breadcrumb -->
		@include('admin.includes.breadcrumb')
		<!-- / Breadcrumb -->
        
        <div class="row">
            <div class="col-md-12">
                {{ Form::open([
                    'method'=> 'POST',
                    'class' => '',
                    'route' => [$routePrefix.'.account.profile'],
                    'name'  => 'updateProfileForm',
                    'id'    => 'updateProfileForm',
                    'files' => true,
                    'novalidate' => true ]) }}
                    @method('PATCH')
                    <div class="card mb-4">
                        <h5 class="card-header">{{ __('custom_admin.message_profile_details') }}</h5>
                        <!-- Account -->
                        <div class="card-body">
                            <div class="d-flex align-items-start align-items-sm-center gap-3">
                                @php
                                $image = asset("images/admin/avatars/1.png");
                                if ($adminDetail->profile_pic != null && file_exists(public_path('images/uploads/'.$pageRoute.'/'.$adminDetail->profile_pic))) :
                                    $image = asset("images/uploads/".$pageRoute."/thumbs/".$adminDetail->profile_pic);
                                endif
                                @endphp
                                
                                <div class="preview_img_div_image position_relative" style="position: relative;">
                                    <img src="{{ $image }}" alt="user-avatar" class="d-block rounded" height="100" width="100" id="uploadedAvatar" />
                                    <img id="upload_preview" class="mt-2" style="display: none;" />
                                </div>

                                <div class="button-wrapper">
                                    <label for="upload" class="btn rounded-pill btn-dark mb-4" tabindex="0">
                                        <span class="d-none d-sm-block"><i class='bx bx-upload'></i> {{ __('custom_admin.label_upload_new_photo') }}</span>
                                        <i class="bx bx-upload d-block d-sm-none"></i>
                                        {{ Form::file('profile_pic', array(
																	'id' => 'upload',
																	'class' => 'account-file-input upload-image',
                                                                    'hidden' => true
																	)) }}
                                    </label>
                                    <p class="text-muted mb-0">{{ __('custom_admin.message_allowed_file_types', ['fileTypes' => config('global.IMAGE_FILE_TYPES')]) }} </p>
                                </div>
                            </div>
                        </div>
                        <hr class="my-0" />
                        <div class="card-body">
                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label for="firstName" class="form-label">{{ __('custom_admin.label_first_name') }}<span class="red_star">*</span></label>
                                    {{ Form::text('first_name', $adminDetail->first_name ?? null, [
                                                                'id' => 'first_name',
                                                                'class' => 'form-control',
                                                                'placeholder' => __('custom_admin.message_enter_first_name'),
                                                                'required' => true,
                                                                'autofocus' => true ]) }}
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="lastName" class="form-label">{{ __('custom_admin.label_last_name') }}<span class="red_star">*</span></label>
                                    {{ Form::text('last_name', $adminDetail->last_name ?? null, [
                                                                'id' => 'last_name',
                                                                'class' => 'form-control',
                                                                'placeholder' => __('custom_admin.message_enter_last_name'),
                                                                'required' => true ]) }}
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="email" class="form-label">{{ __('custom_admin.label_email') }}<span class="red_star">*</span></label>
                                    {{ Form::text('email', $adminDetail->email ?? null, array(
                                                                'id' => 'email',
                                                                'class' => 'form-control',
                                                                'placeholder' => __('custom_admin.message_enter_email'),
                                                                'required' => true )) }}
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="phone_no" class="form-label">{{ __('custom_admin.label_phone_number') }}<span class="red_star">*</span></label>
                                    {{ Form::text('phone_no', $adminDetail->phone_no, array(
                                                                'id' => 'phone_no',
                                                                'class' => 'form-control',
                                                                'placeholder' => __('custom_admin.message_enter_phone'),
                                                                'required' => true )) }}
                                </div>
                            </div>
                            <div class="mt-2">
                                <a class="btn rounded-pill btn-secondary text-white" id="btn-cancel" href="{{ route($routePrefix.'.account.dashboard') }}"><i class='bx bx-left-arrow-circle'></i> {{ __('custom_admin.btn_cancel') }}</a>
                                <button type="submit" class="btn rounded-pill btn-primary float-right" id="btn-updating"><i class='bx bx-save'></i> {{ __('custom_admin.btn_update') }}</button>
                            </div>
                        </div>
                        <!-- /Account -->
                    </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    @include($routePrefix.'.includes.image_preview')
@endpush