@extends($activeTemplate.'layouts.master')

@php
    $userDashboardImage = getContent('user.content', true);
@endphp

@section('content')

<section class="pt-100 pb-100 overlay--one bg_img" style="background-image: url('{{ getImage( 'assets/images/frontend/user/' .@$userDashboardImage->data_values->background_image, '1920x1080') }}');">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-5">
          <div class="deposit-preview-card rounded-3">
            <h3 class="title mb-3">@lang('Deposit Confirm')</h3>
            <ul class="deposit-preview-list">
              <li>@lang('Amount'): <strong>{{getAmount($data->amount)}} </strong> {{__($general->cur_text)}}
              <li>@lang('Charge'): <strong>{{getAmount($data->charge)}}</strong> {{__($general->cur_text)}}
              <li>@lang('Payable'): <strong> {{getAmount($data->amount + $data->charge)}}</strong> {{__($general->cur_text)}}
              <li>Payment Gateway: <span class="fw-bold">{{ __($data->gateway->name) }}</span></li>
              <li>@lang('Conversion Rate'): <strong>1 {{__($general->cur_text)}} = {{getAmount($data->rate)}}  {{__($data->baseCurrency())}}</strong>
            </ul>

            @if( 1000 >$data->method_code)
                <a href="{{route('user.deposit.confirm')}}" class="btn btn--base btn-shadow--base rounded-2 w-100 mt-4">@lang('Pay Now')</a>
            @else
                <a href="{{route('user.deposit.manual.confirm')}}" class="btn btn--base btn-shadow--base rounded-2 w-100 mt-4">@lang('Pay Now')</a>
            @endif

          </div>
        </div>
      </div>
    </div>
</section>

@stop



