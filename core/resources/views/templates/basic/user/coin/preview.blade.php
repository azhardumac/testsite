@extends($activeTemplate.'layouts.master')


@section('content')

<section class="pt-100 pb-100 overlay--one bg_img" style="background-image: url(' {{ asset($activeTemplateTrue.'images/bg/section.jpg') }}');">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-5">
          <div class="deposit-preview-card rounded-3">
            <h3 class="title mb-3">Buy Preview</h3>

            <ul class="deposit-preview-list">
            <form action="{{ route('user.coin.buy.confirm') }}" method="post">
                @csrf
                <input type="hidden" name="coin_quantity" value="{{ $quantity }}">
              <li>@lang('Phase'): <span class="fw-bold">{{ __($coinExists->stage) }} @lang('Stage')</span></li>
              <li>@lang('Price'): <span class="fw-bold">{{ getAmount($coinExists->price) }} {{ __($general->cur_text) }}
                (@lang('1') {{ __($general->coin_name) }} @lang('=') {{ getAmount($coinExists->price) }} {{ __($general->cur_text) }})
             </span></li>
              <li>@lang('Coin Quantity'): <span class="fw-bold">{{ __($quantity) }}</span></li>
              <li>@lang('Total Amount'): <span class="fw-bold">{{ getAmount($requiredPrice) }}</span></li>
              <li>@lang('You Post Balance Will be'): <span class="fw-bold">{{ Auth::user()->balance - $requiredPrice }}</span></li>
              <input type="submit" value="Confirm" class="btn btn--base w-100">
            </form>
            </ul>

          </div>
        </div>
      </div>
    </div>
</section>

@stop



