@extends($activeTemplate.'layouts.master')

@php
    $userDashboardImage = getContent('user.content', true);
@endphp

@section('content')

<section class="pt-100 pb-100 overlay--one bg_img" style="background-image: url('{{ getImage( 'assets/images/frontend/user/' .$userDashboardImage->data_values->background_image, '1920x1080') }}');">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-5">
          <div class="deposit-preview-card rounded-3">
            <h3 class="title mb-3">Enter Your Amount To Purchase</h3>

            <ul class="deposit-preview-list">
                <form action="" id="form">
                    @csrf
                    <li>@lang('Phase'): <span class="fw-bold">{{ __($phase->stage) }} @lang('Stage')</span></li>
                    <li>@lang('Price'): <span class="fw-bold">{{ getAmount($phase->price) }} {{ __($general->cur_text) }}
                        (@lang('1') {{ __($general->coin_name) }} @lang('=') {{ getAmount($phase->price) }} {{ __($general->cur_text) }})
                    </span></li>
                    <li>@lang('Availabe'): <span class="fw-bold">{{ getAmount($phase->coin_token) }} {{ __($general->coin_name) }}</span></li>
                    <li class="total_price"> <span class="fw-bold" id="total_price"></span></li>
                    <div class="input-group">
                        <input type="number" class="form-control" id="coin_quantity" required="" name="coin_quantity">
                        <span class="input-group-text text-dark">{{ __($general->coin_name) }}</span>
                    </div>
                    <input type="submit" value="@lang('Buy')" class="btn btn--base w-100 mt-4">
                </form>
            </ul>

          </div>
        </div>
      </div>
    </div>
</section>


 <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Confirmation')!</h5>
                <button type="button" class="btn-close bg-danger text-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('user.coin.buy.confirm') }}" method="post">
                @csrf
                <input type="hidden" name="id">
                <div class="modal-body">
                    <input type="hidden" class="form-control" required="" name="coin_quantity" step="any">
                    <p>@lang('Are you sure to buy') {{ __($general->coin_name) }} ?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-danger text-white" data-bs-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn custom--bg text-white">@lang('Confirm')</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection


@push('script')
<script>

    (function($){

        "use strict";

        $('#total_price').hide();

        const price = parseInt('{{ getAmount($phase->price) }}') ;

        const baseCurrency = '{{ __($general->cur_text) }}';

        $('#coin_quantity').on('input', function () {

            if($(this).val()){
                $('#total_price').show();
                var totalPrice = parseInt($(this).val()) * price;
                $('#total_price').text('@lang("Total Price"): '+totalPrice+' '+baseCurrency);
            }else{
                $('#total_price').hide();
            }

        });

        $('#form').on('submit', function(e){
            e.preventDefault();

            var modal = $('#confirmModal');
            var input = $('#coin_quantity').val();
            modal.find('input[name=coin_quantity]').val(input);
            modal.modal('show');
        });

    })(jQuery);

</script>
@endpush
