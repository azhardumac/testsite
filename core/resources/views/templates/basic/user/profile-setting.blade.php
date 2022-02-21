@extends($activeTemplate.'layouts.master')

@php
    $userDashboardImage = getContent('user.content', true);
@endphp

@section('content')

<section class="pt-100 pb-100 overlay--one bg_img" style="background-image: url('{{ getImage( 'assets/images/frontend/user/' .@$userDashboardImage->data_values->background_image, '1920x1080') }} ');">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-xl-10">
          <form class="profile-form rounded-3" action="" method="post" enctype="multipart/form-data">
            @csrf
            <div class="profile-thumb-wrapper text-center">
              <div class="profile-thumb">
                <div class="avatar-preview">
                  <div class="profilePicPreview" style="background-image: url(' {{ getImage(imagePath()['profile']['user']['path'].'/'. $user->image,imagePath()['profile']['user']['size']) }} ')"></div>
                </div>
                <div class="avatar-edit">
                  <input type='file' class="profilePicUpload" name="image" id="profilePicUpload1" accept=".png, .jpg, .jpeg" />
                  <label for="profilePicUpload1"><i class="la la-pencil"></i></label>
                </div>
              </div>
            </div>
            <div class="row mt-5">
              <div class="col-lg-6 form-group">
                <label for="InputFirstname" class="col-form-label">@lang('First Name'):</label>
                <input type="text" class="form-control" id="InputFirstname" name="firstname" placeholder="@lang('First Name')" value="{{$user->firstname}}" readonly>
              </div>
              <div class="col-lg-6 form-group">
                <label for="lastname" class="col-form-label">@lang('Last Name'):</label>
                <input type="text" class="form-control" id="lastname" name="lastname" placeholder="@lang('Last Name')" value="{{$user->lastname}}" readonly>
              </div>
              <div class="col-lg-6 form-group">
                <label for="email" class="col-form-label">@lang('E-mail Address'):</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="@lang('E-mail Address')" value="{{$user->email}}" readonly>
              </div>
              <div class="col-lg-6 form-group">
                <input type="hidden" id="track" name="country_code">
                <label for="phone" class="col-form-label">@lang('Mobile Number')</label>
                <input type="tel" class="form-control pranto-control" id="phone" name="mobile" value="{{$user->mobile}}" placeholder="@lang('Your Contact Number')" readonly>
              </div>
              <input type="hidden" name="country" id="country" class="form-control d-none" value="{{@$user->address->country}}">
              <div class="col-lg-6 form-group">
                <label for="address" class="col-form-label">@lang('Address'):</label>
                <input type="text" class="form-control" id="address" name="address" placeholder="@lang('Address')" value="{{@$user->address->address}}" required="">
              </div>
              <div class="col-lg-6 form-group">
                <label for="state" class="col-form-label">@lang('State'):</label>
                <input type="text" class="form-control" id="state" name="state" placeholder="@lang('state')" value="{{@$user->address->state}}" required="">
              </div>
              <div class="col-lg-6 form-group">
                <label for="zip" class="col-form-label">@lang('Zip Code'):</label>
                <input type="text" class="form-control" id="zip" name="zip" placeholder="@lang('Zip Code')" value="{{@$user->address->zip}}" required="">
              </div>
              <div class="col-lg-6 form-group">
                <label for="city" class="col-form-label">@lang('City'):</label>
                <input type="text" class="form-control" id="city" name="city" placeholder="@lang('City')" value="{{@$user->address->city}}" required="">
              </div>
              <div class="col-lg-12 text-end">
                <button type="submit" class="btn btn--base w-100 justify-content-center">@lang('Update Profile')</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
@push('style-lib')
    <link href="{{ asset($activeTemplateTrue.'css/bootstrap-fileinput.css') }}" rel="stylesheet">
@endpush
@push('style')
    <link rel="stylesheet" href="{{asset('assets/admin/build/css/intlTelInput.css')}}">
    <style>
        .intl-tel-input {
            position: relative;
            display: inline-block;
            width: 100%;!important;
        }
    </style>
@endpush


@push('script')
<script>

    "use strict";

    function proPicURL(input) {
      if (input.files && input.files[0]) {
          var reader = new FileReader();
          reader.onload = function(e) {
              var preview = $(input).parents('.profile-thumb').find('.profilePicPreview');
              $(preview).css('background-image', 'url(' + e.target.result + ')');
              $(preview).addClass('has-image');
              $(preview).hide();
              $(preview).fadeIn(650);
          }
          reader.readAsDataURL(input.files[0]);
      }
    }
    $(".profilePicUpload").on('change', function() {
      proPicURL(this);
    });

    $(".remove-image").on('click', function(){
      $(".profilePicPreview").css('background-image', 'none');
      $(".profilePicPreview").removeClass('has-image');
    })
  </script>
@endpush
