@extends('admin.layouts.app', ['title' => $pageTitle])

@section('content')

    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Forms /</span> Basic Inputs</h4>

        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <h5 class="card-header">Default</h5>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                              <label class="form-label" for="multicol-username">Username</label>
                              <input type="text" id="multicol-username" class="form-control" placeholder="john.doe">
                            </div>
                            <div class="col-md-6">
                              <label class="form-label" for="multicol-email">Email</label>
                              <div class="input-group input-group-merge">
                                <input type="text" id="multicol-email" class="form-control" placeholder="john.doe" aria-label="john.doe" aria-describedby="multicol-email2" style="background-image: url(&quot;chrome-extension://hlpjhlifkgmoibhollggngbbhbejecph/icons/icon.svg&quot;); background-repeat: no-repeat; background-position: right 3px center !important;">
                                <span class="input-group-text" id="multicol-email2">@example.com</span>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-password-toggle">
                                <label class="form-label" for="multicol-password">Password</label>
                                <div class="input-group input-group-merge">
                                  <input type="password" id="multicol-password" class="form-control" placeholder="············" aria-describedby="multicol-password2" style="background-image: url(&quot;chrome-extension://hlpjhlifkgmoibhollggngbbhbejecph/icons/icon.svg&quot;); background-repeat: no-repeat; background-position: right 3px center !important;">
                                  <span class="input-group-text cursor-pointer" id="multicol-password2"><i class="bx bx-hide"></i></span>
                                </div>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-password-toggle">
                                <label class="form-label" for="multicol-confirm-password">Confirm Password</label>
                                <div class="input-group input-group-merge">
                                  <input type="password" id="multicol-confirm-password" class="form-control" placeholder="············" aria-describedby="multicol-confirm-password2" style="background-image: url(&quot;chrome-extension://hlpjhlifkgmoibhollggngbbhbejecph/icons/icon.svg&quot;); background-repeat: no-repeat; background-position: right 3px center !important;">
                                  <span class="input-group-text cursor-pointer" id="multicol-confirm-password2"><i class="bx bx-hide"></i></span>
                                </div>
                              </div>
                            </div>
                          </div>
                    </div>
                </div>
            </div>
            

            
        </div>
    </div>
    <!-- / Content -->
@endsection
