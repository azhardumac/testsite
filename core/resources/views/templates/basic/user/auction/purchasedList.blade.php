@extends($activeTemplate.'layouts.master')

@php
    $userDashboardImage = getContent('user.content', true);
@endphp

@section('content')

<section class="pt-100 pb-100 overlay--one bg_img" style="background-image: url('{{ getImage( 'assets/images/frontend/user/' .@$userDashboardImage->data_values->background_image, '1920x1080') }}');">
    <div class="container">

        <div class="text-end mb-4">
            <a href="{{ route('user.auction.list') }}" class="custom--card d-inline-flex card-header highlighted-text" style="color: white !important;">
                @lang('Auction List')
            </a>
        </div>

      <div class="row mt-5">
        <div class="col-lg-12">
          <div class="table-responsive table-responsive--sm">
            <table class="table custom--table">
              <thead>
                <tr>
                    <th scope="col">@lang('Seller')</th>
                    <th scope="col">@lang('Quantity')</th>
                    <th scope="col">@lang('Parcent')</th>
                    <th scope="col">@lang('Amount')</th>
                    <th scope="col">@lang('Purchased Date')</th>
                </tr>
              </thead>
              <tbody>
                    @forelse($auctions as $index => $auction)
                        <tr>
                            <td data-label="@lang('Seller')">{{ __($auction->user->fullname) }} </td>
                            <td data-label="@lang('Quantity')">{{ getAmount($auction->quantity) }} {{ __($general->coin_name) }}</td>
                            <td data-label="@lang('Parcent of Profit')">{{ getAmount($auction->expected_profit) }}% </td>
                            <td data-label="@lang('Amount')">{{ getAmount($auction->amount) }} {{ __($general->cur_text) }}</td>
                            <td data-label="@lang('Purchased Date')">{{ showDateTime($auction->auction_completed) }} </td>
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


@endsection

@push('script')
<script>

  (function($){

      "use strict";

      $('.confirm').on('click', function() {
          var modal = $('#exampleModal');
          modal.find('input[name=id]').val($(this).data('id'));
          modal.modal('show');
      });

  })(jQuery);

</script>
@endpush
