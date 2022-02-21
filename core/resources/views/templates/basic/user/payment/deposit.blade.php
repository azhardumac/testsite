@extends($activeTemplate.'layouts.master')

@php
    $userDashboardImage = getContent('user.content', true);
@endphp

@section('content')

<section class="pt-100 pb-100 overlay--one bg_img" style="background-image: url(' {{ getImage( 'assets/images/frontend/user/' .@$userDashboardImage->data_values->background_image, '1920x1080') }} ');">
    <div class="container">

        <div class="text-end mb-4">
            <a href="{{ route('user.deposit.history') }}" class="custom--card d-inline-flex card-header highlighted-text" style="color: white !important;">
                @lang('Deposit History')
            </a>
        </div>

        <div class="row justify-content-center mb-none-30">

            @foreach($gatewayCurrency as $data)
                <div class="col-xxl-3 col-lg-4 col-sm-6 mb-30">
                    <div class="deposit-card rounded-3">
                        <h4 class="deposit-card__title mb-4">{{__($data->name)}}</h4>
                        <div class="deposit-card__body">
                        <img src="{{$data->methodImage()}}" class="card-img-top" alt="{{__($data->name)}}" class="w-100">

                        <a href="#0" class="btn btn-outline--base btn-shadow--base py-3 rounded-2 w-100 text-center mt-4 deposit"
                            data-id="{{$data->id}}" data-resource="{{$data}}"
                            data-min_amount="{{getAmount($data->min_amount)}}"
                            data-max_amount="{{getAmount($data->max_amount)}}"
                            data-base_symbol="{{$data->baseSymbol()}}"
                            data-fix_charge="{{getAmount($data->fixed_charge)}}"
                            data-percent_charge="{{getAmount($data->percent_charge)}}" data-bs-toggle="modal" data-bs-target="#staticBackdrop"
                        >
                            @lang('Deposit Now')
                        </a>

                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
</section>



<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title method-name text-dark" id="exampleModalLabel"></h5>
                    <button type="button" class="btn-close bg-danger" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{route('user.deposit.insert')}}" method="post">
                    @csrf
                    <div class="modal-body">
                        <p class="depositLimit"></p>
                        <p class="depositCharge"></p>
                        <div class="form-group">
                            <input type="hidden" name="currency" class="edit-currency" value="">
                            <input type="hidden" name="method_code" class="edit-method-code" value="">
                        </div>
                        <div class="form-group">
                            <label>@lang('Enter Amount'):</label>
                            <div class="input-group">
                                <input id="amount" type="text" class="form-control form-control-lg" onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')" name="amount" placeholder="0.00" required="" value="{{old('amount')}}">
                                <span class="input-group-text currency-addon addon-bg custom--bg">{{__($general->cur_text)}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn bg-danger text-white" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn custom--bg text-white">@lang('Confirm')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop



@push('script')
    <script>
        (function($){

            "use strict";

            $('.deposit').on('click', function () {
                var id = $(this).data('id');
                var result = $(this).data('resource');
                var minAmount = $(this).data('min_amount');
                var maxAmount = $(this).data('max_amount');
                var baseSymbol = "{{__($general->cur_text)}}";
                var fixCharge = $(this).data('fix_charge');
                var percentCharge = $(this).data('percent_charge');

                var depositLimit = `@lang('Deposit Limit'): ${minAmount} - ${maxAmount}  ${baseSymbol}`;
                $('.depositLimit').text(depositLimit);
                var depositCharge = `@lang('Charge'): ${fixCharge} ${baseSymbol}  ${(0 < percentCharge) ? ' + ' +percentCharge + ' % ' : ''}`;
                $('.depositCharge').text(depositCharge);
                $('.method-name').text(`@lang('Payment By ') ${result.name}`);
                $('.currency-addon').text(baseSymbol);

                $('.edit-currency').val(result.currency);
                $('.edit-method-code').val(result.method_code);
            });

        })(jQuery);
    </script>
@endpush
