@extends('errors.layouts.error', ['title' => 'Not Found'])

@section('content')

    <!-- Error -->
    <div class="container-xxl container-p-y">
        <div class="misc-wrapper">
            <h2 class="mb-2 mx-2">@lang('custom_admin.error_page_not_found') :(</h2>
            <p class="mb-4 mx-2">@lang('custom_admin.message_page_not_found')</p>
            <a href="{{ url()->previous() }}" class="btn btn-primary">@lang('custom_admin.btn_back_to_home')</a>
            <div class="mt-3">
                <img src="{{ asset('images/admin/illustrations/page-misc-error-light.png') }}" alt="page-misc-error-light" width="500" class="img-fluid" data-app-dark-img="{{ asset('images/admin/illustrations/page-misc-error-dark.png') }}" data-app-light-img="{{ asset('images/admin/illustrations/page-misc-error-light.png') }}" />
            </div>
        </div>
    </div>
    <!-- /Error -->

@endsection
