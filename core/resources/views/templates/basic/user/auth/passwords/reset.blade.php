@extends($activeTemplate.'layouts.blankMaster')

@php
    $recovery = getContent('ac_recovery.content', true);
@endphp

@section('content')

<section class="account-section bg_img" style="background-image: url(' {{ getImage( 'assets/images/frontend/ac_recovery/' .@$recovery->data_values->background_image, '1920x1010') }} ');">
    <div class="account-area">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-5">
            <div class="account-wrapper">
                <form method="POST" action="{{ route('user.password.update') }}">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">
                <input type="hidden" name="token" value="{{ $token }}">
                <div class="account-thumb-area text-center">
                  <a href="{{ route('home') }}" class="account-wrapper-logo">
                    <img src="{{getImage(imagePath()['logoIcon']['path'] .'/logo.png')}}" alt="image">
                  </a>

                  <h3 class="title">@lang('Reset Password')</h3>
                </div>
                <div class="form-group">
                  <label for="password">@lang('Password') <sup class="">*</sup></label>
                  <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="@lang('New Password')">
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong class="text-white">{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                  <label for="password-confirm">@lang('Confirm Password') <sup class="">*</sup></label>
                  <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="@lang('Confirm Password')">
                </div>

                <div class="form-group">
                    <div class="col-md-12">
                        @php echo recaptcha() @endphp
                    </div>
                </div>

                <div class="button">
                    <button type="submit" class="btn btn--base w-100">@lang('Reset Password')</button>
                </div>

              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
