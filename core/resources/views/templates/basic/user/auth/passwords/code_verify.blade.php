@extends($activeTemplate.'layouts.blankMaster')

@php
    $recovery = getContent('ac_recovery.content', true);
@endphp

@section('content')

<section class="account-section bg_img" style="background-image: url(' {{ getImage( 'assets/images/frontend/ac_recovery/' .@$recovery->data_values->background_image, '1920x1010') }} ');">
    <div class="account-area">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-6">
            <div class="account-wrapper">

                <div class="account-thumb-area text-center">
                  <a href="{{ route('home') }}" class="account-wrapper-logo">
                    <img src="{{getImage(imagePath()['logoIcon']['path'] .'/logo.png')}}" alt="image">
                  </a>
                </div>

                <form action="{{ route('user.password.verify-code') }}" method="POST" class="cmn-form mt-30">
                    @csrf

                    <input type="hidden" name="email" value="{{ $email }}">

                    <div class="form-group">

                        <h3 class="justify-content-center text-center d-flex m-4">@lang('Verification Code')</h3>

                        <div id="phoneInput">
                            <div class="field-wrapper">
                                <div class=" phone">
                                    <input type="text" name="code[]" class="letter"
                                           pattern="[0-9]*" inputmode="numeric" maxlength="1">
                                    <input type="text" name="code[]" class="letter"
                                           pattern="[0-9]*" inputmode="numeric" maxlength="1">
                                    <input type="text" name="code[]" class="letter"
                                           pattern="[0-9]*" inputmode="numeric" maxlength="1">
                                    <input type="text" name="code[]" class="letter"
                                           pattern="[0-9]*" inputmode="numeric" maxlength="1">
                                    <input type="text" name="code[]" class="letter"
                                           pattern="[0-9]*" inputmode="numeric" maxlength="1">
                                    <input type="text" name="code[]" class="letter"
                                           pattern="[0-9]*" inputmode="numeric" maxlength="1">
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="form-group col-lg-12">
                        <button type="submit" class="btn btn--base w-100">@lang('Verify Code') <i class="las la-sign-in-alt"></i></button>
                    </div>

                    <div class="form-group">
                        <p>@lang('Please check including your Junk/Spam Folder. if not found, you can')</p>
                        <a href="{{ route('user.password.request') }}" class="">@lang('Resend') ?</a>
                    </div>

                </form>

            </div>
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
