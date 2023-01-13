@extends('admin.layouts.auth', ['title' => $pageTitle])

@section('content')
    <!-- Forgot Password -->
    <div class="card">
        <div class="card-body">
            <!-- Logo -->
            @include('admin.includes.logo')
            <!-- /Logo -->
            <h4 class="mb-2">@lang('custom_admin.label_forgot_password')? 🔒</h4>
            <p class="mb-4">@lang('custom_admin.message_registered_email')</p>

            {{ Form::open([
                'method' => 'POST',
                'class' => 'mb-3',
                'route' => [$routePrefix . '.' . $as . '.forgot-password'],
                'name' => 'forgotPasswordForm',
                'id' => 'forgotPasswordForm',
                'files' => true,
                'novalidate' => true,
            ]) }}
            <div class="mb-3">
                <label for="email" class="form-label">@lang('custom_admin.label_email')<span class="red_star">*</span></label>
                {{ Form::text('email', null, [
                    'id' => 'email',
                    'class' => 'form-control',
                    'placeholder' => 'Enter your email',
                    'required' => true,
                ]) }}
            </div>
            <button class="btn btn-primary d-grid w-100">@lang('custom_admin.btn_send_reset_link')</button>
            {{ Form::close() }}
            
            <div class="text-center">
                <a href="{{ route($routePrefix . '.' . $as . '.login') }}" class="d-flex align-items-center justify-content-center">
                    <i class="bx bx-chevron-left scaleX-n1-rtl bx-sm"></i>
                    @lang('custom_admin.label_back_to_login')
                </a>
            </div>
        </div>
    </div>
    <!-- /Forgot Password -->

    
@endsection
