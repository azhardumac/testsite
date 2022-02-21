@extends($activeTemplate.'layouts.master')

@php
    $userDashboardImage = getContent('user.content', true);
@endphp

@section('content')
<!-- change password section start -->
<section class="pt-100 pb-100 overlay--one bg_img" style="background-image: url('{{ getImage( 'assets/images/frontend/user/' .@$userDashboardImage->data_values->background_image, '1920x1080') }}');">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-6">
          <div class="account-wrapper mt-5">
            <form class="account-form" action="" method="post">
                @csrf
              <div class="account-thumb-area text-center">
                <div class="account-thumb">
                  <i class="las la-user"></i>
                </div>
                <h4 class="title">@lang('Change your password').</h4>
              </div>
              <div class="form-group">
                <label for="password">@lang('Current Password') <sup class="">*</sup></label>
                <input type="password" id="password" name="current_password" class="form-control" placeholder="Enter current password">
              </div>
              <div class="form-group">
                <label for="password2">@lang('New Password') <sup class="">*</sup></label>
                <input type="password" name="password" class="form-control" id="password2" placeholder="Enter new password">
              </div>
              <div class="form-group">
                <label for="confirm_password">@lang('Confirm Password') <sup class="">*</sup></label>
                <input type="password" id="confirm_password" name="password_confirmation" class="form-control" placeholder="Enter confirm password">
              </div>
              <button type="submit" class="btn btn--base w-100">@lang('Change Password')</button>
            </form>
          </div><!-- account-wrapper end -->
        </div>
      </div>
    </div>
  </section>
  <!-- change password section end -->
@endsection

