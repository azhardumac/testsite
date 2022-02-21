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
            <h3 class="title mb-3">@lang('Enter info to create Auction')</h3>

            <ul class="deposit-preview-list">
                <form action="" id="form">

                    <div class="row">
                        <div class="col-lg-12 form-group text-start">
                            <label for="coin_quantity">@lang('Coin Quantity')*</label>
                           <div class="input-group">
                            <input type="number" required placeholder="@lang('Quantity')" class="form-control" id="coin_quantity" name="coin_quantity">
                            <span class="input-group-text text-dark">{{ __($general->coin_name) }}</span>
                           </div>
                        </div>

                        <div class="col-lg-12 form-group text-start">
                            <label for="parcent">@lang('Profit Parcent')*</label>
                            <div class="input-group">
                                <input type="text" onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')" required placeholder="@lang('Expected Profit')" class="form-control" id="parcent" name="parcent">
                                <span class="input-group-text text-dark">@lang('%')</span>
                            </div>
                        </div>

                        <span id="calculation_result"></span>

                        <div class="col-lg-12">
                            <input type="submit" value="@lang('Submit')" class="btn btn--base w-100 mt-4">
                        </div>

                    </div>

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
            <form action="{{ route('user.auction.create.confirm') }}" method="post">
                @csrf
                <input type="hidden" step="any" required class="coin_quantity" name="coin_quantity">
                <input type="hidden" step="any" required class="parcent" name="parcent">

                <div class="modal-body">
                    <input type="hidden" class="form-control" required="" name="coin_quantity" step="any">
                    <p>@lang('Are you sure to create new auction?')</p>
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

        'use strict'

        $('#parcent').attr('readonly', true);

        $('#coin_quantity').on('input', function(){
            let input = $('#coin_quantity').val();

            if(input){
                $('#parcent').removeAttr('readonly');
            }else{
                $('#parcent').attr('readonly', true);
            }

        });

        $('#parcent').on('input', function () {

            let parcentOfprofit = parseFloat($(this).val());
            const currentCoinPrice = parseFloat('{{ getAmount($phase->price) }}');
            let getAmountOfParcent = (parcentOfprofit * currentCoinPrice) / 100;
            let finalPrice = currentCoinPrice + getAmountOfParcent;
            let coinQuantity = parseFloat($('#coin_quantity').val());
            let amount = coinQuantity * finalPrice;

            if(isNaN(amount)){
                amount = 0;
            }

            $('#calculation_result').text(` @lang("Total Amount"): ${amount.toFixed(2)} {{ $general->cur_text }} `);
        });


        $('#coin_quantity').on('input', function () {

        let parcentOfprofit = parseFloat($('#parcent').val());
        const currentCoinPrice = parseFloat('{{ getAmount($phase->price) }}');
        let getAmountOfParcent = (parcentOfprofit * currentCoinPrice) / 100;
        let finalPrice = currentCoinPrice + getAmountOfParcent;
        let coinQuantity = parseFloat($(this).val());
        let amount = coinQuantity * finalPrice;

        if(isNaN(amount)){
            amount = 0;
        }

        $('#calculation_result').text(` @lang("Total Amount"): ${amount.toFixed(2)} {{ $general->cur_text }} `);
        });

        $('#form').on('submit', function(e){
            e.preventDefault();

            var modal = $('#confirmModal');
            var coin = $('#coin_quantity').val();
            var parcent = $('#parcent').val();
            modal.find('input[name=coin_quantity]').val(coin);
            modal.find('input[name=parcent]').val(parcent);
            modal.modal('show');
        });

    })(jQuery);

</script>
@endpush
