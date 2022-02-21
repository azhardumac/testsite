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
                <h3>@lang('Please Pay') {{getAmount($deposit->final_amo)}} {{__($deposit->method_currency)}}</h3>
                <h3 class="my-3">@lang('To Get') {{getAmount($deposit->amount)}}  {{__($general->cur_text)}}</h3>
                <button type="button" class="btn btn-success mt-4 btn btn--base " id="btn-confirm" onClick="payWithRave()">@lang('Pay Now')</button>
                <script src="https://api.ravepay.co/flwv3-pug/getpaidx/api/flwpbf-inline.js"></script>
                <script>
                    var btn = document.querySelector("#btn-confirm");
                    btn.setAttribute("type", "button");
                    const API_publicKey = "{{$data->API_publicKey}}";

                function payWithRave() {
                    var x = getpaidSetup({
                    PBFPubKey: API_publicKey,
                    customer_email: "{{$data->customer_email}}",
                    amount: "{{$data->amount }}",
                    customer_phone: "{{$data->customer_phone}}",
                    currency: "{{$data->currency}}",
                    txref: "{{$data->txref}}",
                    onclose: function () {
                    },
                    callback: function (response) {
                        var txref = response.tx.txRef;
                        var status = response.tx.status;
                        var chargeResponse = response.tx.chargeResponseCode;
                        if (chargeResponse == "00" || chargeResponse == "0") {
                            window.location = '{{ url('ipn/flutterwave') }}/' + txref + '/' + status;
                        } else {
                            window.location = '{{ url('ipn/flutterwave') }}/' + txref + '/' + status;
                        }
                            // x.close(); // use this to close the modal immediately after payment.
                        }
                    });
                }
                </script>
            </ul>
          </div>
        </div>
      </div>
    </div>
</section>

@endsection
