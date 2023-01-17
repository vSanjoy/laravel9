@if (strpos(Route::currentRouteName(), 'dashboard') === false)
<h6 class="fw-bold py-1 mb-4">
    <span class="text-muted fw-light">
        <a href="{{ route($routePrefix.'.account.dashboard') }}" class="">{{ __('custom_admin.label_dashboard') }}</a> /
    </span>
    @if (strpos(Route::currentRouteName(), 'settings') === false && strpos(Route::currentRouteName(), 'change-password') === false && strpos(Route::currentRouteName(), 'profile') === false)
        @if (isset($breadcrumb[$pageType]) && count($breadcrumb[$pageType]) > 0)
            @foreach ($breadcrumb[$pageType] as $pageValue)
                @if ($pageValue['url'] != '')
                    <a href="{{ $pageValue['url'] }}" class="">{{ $pageValue['label'] }}</a> / 
                @else
                    {{ $pageValue['label'] }}
                @endif
            @endforeach
        @endif
    @else
        {{ $pageTitle }}
    @endif
</h6>
@endif

{{-- <div class="page-breadcrumb">
    <div class="row">
    @if (strpos(Route::currentRouteName(), 'dashboard') === false)
        <div class="col-12 align-self-center">
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route($routePrefix.'.account.dashboard') }}" class="">@lang('custom_admin.label_dashboard')</a></li>
                @if (strpos(Route::currentRouteName(), 'website-settings') === false && strpos(Route::currentRouteName(), 'change-password') === false && strpos(Route::currentRouteName(), 'profile') === false)
                    @if (isset($breadcrumb[$pageType]) && count($breadcrumb[$pageType]) > 0)
                        @foreach ($breadcrumb[$pageType] as $pageValue)
                            @if ($pageValue['url'] != '')
                                <li class="breadcrumb-item"><a href="{{ $pageValue['url'] }}" class="">{{ $pageValue['label'] }}</a></li>
                            @else
                                <li class="breadcrumb-item active">{{ $pageValue['label'] }}</li>
                            @endif
                        @endforeach
                    @endif
                @else
                        <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle }}</li>
                @endif
                    </ol>
                </nav>
            </div>
        </div>
    @endif
    </div>
</div> --}}