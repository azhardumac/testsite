@extends($activeTemplate.'layouts.master')

@php
    $userDashboardImage = getContent('user.content', true);
@endphp

@section('content')

<section class="pt-100 pb-100 overlay--one bg_img" style="background-image: url('{{ getImage( 'assets/images/frontend/user/' .@$userDashboardImage->data_values->background_image, '1920x1080') }}');">
    <div class="container">

    <div class="text-end mb-4">
        <a href="{{ route('user.deposit') }}" class="custom--card d-inline-flex card-header highlighted-text" style="color: white !important;">
            @lang('Deposit Now')
        </a>
    </div>

      <div class="row mb-none-30">

      </div><!-- row end -->
      <div class="row mt-5">
        <div class="col-lg-12">
          <div class="table-responsive table-responsive--sm">
            <table class="table custom--table">
              <thead>
                <tr>
                    <th scope="col">@lang('Transaction ID')</th>
                    <th scope="col">@lang('Gateway')</th>
                    <th scope="col">@lang('Amount')</th>
                    <th scope="col">@lang('Status')</th>
                    <th scope="col">@lang('Date')</th>
                    <th scope="col"> @lang('MORE')</th>
                </tr>
              </thead>
              <tbody>
                @if(count($logs) >0)
                    @foreach($logs as $k=>$data)
                        <tr>
                            <td data-label="@lang('Trx')">{{$data->trx}}</td>
                            <td data-label="@lang('Gateway')">{{ __(@$data->gateway->name)  }}</td>
                            <td data-label="@lang('Amount')">
                                <strong>{{getAmount($data->amount)}} {{__($general->cur_text)}}</strong>
                            </td>
                            <td data-label="@lang('Status')">
                                @if($data->status == 1)
                                    <span class="badge badge--success rounded-pill">@lang('Completed')</span>
                                @elseif($data->status == 2)
                                    <span class="badge badge--warning rounded-pill">@lang('Pending')</span>
                                @elseif($data->status == 3)
                                    <span class="badge badge--danger rounded-pill">@lang('Canceled')</span>
                                @endif

                                @if($data->admin_feedback != null)
                                    <button class="btn-info btn-rounded  badge detailBtn" data-admin_feedback="{{$data->admin_feedback}}"><i class="fa fa-info"></i></button>
                                @endif

                            </td>
                            <td data-label="@lang('Date')">
                                {{showDateTime($data->created_at, 'Y-m-d')}}
                            </td>

                            @php
                                $details = ($data->detail != null) ? json_encode($data->detail) : null;
                            @endphp

                            <td data-label="@lang('Details')">
                                <a href="#0" class="btn custom--bg btn-sm approveBtn"
                                   style="background-color: #37ebec;"
                                   data-info="{{$details}}"
                                   data-id="{{ $data->id }}"
                                   data-amount="{{ getAmount($data->amount)}} {{ __($general->cur_text) }}"
                                   data-charge="{{ getAmount($data->charge)}} {{ __($general->cur_text) }}"
                                   data-after_charge="{{ getAmount($data->amount + $data->charge)}} {{ __($general->cur_text) }}"
                                   data-rate="{{ getAmount($data->rate)}} {{ __($data->method_currency) }}"
                                   data-payable="{{ getAmount($data->final_amo)}} {{ __($data->method_currency) }}">
                                    <i class="fa fa-desktop"></i>
                                </a>
                            </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="100%" class="text-center"> @lang('No results found')!</td>
                        </tr>
                    @endif
                </tbody>
            </table>
            {{$logs->links()}}
          </div>
        </div>
      </div><!-- row end -->
    </div>
  </section>


  {{-- APPROVE MODAL --}}
<div id="approveModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Details')</h5>
                <button type="button" class="btn-close bg-danger" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                    <div class="">@lang('Amount') : <span class="withdraw-amount"></span></div>
                    <div class="">@lang('Charge') : <span class="withdraw-charge"></span></div>
                    <div class="">@lang('After Charge') : <span class="withdraw-after_charge"></span></div>
                    <div class="">@lang('Conversion Rate') : <span class="withdraw-rate"></span></div>
                    <div class="">@lang('Payable Amount') : <span class="withdraw-payable"></span></div>
                <ul class="list-group withdraw-detail mt-1">
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-danger text-white" data-bs-dismiss="modal">@lang('Close')</button>
            </div>
        </div>
    </div>
</div>

{{-- Detail MODAL --}}
<div id="detailModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Admin Feedback')</h5>
                <button type="button" class="btn-close bg-danger text-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="withdraw-detail"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-danger text-white" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection


@push('script')
    <script>
        "use strict";
        $('.approveBtn').on('click', function() {

            var modal = $('#approveModal');
            modal.find('.withdraw-amount').text($(this).data('amount'));
            modal.find('.withdraw-charge').text($(this).data('charge'));
            modal.find('.withdraw-after_charge').text($(this).data('after_charge'));
            modal.find('.withdraw-rate').text($(this).data('rate'));
            modal.find('.withdraw-payable').text($(this).data('payable'));
            var list = [];
            var details =  Object.entries($(this).data('info'));

            var ImgPath = "{{asset(imagePath()['verify']['deposit']['path'])}}/";
            var singleInfo = '';
            for (var i = 0; i < details.length; i++) {
                if (details[i][1].type == 'file') {
                    singleInfo += `<li class="list-group-item">
                                        <span class="font-weight-bold "> ${details[i][0].replaceAll('_', " ")} </span> : <img src="${ImgPath}/${details[i][1].field_name}" alt="@lang('Image')" class="w-100">
                                    </li>`;
                }else{
                    singleInfo += `<li class="list-group-item">
                                        <span class="font-weight-bold "> ${details[i][0].replaceAll('_', " ")} </span> : <span class="font-weight-bold ml-3">${details[i][1].field_name}</span>
                                    </li>`;
                }
            }

            if (singleInfo)
            {
                modal.find('.withdraw-detail').html(`<br><strong class="my-3">@lang('Payment Information')</strong>  ${singleInfo}`);
            }else{
                modal.find('.withdraw-detail').html(`${singleInfo}`);
            }
            modal.modal('show');
        });

        $('.detailBtn').on('click', function() {
            var modal = $('#detailModal');
            var feedback = $(this).data('admin_feedback');
            modal.find('.withdraw-detail').html(`<p> ${feedback} </p>`);
            modal.modal('show');
        });
    </script>
@endpush


@push('style')
<style>
    .list-group-item{
        background-color: transparent !important;
    }
</style>
@endpush
