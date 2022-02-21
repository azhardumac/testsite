@extends($activeTemplate.'layouts.blankMaster')

@php
    $login = getContent('login.content', true);
@endphp

@section('content')

<section class="account-section bg_img" style="background-image: url('{{ getImage( 'assets/images/frontend/login/' .@$login->data_values->background_image, '1920x1010') }}');">
    <div class="account-area">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-5">
            <div class="account-wrapper">
              <form class="account-form" method="POST" action="{{ route('user.login')}}">
                @csrf
                <div class="account-thumb-area text-center">
                  <a href="{{ route('home') }}" class="account-wrapper-logo">
                    <img src="{{getImage(imagePath()['logoIcon']['path'] .'/logo.png')}}" alt="image">
                  </a>

                  <h3 class="title">{{ __(@$login->data_values->heading) }}</h3>
                </div>
                <div class="form-group">
                  <label>@lang('Username') <sup class="">*</sup></label>
                  <input type="text" name="username" value="{{ old('username') }}" placeholder="@lang('Enter your username')" class="form-control" required>
                </div>

                <div class="form-group">
                  <label>@lang('Password') <sup class="">*</sup></label>
                  <input id="password" type="password" class="form-control" name="password" required autocomplete="current-password" placeholder="@lang('Enter password')">
                </div>

                <div class="form-group">
                    <div class="col-md-12">
                        @php echo recaptcha() @endphp
                    </div>
                </div>

                @include($activeTemplate.'partials.custom-captcha')

                <div class="form-group">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember">
                        @lang('Remember Me')
                    </label>
                </div>

                <button type="submit" class="btn btn--base w-100">@lang('Login Now')</button>
                <p class="text-center mt-3"><span class="text-white">@lang('New to') {{ $general->sitename }}?</span> <a href="{{ route('user.register') }}" class="text--base">@lang('Signup here').</a>
                </p>
                <p class="text-center">@lang('or') <a href="{{route('user.password.request')}}" class="text--base">@lang('Forgot Password')?</a></p>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

@endsection

@push('script')
    <script>
        "use strict";
        function submitUserForm() {
            var response = grecaptcha.getResponse();
            if (response.length == 0) {
                document.getElementById('g-recaptcha-error').innerHTML = '<span style="color:red;">@lang("Captcha field is required.")</span>';
                return false;
            }
            return true;
        }
        function verifyCaptcha() {
            document.getElementById('g-recaptcha-error').innerHTML = '';
        }
    </script>
@endpush
