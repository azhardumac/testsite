@extends($activeTemplate.'layouts.master')

@php
    $userDashboardImage = getContent('user.content', true);
@endphp

@section('content')

<section class="pt-100 pb-100 overlay--one bg_img" style="background-image: url('{{ getImage( 'assets/images/frontend/user/' .@$userDashboardImage->data_values->background_image, '1920x1080') }}');">
    <div class="container">

        <div class="text-end mb-4">
            <a href="{{ route('user.withdraw') }}" class="custom--card d-inline-flex card-header highlighted-text" style="color: white !important;">
                @lang('Withdraw Now')
            </a>
        </div>

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
                    <th scope="col">@lang('More')</th>
                </tr>
              </thead>
              <tbody>
                @forelse($withdraws as $k=>$data)
                    <tr>
                        <td data-label="#@lang('Trx')">{{$data->trx}}</td>
                        <td data-label="@lang('Gateway')">{{ __($data->method->name) }}</td>
                        <td data-label="@lang('Amount')">
                            <strong>{{getAmount($data->amount)}} {{__($general->cur_text)}}</strong>
                        </td>

                        <td data-label="@lang('Status')">
                            @if($data->status == 2)
                                <span class="badge badge--warning rounded-pill">@lang('Pending')</span>
                            @elseif($data->status == 1)
                                <span class="badge badge--success rounded-pill">@lang('Completed')</span>
                                <button class="btn-info btn-rounded  badge detailBtn" data-admin_feedback="{{$data->admin_feedback}}"><i class="fa fa-info"></i></button>
                            @elseif($data->status == 3)
                                <span class="badge badge--danger rounded-pill">@lang('Rejected')</span>
                                <button class="btn-info btn-rounded badge detailBtn" data-admin_feedback="{{$data->admin_feedback}}"><i class="fa fa-info"></i></button>
                            @endif

                        </td>
                        <td data-label="@lang('Date')">
                            {{showDateTime($data->created_at, 'Y-m-d')}}
                        </td>

                        <td data-label="@lang('Details')">
                            <a href="#0" class="btn btn-sm details custom--bg"
                               style="background-color: #37ebec;"
                               data-charge="{{getAmount($data->charge)}}"
                               data-amount="{{getAmount($data->amount)}}"
                               data-after_charge="{{ getAmount($data->after_charge) }}"
                               data-rate="{{ getAmount($data->rate) }} {{__($general->cur_text)}}"
                               data-get="{{ getAmount($data->final_amount) }} {{__($data->currency)}}"
                            >
                                <i class="fa fa-desktop"></i>
                            </a>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td class="text-white text-center" colspan="100%">{{ __($empty_message) }}</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
            {{$withdraws->links()}}
          </div>
        </div>
      </div><!-- row end -->
    </div>
  </section>


  {{-- FEEDBACK MODAL --}}
<div id="feedBackDetails" class="modal fade" tabindex="-1" role="dialog">
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


    {{-- Detail MODAL --}}
    <div id="detailModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Details')</h5>
                    <button type="button" class="btn-close bg-danger text-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="list-group">
                        <li class="">@lang('Amount') : <span class="withdraw-amount"></span></li>
                        <li class="">@lang('Charge') : <span class="withdraw-charge"></span></li>
                        <li class="">@lang('After Charge') : <span class="withdraw-after_charge"></span></li>
                        <li class="">@lang('Conversion Rate') : <span class="withdraw-rate"></span></li>
                        <li class="">@lang('Payable Amount') : <span class="withdraw-payable"></span></li>
                    </ul>
                    <ul class="list-group withdraw-detail mt-1">
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn text-white bg-danger" data-bs-dismiss="modal">@lang('Close')</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        (function($){
            "use strict";

            $('.detailBtn').on('click', function() {
                var modal = $('#feedBackDetails');
                var feedback = $(this).data('admin_feedback');
                modal.find('.withdraw-detail').html(`<p> ${feedback} </p>`);
                modal.modal('show');
            });

            $('.details').on('click', function() {
                var modal = $('#detailModal');
                modal.find('.withdraw-amount').text($(this).data('amount'));
                modal.find('.withdraw-charge').text($(this).data('charge'));
                modal.find('.withdraw-after_charge').text($(this).data('after_charge'));
                modal.find('.withdraw-rate').text($(this).data('rate'));
                modal.find('.withdraw-payable').text($(this).data('get'));
                modal.modal('show');
            });

        })(jQuery);
    </script>
@endpush
