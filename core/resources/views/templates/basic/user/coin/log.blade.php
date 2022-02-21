@extends($activeTemplate.'layouts.master')

@php
    $userDashboardImage = getContent('user.content', true);
@endphp

@section('content')

<section class="pt-100 pb-100 overlay--one bg_img" style="background-image: url('{{ getImage( 'assets/images/frontend/user/' .@$userDashboardImage->data_values->background_image, '1920x1080') }}');">

    <div class="container">

        <div class="text-end">
            <a href="{{ route('user.coin.buy') }}" class="custom--card d-inline-flex card-header highlighted-text" style="color: white !important;">
                @lang('Buy Now')
            </a>
        </div>

        <div class="row mt-5">
          <div class="col-lg-12">
            <div class="table-responsive table-responsive--sm">
              <table class="table custom--table">
                <thead>
                  <tr>
                      <th scope="col">@lang('Date')</th>
                      <th scope="col">@lang('Coin')</th>
                      <th scope="col">@lang('Rate')</th>
                      <th scope="col">@lang('Amount')</th>
                      <th scope="col">@lang('Balance')</th>
                      <th scope="col">@lang('Status')</th>
                      <th scope="col">@lang('Details')</th>
                  </tr>
                </thead>
                <tbody>
                    @forelse($logs as $index => $log)
                        <tr>
                            <td data-label="@lang('Date')">
                                {{ showDateTime($log->created_at, 'Y-m-d') }}
                            </td>
                            <td data-label="@lang('Coin')">
                                <span>
                                    {{ __($log->type) }} {{ getAmount(__($log->coin_quantity)) }} {{ __($general->coin_symbol) }}
                                </span>
                            </td>
                            <td data-label="@lang('Rate')">
                                {{ getAmount(__($log->coin_rate)) }} {{ __($general->cur_text) }}
                            </td>
                             <td data-label="@lang('Amount')">
                                {{ getAmount(__($log->amount)) }} {{ __($general->cur_text) }}
                            </td>
                            <td data-label="@lang('Balance')">
                                {{ getAmount(__($log->coin_post_balance)) }} {{ __($general->coin_symbol) }}
                            </td>
                            <td data-label="@lang('Status')">
                                @if($log->status)
                                    <span class="badge badge--success rounded-pill">
                                        @lang('Success')
                                    </span>
                                @else
                                    <span class="badge badge--danger rounded-pill">
                                       @lang('Not Success')
                                    </span>
                                @endif
                            </td>

                            <td data-label="@lang('Details')">
                                {{ __($log->details) }}
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="100%" class="text-center">@lang('No results found')!</td>
                        </tr>
                    @endforelse
                  </tbody>
              </table>
              {{$logs->links()}}
            </div>
          </div>
        </div><!-- row end -->
      </div>

</section>


<div id="detailModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-dark">@lang('Details')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul class="list-group">
                    <li class="list-group-item dark-bg text-dark">@lang('Transaction Id') : <span class="trx_id text-dark"></span></li>
                    <li class="list-group-item dark-bg text-dark">@lang('Details') : <span class="details text-dark"></span></li>
                </ul>
                <ul class="list-group withdraw-detail mt-1">
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
        var modal = $('#detailModal');
        modal.find('.trx_id').text($(this).data('id'));
        modal.find('.details').text($(this).data('details'));
        modal.modal('show');
    });

})(jQuery);
</script>
@endpush
