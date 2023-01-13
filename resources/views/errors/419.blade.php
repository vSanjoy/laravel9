@extends('errors::layout', ['title' => 'Page Expired'])

@section('content')

    <!-- Error -->
    <div class="container-xxl container-p-y">
        <div class="misc-wrapper">
            <h2 class="mb-2 mx-2">@lang('custom_admin.error_419') :(</h2>
            <p class="mb-4 mx-2">&nbsp;</p>
            <a href="{{ url()->previous() }}" class="btn btn-primary">@lang('custom_admin.btn_back_to_home')</a>
            <div class="mt-3">
                <img src="{{ asset('images/admin/illustrations/girl-doing-yoga-light.png') }}" alt="page-misc-error-light" width="500" class="img-fluid" data-app-dark-img="{{ asset('images/admin/illustrations/girl-doing-yoga-light.png') }}" data-app-light-img="{{ asset('images/admin/illustrations/girl-doing-yoga-light.png') }}" />
            </div>
        </div>
    </div>
    <!-- /Error -->

@endsection
