@extends('admin.layouts.app', ['title' => $panelTitle])

@section('content')
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
		<!-- Breadcrumb -->
		@include('admin.includes.breadcrumb')
		<!-- / Breadcrumb -->
        
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <h5 class="card-header">{{ $panelTitle }}</h5>
                    <div class="card-body">
					{{ Form::open([
						'method'=> 'POST',
						'class' => '',
						'route' => [$routePrefix.'.account.settings'],
						'name'  => 'updateSettingsForm',
						'id'    => 'updateSettingsForm',
						'files' => true,
						'novalidate' => true]) }}
						@method('PATCH')
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('custom_admin.label_from_email') }}<span class="red_star">*</span></label>
								{{ Form::text('from_email', $websiteSettings['from_email'] ?? null, [
																		'id' => 'email',
																		'class' => 'form-control',
																		'placeholder' => __('custom_admin.placeholder_from_email'),
																		'required' => true ]) }}
                            </div>
							<div class="col-md-6">
                                <label class="form-label">{{ __('custom_admin.label_to_email') }}<span class="red_star">*</span></label>
								{{ Form::text('to_email', $websiteSettings['to_email'] ?? null, [
																		'id' => 'to_email',
																		'class' => 'form-control',
																		'placeholder' => __('custom_admin.placeholder_to_email'),
																		'required' => true ]) }}
                            </div>
							<div class="col-md-6">
                                <label class="form-label">{{ __('custom_admin.label_phone_number') }}</label>
								{{ Form::text('phone_no', $websiteSettings['phone_no'] ?? null, [
																		'id' => 'phone_no',
																		'class' => 'form-control',
																		'placeholder' => __('custom_admin.placeholder_phone_number')
																		]) }}
                            </div>
							<div class="col-md-6">
                                <label class="form-label">{{ __('custom_admin.label_fax') }}</label>
								{{ Form::text('fax', $websiteSettings['fax'] ?? null, [
																		'id' => 'fax',
																		'class' => 'form-control',
																		'placeholder' => __('custom_admin.placeholder_fax')
																		]) }}
                            </div>
							<div class="col-md-6">
                                <label class="form-label">{{ __('custom_admin.label_facebook_link') }}</label>
								{{ Form::text('facebook_link', $websiteSettings['facebook_link'] ?? null, [
																						'id' => 'facebook_link',
																						'class' => 'form-control',
																						'placeholder' => __('custom_admin.placeholder_facebook_link')
																						]) }}
                            </div>
							<div class="col-md-6">
                                <label class="form-label">{{ __('custom_admin.label_twitter_link') }}</label>
								{{ Form::text('twitter_link', $websiteSettings['twitter_link'] ?? null, [
																						'id' => 'twitter_link',
																						'class' => 'form-control',
																						'placeholder' => __('custom_admin.placeholder_twitter_link')
																						]) }}
                            </div>
							<div class="col-md-6">
                                <label class="form-label">{{ __('custom_admin.label_instagram_link') }}</label>
								{{ Form::text('instagram_link', $websiteSettings['instagram_link'] ?? null, [
																									'id' => 'instagram_link',
																									'class' => 'form-control',
																									'placeholder' => __('custom_admin.placeholder_instagram_link')
																									]) }}
                            </div>
							<div class="col-md-6">
                                <label class="form-label">{{ __('custom_admin.label_linkedin_link') }}</label>
								{{ Form::text('linkedin_link', $websiteSettings['linkedin_link'] ?? null, [
																										'id' => 'linkedin_link',
																										'class' => 'form-control',
																										'placeholder' => __('custom_admin.placeholder_linkedin_link')
																										]) }}
                            </div>
							<div class="col-md-6">
                                <label class="form-label">{{ __('custom_admin.label_pinterest_link') }}</label>
								{{ Form::text('pinterest_link', $websiteSettings['pinterest_link'] ?? null, [
																										'id' => 'pinterest_link',
																										'class' => 'form-control',
																										'placeholder' => __('custom_admin.placeholder_pinterest_link')
																										]) }}
                            </div>
							<div class="col-md-6">
                                <label class="form-label">{{ __('custom_admin.label_googleplus_link') }}</label>
								{{ Form::text('googleplus_link', $websiteSettings['googleplus_link'] ?? null, [
																										'id' => 'googleplus_link',
																										'class' => 'form-control',
																										'placeholder' => __('custom_admin.placeholder_googleplus_link')
																										]) }}
                            </div>
							<div class="col-md-6">
                                <label class="form-label">{{ __('custom_admin.label_youtube_link') }}</label>
								{{ Form::text('youtube_link', $websiteSettings['youtube_link'] ?? null, [
																										'id' => 'youtube_link',
																										'class' => 'form-control',
																										'placeholder' => __('custom_admin.placeholder_youtube_link')
																										]) }}
                            </div>
							<div class="col-md-6">
                                <label class="form-label">{{ __('custom_admin.label_rss_link') }}</label>
								{{ Form::text('rss_link', $websiteSettings['rss_link'] ?? null, [
																								'id' => 'rss_link',
																								'class' => 'form-control',
																								'placeholder' => __('custom_admin.placeholder_rss_link')
																								]) }}
                            </div>
							<div class="col-md-6">
                                <label class="form-label">{{ __('custom_admin.label_dribble_link') }}</label>
								{{ Form::text('dribble_link', $websiteSettings['dribble_link'] ?? null, [
																								'id' => 'dribble_link',
																								'class' => 'form-control',
																								'placeholder' => __('custom_admin.placeholder_dribble_link')
																								]) }}
                            </div>
							<div class="col-md-6">
                                <label class="form-label">{{ __('custom_admin.label_tumblr_link') }}</label>
								{{ Form::text('tumblr_link', $websiteSettings['tumblr_link'] ?? null, [
																								'id' => 'tumblr_link',
																								'class' => 'form-control',
																								'placeholder' => __('custom_admin.placeholder_tumblr_link')
																								]) }}
                            </div>
							<div class="col-md-6">
                                <label class="form-label">{{ __('custom_admin.label_website_title') }}<span class="red_star">*</span></label>
								{{ Form::text('website_title', $websiteSettings['website_title'] ?? null, [
																								'id' => 'website_title',
																								'class' => 'form-control',
																								'placeholder' => __('custom_admin.placeholder_website_title'),
																								'required' => true
																								]) }}
                            </div>
							<div class="col-md-6">
                                <label class="form-label">{{ __('custom_admin.label_tag_line') }}</label>
								{{ Form::text('tag_line', $websiteSettings['tag_line'] ?? null, [
																								'id' => 'tag_line',
																								'class' => 'form-control',
																								'placeholder' => __('custom_admin.placeholder_tag_line')
																								]) }}
                            </div>
							<div class="col-md-6">
                                <label class="form-label">{{ __('custom_admin.label_default_meta_title') }}</label>
								{{ Form::text('default_meta_title', $websiteSettings['default_meta_title'] ?? null, [
																								'id' => 'default_meta_title',
																								'class' => 'form-control',
																								'placeholder' => __('custom_admin.placeholder_default_meta_title')
																								]) }}
                            </div>
							<div class="col-md-6">
                                <label class="form-label">{{ __('custom_admin.label_default_meta_keyword') }}</label>
								{{ Form::text('default_meta_keywords', $websiteSettings['default_meta_keywords'] ?? null, [
																								'id' => 'default_meta_keywords',
																								'class' => 'form-control',
																								'placeholder' => __('custom_admin.placeholder_default_meta_keywords')
																								]) }}
                            </div>
							<div class="col-md-6">
                                <label class="form-label">{{ __('custom_admin.label_default_meta_description') }}</label>
								{{ Form::textarea('default_meta_description', $websiteSettings['default_meta_description'] ?? null, [
																								'id' => 'default_meta_description',
																								'class' => 'form-control',
																								'rows'	=> 3,
																								'placeholder' => __('custom_admin.placeholder_default_meta_description')
																								]) }}
                            </div>
							<div class="col-md-6">
                                <label class="form-label">{{ __('custom_admin.label_address') }}</label>
								{{ Form::textarea('address', $websiteSettings['address'] ?? null, [
																								'id' => 'address',
																								'class' => 'form-control',
																								'rows'	=> 3,
																								'placeholder' => __('custom_admin.placeholder_address')
																								]) }}
                            </div>
                        </div>
						<hr class="my-4 mx-n4">
						<div class="row g-3">
                            <div class="col-md-6 d-flex align-items-start align-items-sm-center gap-3">
								@php
                                $logo = asset("images/admin/avatars/1.png");
								if (isset($websiteSettings->logo) && $websiteSettings->logo != null) :
									if (file_exists(public_path('/images/uploads/'.$pageRoute.'/'.$websiteSettings->logo))) :
										$logo = asset('images/uploads/'.$pageRoute.'/'.$websiteSettings->logo);
									endif;
								endif;
                                @endphp
                                
                                <div class="preview_img_div_upload position_relative" style="position: relative;">
                                    <img src="{{ $logo }}" alt="user-avatar" class="d-block rounded" height="100" width="100" id="uploadedAvatar" />
                                    <img id="upload_preview" class="mt-2" style="display: none;" />
                                </div>

                                <div class="button-wrapper">
                                    <label for="upload" class="btn rounded-pill btn-dark mb-4" tabindex="0">
                                        <span class="d-none d-sm-block"><i class='bx bx-upload'></i> {{ __('custom_admin.label_upload_logo') }}</span>
                                        <i class="bx bx-upload d-block d-sm-none"></i>
                                        {{ Form::file('logo', array(
																	'id' => 'upload',
																	'class' => 'account-file-input upload-image',
                                                                    'hidden' => true
																	)) }}
                                    </label>
                                    <p class="text-muted mb-0">{{ __('custom_admin.message_allowed_file_types', ['fileTypes' => config('global.IMAGE_FILE_TYPES')]) }} </p>
                                </div>
							</div>

							<div class="mt-4">
								<a class="btn rounded-pill btn-secondary btn-buy-now text-white" id="btn-cancel" href="{{ route($routePrefix.'.account.dashboard') }}"><i class='bx bx-left-arrow-circle'></i> {{ __('custom_admin.btn_cancel') }}</a>
                                <button type="submit" class="btn rounded-pill btn-primary float-right" id="btn-updating"><i class='bx bx-save'></i> {{ __('custom_admin.btn_update') }}</button>
                            </div>
						</div>
					{{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- / Content -->
@endsection

@push('scripts')
    @include($routePrefix.'.includes.image_preview')
@endpush
