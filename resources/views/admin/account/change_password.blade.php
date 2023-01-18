@extends('admin.layouts.app', ['title' => $pageTitle])

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
						'route' => [$routePrefix.'.account.change-password'],
						'name'  => 'updateAdminPassword',
						'id'    => 'updateAdminPassword',
						'files' => true,
						'novalidate' => true]) }}
						@method('PATCH')
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('custom_admin.label_current_password') }}<span class="red_star">*</span></label>
								<div class="mb-3 form-password-toggle">
									<div class="input-group input-group-merge" id="current_password_div">
										{{ Form::password('current_password', array(
																			'id' => 'current_password',
																			'class' => 'form-control',
																			'placeholder' => '············',
																			'required' => true )) }}
										<span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
									</div>
								</div>
                            </div>
							<div class="col-md-6">
                                <label class="form-label">{{ __('custom_admin.label_new_password') }}<span class="red_star">*</span></label>
								<div class="mb-3 form-password-toggle">
									<div class="input-group input-group-merge" id="password_div">
										{{ Form::password('password', array(
																			'id' => 'password',
																			'class' => 'form-control password-checker',
																			'placeholder' => '············',
																			'data-pcid'	=> 'new-password-checker',
																			'required' => true )) }}
										<span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
									</div>
								</div>
								<div class="progress" id="new-password-checker" style="height: 6px;">
									<div class="progress" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
								</div>
                            </div>
							<div class="col-md-6">
                                <label class="form-label">{{ __('custom_admin.label_confirm_password') }}<span class="red_star">*</span></label>
								<div class="mb-3 form-password-toggle">
									<div class="input-group input-group-merge" id="confirm_password_div">
										{{ Form::password('confirm_password', array(
																			'id' => 'confirm_password',
																			'class' => 'form-control',
																			'placeholder' => '············',
																			'required' => true )) }}
										<span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
									</div>
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
