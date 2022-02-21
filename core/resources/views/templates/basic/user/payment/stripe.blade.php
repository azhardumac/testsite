@extends($activeTemplate.'layouts.master')

@php
    $userDashboardImage = getContent('user.content', true);
@endphp

@section('content')

<section class="pt-100 pb-100 overlay--one bg_img" style="background-image: url('{{ getImage( 'assets/images/frontend/user/' .@$userDashboardImage->data_values->background_image, '1920x1080') }}');">
    <div class="container">
      <div class="row mb-none-30">

      </div><!-- row end -->

      <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <div class="custom--card rounded-3">
                    <div class="card-header">
                        <h5 class="card-title">@lang('Stripe Payment')</h5>
                    </div>
                    <div class="card-body card-body-deposit">

                        <div class="card-wrapper mb-3"></div>

                        <form role="form" id="payment-form" method="{{$data->method}}" action="{{$data->url}}">
                            {{csrf_field()}}
                            <input type="hidden" value="{{$data->track}}" name="track">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="name">@lang('CARD NAME')</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control form-control-lg custom-input" name="name" placeholder="@lang('Card Name')" autocomplete="off" autofocus/>
                                        <span class="input-group-text"><i class="fa fa-font"></i></span>
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <label for="cardNumber">@lang('CARD NUMBER')</label>
                                    <div class="input-group">
                                        <input type="tel" class="form-control form-control-lg custom-input" name="cardNumber" placeholder="@lang('Valid Card Number')" autocomplete="off" required autofocus/>
                                        <span class="input-group-text addon-bg"><i class="fa fa-credit-card"></i></span>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <label for="cardExpiry">@lang('EXPIRATION DATE')</label>
                                    <input type="tel" class="form-control form-control-lg input-sz custom-input" name="cardExpiry" placeholder="@lang('MM / YYYY')" autocomplete="off" required/>
                                </div>
                                <div class="col-md-6 ">
                                    <label for="cardCVC">@lang('CVC CODE')</label>
                                    <input type="tel" class="form-control form-control-lg input-sz custom-input" name="cardCVC" placeholder="@lang('CVC')" autocomplete="off" required/>
                                </div>
                            </div>
                            <button class="btn btn--base btn-shadow--base rounded-2 w-100 mt-4" type="submit"> @lang('PAY NOW')
                            </button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>
  </section>

@endsection


@push('script')
    <script type="text/javascript" src="https://rawgit.com/jessepollak/card/master/dist/card.js"></script>

    <script>
        (function ($) {
            "use strict";
            $(document).ready(function () {
                var card = new Card({
                    form: '#payment-form',
                    container: '.card-wrapper',
                    formSelectors: {
                        numberInput: 'input[name="cardNumber"]',
                        expiryInput: 'input[name="cardExpiry"]',
                        cvcInput: 'input[name="cardCVC"]',
                        nameInput: 'input[name="name"]'
                    }
                });
            });
        })(jQuery);
    </script>
@endpush
