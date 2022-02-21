@extends($activeTemplate.'layouts.master')

@php
    $userDashboardImage = getContent('user.content', true);
@endphp

@section('content')

<section class="pt-100 pb-100 overlay--one bg_img" style="background-image: url('{{ getImage( 'assets/images/frontend/user/' .@$userDashboardImage->data_values->background_image, '1920x1080') }}');">
    <div class="container">

        <div class="text-end mb-4">
            <a href="{{ route('user.auction.create') }}" class="custom--card d-inline-flex card-header highlighted-text" style="color: white !important;">
                @lang('Create Auction')
            </a>
        </div>

      <div class="row mt-5">
        <div class="col-lg-12">
          <div class="table-responsive table-responsive--sm">
            <table class="table custom--table">
              <thead>
                <tr>
                    <th scope="col">@lang('Buyer')</th>
                    <th scope="col">@lang('Quantity')</th>
                    <th scope="col">@lang('Expected Profit')</th>
                    <th scope="col">@lang('Amount')</th>
                    <th scope="col">@lang('Status')</th>
                    <th scope="col">@lang('Completed Date')</th>
                    <th scope="col">@lang('Action')</th>
                </tr>
              </thead>
              <tbody>
                    @forelse($auctions as $index => $auction)
                        <tr>
                            <td data-label="@lang('Buyer')">
                                @if($auction->auction_buyer)
                                    {{ __($auction->user->fullname) }}
                                @else
                                    @lang('N/A')
                                @endif
                            </td>
                            <td data-label="@lang('Quantity')">{{ getAmount($auction->quantity) }} {{ __($general->coin_name) }}</td>
                            <td data-label="@lang('Expected Profit')">{{ getAmount($auction->expected_profit) }}% {{ __($general->cur_text) }}
                            </td>
                            <td data-label="@lang('Amount')">{{ getAmount($auction->amount) }} {{ __($general->cur_text) }}</td>
                            <td data-label="@lang('Status')">
                                @if($auction->status == 1)
                                    <span class="badge badge--info rounded-pill">@lang('Running')</span>
                                @elseif($auction->status == 2)
                                    <span class="badge badge--danger rounded-pill">@lang('Backed')</span>
                                @elseif($auction->status == 3)
                                    <span class="badge badge--success rounded-pill">@lang('Completed')</span>
                                @endif
                            </td>
                            <td data-label="@lang('Completed Date')">
                                {{ $auction->auction_completed != null ? showDateTime($auction->auction_completed, 'Y-m-d') : __('N/A') }}
                            </td>
                            <td data-label="@lang('Action')">
                                @if($auction->status == 1)
                                    <a href="#0" class="btn custom--bg btn-sm details confirm"
                                    style="background-color: #37ebec;" data-bs-toggle="modal" data-bs-target="#exampleModal" data-id='{{ $auction->id }}'>
                                     <i class="fa fa-arrow-left"></i>
                                    </a>
                                @elseif($auction->status == 2)
                                    @lang('Backed')
                                @elseif($auction->status == 3)
                                    @lang('Completed')
                                @endif
                            </td>
                    @empty
                        <tr>
                            <td colspan="100%" class="text-center"> @lang('No results found')!</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{$auctions->links()}}
          </div>
        </div>
      </div><!-- row end -->
    </div>
  </section>


  <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Confirmation')!</h5>
                <button type="button" class="btn-close bg-danger text-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('user.auction.back') }}" method="POST">
                @csrf
                <input type="hidden" name="id">
                <div class="modal-body">
                    <p>@lang('Are you sure to back/withdraw this auction')?</p>
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
        "use strict";

        (function($){

            $('.confirm').on('click', function() {
                var modal = $('#confirmModal');
                modal.find('input[name=id]').val($(this).data('id'));
                modal.modal('show');
            });

        })(jQuery);

    </script>
@endpush
