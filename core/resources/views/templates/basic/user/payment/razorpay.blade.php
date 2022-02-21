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
                <form action="{{$data->url}}" method="{{$data->method}}">
                    <h3 class="text-center">@lang('Please Pay') {{getAmount($deposit->final_amo)}} {{$deposit->method_currency}}</h3>
                    <h3 class="my-3 text-center">@lang('To Get') {{getAmount($deposit->amount)}}  {{__($general->cur_text)}}</h3>
                    <script src="{{$data->checkout_js}}"
                            @foreach($data->val as $key=>$value)
                            data-{{$key}}="{{$value}}"
                        @endforeach >
                    </script>
                    <input type="hidden" custom="{{$data->custom}}" name="hidden">
                </form>
            </ul>
          </div>
        </div>
      </div>
    </div>
</section>

@endsection


@push('script')
    <script>
        "use strict";
        $(document).ready(function () {
            $('input[type="submit"]').addClass("ml-4 mt-4 btn btn--base text-center btn-lg");
        })
    </script>
@endpush
