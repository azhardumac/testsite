@extends($activeTemplate.'layouts.master')

@php
    $userDashboardImage = getContent('user.content', true);
@endphp

@section('content')

    <section class="pt-100 pb-100 overlay--one bg_img" style="background-image: url('{{ getImage( 'assets/images/frontend/user/' .@$userDashboardImage->data_values->background_image, '1920x1080') }} ');">
        <div class="container">
            <div class="row justify-content-center mb-none-30">

                <div class="text-end mb-4">
                    <a href="{{ route('user.withdraw.history') }}" class="custom--card d-inline-flex card-header highlighted-text" style="color: white !important;">
                        @lang('Withdraw History')
                    </a>
                </div>

                @foreach($withdrawMethod as $data)
                    <div class="col-xxl-4 col-lg-4 col-sm-6 mb-30">
                        <div class="deposit-card rounded-3">
                            <h5 class="card-header text-center">{{__($data->name)}}</h5>
                            <div class="deposit-card__body text-center">
                            <img src="{{getImage(imagePath()['withdraw']['method']['path'].'/'. $data->image,imagePath()['withdraw']['method']['size'])}}" class="card-img-top" alt="{{__($data->name)}}" class="w-100">

                            <ul class="cmn-list text-center mt-4">
                                <li>@lang('Limit')
                                    <b>
                                    : {{getAmount($data->min_limit)}}
                                    - {{getAmount($data->max_limit)}} {{__($general->cur_text)}}
                                    </b>
                                </li>

                                <li> @lang('Charge')
                                    <b>
                                    - {{getAmount($data->fixed_charge)}} {{__($general->cur_text)}}
                                    + {{getAmount($data->percent_charge)}}%
                                    </b>
                                </li>
                                <li>@lang('Processing Time') -
                                    <b>
                                    {{$data->delay}}
                                    </b>
                                </li>
                            </ul>

                            <a href="#0" data-id="{{$data->id}}"
                               data-resource="{{$data}}"
                               data-min_amount="{{getAmount($data->min_limit)}}"
                               data-max_amount="{{getAmount($data->max_limit)}}"
                               data-fix_charge="{{getAmount($data->fixed_charge)}}"
                               data-percent_charge="{{getAmount($data->percent_charge)}}"
                               data-base_symbol="{{__($general->cur_text)}}"
                               class="btn btn--base deposit mt-3" data-bs-toggle="modal" data-bs-target="#staticBackdrop"
                            >
                                @lang('Withdraw Now')
                            </a>

                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </section>


    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title method-name text-dark" id="exampleModalLabel">@lang('Withdraw')</h5>
                    <button type="button" class="btn-close bg-danger" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{route('user.withdraw.money')}}" method="post">
                    @csrf
                    <div class="modal-body">
                        <p class="depositLimit"></p>
                        <p class="depositCharge"></p>

                        <div class="form-group">
                            <input type="hidden" name="currency"  class="edit-currency form-control" value="">
                            <input type="hidden" name="method_code" class="edit-method-code  form-control" value="">
                        </div>

                        <div class="form-group">
                            <label>@lang('Enter Amount'):</label>
                            <div class="input-group">
                                <input id="amount" type="text" class="form-control form-control-lg" onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')" name="amount" placeholder="0.00" required=""  value="{{old('amount')}}">
                                <span class="input-group-text addon-bg currency-addon custom--bg">{{__($general->cur_text)}}</span>
                            </div>
                        </div>

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

            $('.deposit').on('click', function () {
                var id = $(this).data('id');
                var result = $(this).data('resource');
                var minAmount = $(this).data('min_amount');
                var maxAmount = $(this).data('max_amount');
                var fixCharge = $(this).data('fix_charge');
                var percentCharge = $(this).data('percent_charge');

                var depositLimit = `@lang('Withdraw Limit'): ${minAmount} - ${maxAmount}  {{__($general->cur_text)}}`;
                $('.depositLimit').text(depositLimit);
                var depositCharge = `@lang('Charge'): ${fixCharge} {{__($general->cur_text)}} ${(0 < percentCharge) ? ' + ' + percentCharge + ' %' : ''}`
                $('.depositCharge').text(depositCharge);
                $('.method-name').text(`@lang('Withdraw Via') ${result.name}`);
                $('.edit-currency').val(result.currency);
                $('.edit-method-code').val(result.id);
            });

        })(jQuery);
    </script>

@endpush

