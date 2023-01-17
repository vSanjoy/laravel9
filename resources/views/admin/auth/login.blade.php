@extends('admin.layouts.auth', ['title' => $pageTitle])

@section('content')
    @php
    $email = $password = null;
    if ($superAdminDetails) {
        $email      = $superAdminDetails->email ?? null;
        $password   = env('DEMO_LOGIN_PASSWORD') ?? null;
    }
    // Get site settings data
    $getSiteSettings = getSiteSettings();
    @endphp

    <!-- Register -->
    <div class="card">
        <div class="card-body">
            <!-- Logo -->
            @include('admin.includes.logo')
            <!-- /Logo -->
            <p class="mb-4 text-center">{{ __('custom_admin.label_login_text') }}</p>

            @if ($superAdminDetails)
            <p class="text-center">
                {{ __('custom_admin.label_demo_login_details') }}<br>
                <a class="clickToCopy" href="javascript: void(0);" data-type="email" data-values="{{ $email }}" data-microtip-position="top" role="tooltip" aria-label="{{ trans('custom_admin.label_click_to_copy') }}">
                    {{ $email }}
                </a> / 
                <a class="clickToCopy" href="javascript: void(0);" data-type="password" data-values="{{ $password }}" data-microtip-position="top" role="tooltip" aria-label="{{ trans('custom_admin.label_click_to_copy') }}">
                    {{ $password }}
                </a>
            </p>
            @endif

            <div class='copied'></div>

            {{ Form::open([
                'method'=> 'POST',
                'class' => 'mb-3',
                'route' => [$routePrefix.'.'.$as.'.login'],
                'name'  => 'adminLoginForm',
                'id'    => 'adminLoginForm',
                'files' => true,
                'novalidate' => true]) }}
                @method('PATCH')
                <div class="mb-3">
                    <label for="email" class="form-label">{{ __('custom_admin.label_email') }}<span class="red_star">*</span></label>
                    {{ Form::text('email', null, [
                                                'id' => 'email',
                                                'class' => 'form-control',
                                                'placeholder' => '',
                                                'required' => 'required',
                                                'placeholder' => 'Enter your email'
                                                ]) }}
                </div>
                <div class="mb-3 form-password-toggle">
                    <div class="d-flex justify-content-between">
                        <label class="form-label" for="password">{{ __('custom_admin.label_password') }}<span class="red_star">*</span></label>
                        <a href="{{ route($routePrefix.'.'.$as.'.forgot-password') }}"><small>{{ __('custom_admin.message_admin_forgot_password') }}</small>
                        </a>
                    </div>
                    <div class="input-group input-group-merge" id="password_div">
                        {{ Form::password('password', [
                                                        'id' => 'password',
                                                        'class' => 'form-control',
                                                        'placeholder' => '&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;',
                                                        'required' => true
                                                    ]) }}
                        <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="remember-me" name="remember_me">
                        <label class="form-check-label" for="remember-me"> {{ __('custom_admin.label_remember_me') }} </label>
                    </div>
                </div>
                <div class="mb-3">
                    <button class="btn rounded-pill btn-primary d-grid w-100" id="btn-processing" type="submit">
                        <i class='bx bx-log-in'></i> {{ __('custom_admin.label_sign_in') }}
                    </button>
                </div>
            {{ Form::close() }}

        </div>
    </div>
    <!-- /Register -->
@endsection
