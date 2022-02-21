@extends($activeTemplate.'layouts.blankMaster')

@php
    $pageLinks = getContent('links.element');
    $register = getContent('register.content', true);
@endphp

@section('content')

<section class="account-section bg_img" style="background-image: url('{{ getImage( 'assets/images/frontend/register/' .@$register->data_values->background_image, '1920x1010') }}');">
    <div class="account-area">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-7">
            <div class="account-wrapper">
              <form class="account-form" action="{{ route('user.register') }}" method="POST" onsubmit="return submitUserForm();">
                @csrf
                <div class="account-thumb-area text-center">
                  <a href="{{ route('home') }}" class="account-wrapper-logo">
                      <img src="{{getImage(imagePath()['logoIcon']['path'] .'/logo.png')}}" alt="image">
                  </a>
                  <h4 class="title">{{ __(@$register->data_values->heading) }}</h4>
                </div>
                <div class="row">

                @if(session()->get('reference') != null)
                  <div class="col-md-12 form-group">
                    <label for="reference" class="col-md-12 col-form-label text-md-right">@lang('Reference By')*</label>
                    <input id="firstname" type="text" id="reference" class="form-control" name="referBy" value="{{session()->get('reference')}}" readonly>
                  </div>
                @endif

                  <div class="col-md-6 form-group">
                    <label for="firstname" class="col-md-12 col-form-label text-md-right">@lang('First Name')*</label>
                    <input id="firstname" placeholder="@lang('First Name')" type="text" class="form-control" name="firstname" value="{{ old('firstname') }}" required>
                  </div>
                  <div class="col-md-6 form-group">
                    <label for="lastname" class="col-md-12 col-form-label text-md-right">@lang('Last Name')*</label>
                    <input id="lastname" placeholder="@lang('Last Name')" type="text" class="form-control" name="lastname" value="{{ old('lastname') }}" required>
                  </div>
                  <div class="col-md-6 form-group">
                    <label for="username" class="col-md-12 col-form-label text-md-right">@lang('Username')*</label>
                    <input id="username" placeholder="@lang('Username')" type="text" class="form-control" name="username" value="{{ old('username') }}" required>
                  </div>

                  <div class="col-md-6 form-group">
                    <label for="mobile" class="col-md-12 col-form-label text-md-right">@lang('Mobile')*</label>

                    <div class="form-group country-code mb-0">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <select name="country_code" class="select">
                                        @include('partials.country_code')
                                    </select>
                                </span>
                            </div>
                            <input type="text" name="mobile" class="form-control" placeholder="@lang('Phone Number')">
                        </div>
                    </div>

                  </div>

                  <div class="col-md-6 form-group">
                    <label for="country" class="col-md-12 col-form-label text-md-right">@lang('Country')*</label>
                    <input type="text" name="country" class="form-control" readonly>
                  </div>
                  <div class="col-md-6 form-group">
                    <label for="email" class="col-md-12 col-form-label text-md-right">@lang('E-Mail Address')*</label>
                    <input id="email" type="email" class="form-control" placeholder="Email" name="email" value="{{ old('email') }}" required>
                  </div>
                  <div class="col-md-6 form-group">
                    <label for="password" class="col-md-12 col-form-label text-md-right">@lang('Password')*</label>
                    <input id="password" type="password" placeholder="Password" class="form-control" name="password" required autocomplete="new-password">
                  </div>
                  <div class="col-md-6 form-group">
                    <label for="password-confirm" class="col-md-12 col-form-label text-md-right">@lang('Confirm Password')*</label>
                    <input id="password-confirm" placeholder="Confirm Password" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                  </div>

                  <div class="col-md-6 form-group">
                    @php echo recaptcha() @endphp
                  </div>

                  @include($activeTemplate.'partials.custom-captcha')

                  <div class="col-md-12 form-group">
                    <input type="checkbox" name="terms" id="terms" value="1" required="">
                    <label for="terms">@lang('I agree with')</label>

                    @foreach($pageLinks as $link)
                    <a class="text--base" target="_blank" href="{{ route('slug.page', ['id'=>$link->id, 'slug'=>slug($link->data_values->title)]) }}">{{ __($link->data_values->title) }}</a>
                        {{ $loop->last != true ? ',' : '' }}
                    @endforeach

                  </div>

                  <div class="col-md-12 form-group">
                    <button type="submit" class="btn btn--base w-100">@lang('Registration Now')</button>
                  </div>
                  <div class="col-md-12 text-end">
                    <p class="text-white text-center">@lang('Do you have an account')? <a href="{{ route('user.login') }}" class="text--base">@lang('Login here').</a></p>
                  </div>
                </div><!-- row end -->
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

@endsection

@push('style')
<style type="text/css">
    .country-code .input-group-prepend .input-group-text{
        background: #fff !important;
    }
    .country-code select{
        border: none;
    }
    .country-code select:focus{
        border: none;
        outline: none;
    }
</style>
@endpush

@push('script')
    <script>
      "use strict";

      @if($country_code)
        var t = $(`option[data-code={{ $country_code }}]`).attr('selected','');
      @endif

        $('select[name=country_code]').change(function(){
            $('input[name=country]').val($('select[name=country_code] :selected').data('country'));
        }).change();
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

