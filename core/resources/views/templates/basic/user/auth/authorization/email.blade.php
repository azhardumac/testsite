@extends($activeTemplate .'layouts.blankMaster')

@php
    $recovery = getContent('ac_recovery.content', true);
@endphp

@section('content')

<section class="account-section bg_img" style="background-image: url(' {{ getImage( 'assets/images/frontend/ac_recovery/' .@$recovery->data_values->background_image, '1920x1010') }} ');">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-xl-6 col-lg-10 col-md-8">
          <div class="card custom--card account-wrapper">
            <div class="card-body p-4">
              <div class="row justify-content-center">

                <div class="account-thumb-area text-center">
                  <a href="{{ route('home') }}" class="account-wrapper-logo">
                      <img src="{{getImage(imagePath()['logoIcon']['path'] .'/logo.png')}}" alt="image">
                  </a>
                    <h3 class=" text-center">@lang('Enter Verification Code')</h3>
                </div>

                    <div class="verification-code-form">

                    <form action="{{route('user.verify_email')}}" method="POST" class="login-form">
                        @csrf

                        <div id="phoneInput">
                            <div class="field-wrapper">
                                <div class=" phone">
                                    <input type="text" name="email_verified_code[]" class="letter" pattern="[0-9]*" inputmode="numeric" maxlength="1">
                                    <input type="text" name="email_verified_code[]" class="letter" pattern="[0-9]*" inputmode="numeric" maxlength="1">
                                    <input type="text" name="email_verified_code[]" class="letter" pattern="[0-9]*" inputmode="numeric" maxlength="1">
                                    <input type="text" name="email_verified_code[]" class="letter" pattern="[0-9]*" inputmode="numeric" maxlength="1">
                                    <input type="text" name="email_verified_code[]" class="letter" pattern="[0-9]*" inputmode="numeric" maxlength="1">
                                    <input type="text" name="email_verified_code[]" class="letter" pattern="[0-9]*" inputmode="numeric" maxlength="1">
                                </div>
                            </div>
                        </div>

                      <div class="form-group col-lg-12 mt-4">
                        <button type="submit" class="btn btn--base w-100 justify-content-center">@lang('Submit')</button>
                      </div>

                        <div class="form-group">
                            <p>@lang('Please check including your Junk/Spam Folder. if not found, you can') </p>
                            <a href="{{route('user.send_verify_code')}}?type=email" class="forget-pass"> @lang('Resend')?</a>
                            @if ($errors->has('resend'))
                                <small class="text-white">{{ $errors->first('resend') }}</small>
                            @endif
                        </div>


                    </form>

                    </div>

                </div>

              </div>
            </div>
          </div><!-- card end -->
        </div>
      </div>
    </div>
  </div>
</section>
@endsection


@push('script-lib')
    <script src="{{ asset($activeTemplateTrue. 'js/jquery.inputLettering.js') }}"></script>
@endpush

@push('style')
    <style>

        #phoneInput .field-wrapper {
            position: relative;
            text-align: center;
        }

        #phoneInput .form-group {
            min-width: 300px;
            width: 50%;
            margin: 4em auto;
            display: flex;
            border: 1px solid rgba(96, 100, 104, 0.3);
        }

        #phoneInput .letter {
            height: 50px;
            border-radius: 0;
            text-align: center;
            max-width: calc((100% / 10) - 1px);
            flex-grow: 1;
            flex-shrink: 1;
            flex-basis: calc(100% / 10);
            outline-style: none;
            padding: 5px 0;
            font-size: 18px;
            font-weight: bold;
            color: red;
            border: 1px solid #0e0d35;
        }

        @media (max-width: 480px) {
            #phoneInput .field-wrapper {
                width: 100%;
            }

            #phoneInput .letter {
                font-size: 16px;
                padding: 2px 0;
                height: 35px;
            }
        }

        #phoneInput .letter {
            margin-right: 2px;
            margin-left: 2px;
            max-width: calc(100% / 7) !important;
        }
        #phoneInput .field-wrapper {
            max-width: 450px;
            margin: 0 auto;
        }

    </style>
@endpush

@push('script')
    <script>
        (function($){
            "use strict";

            $('#phoneInput').letteringInput({
                inputClass: 'letter',
                onLetterKeyup: function ($item, event) {
                    console.log('$item:', $item);
                    console.log('event:', event);
                },
                onSet: function ($el, event, value) {
                    console.log('element:', $el);
                    console.log('event:', event);
                    console.log('value:', value);
                }
            });

        })(jQuery);
    </script>
@endpush
