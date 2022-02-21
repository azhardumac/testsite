@extends($activeTemplate.'layouts.master')

@php
    $userDashboardImage = getContent('user.content', true);
@endphp

@section('content')

<section class="pt-100 pb-100 overlay--one bg_img" style="background-image: url('{{ getImage( 'assets/images/frontend/user/' .$userDashboardImage->data_values->background_image, '1920x1080') }}');">
    <div class="container">
      <div class="row justify-content-center gy-4">

        <div class="text-end mb-4">
            <a href="{{ route('user.auction.buy.history') }}" class="custom--card d-inline-flex card-header highlighted-text" style="color: white !important;">
                @lang('Purchased Auction')
            </a>
        </div>

        @forelse($auctions as $index => $auction)
        <div class="col-lg-3 ">
          <div class="deposit-preview-card rounded-3">
            <span class="title mb-3">{{ getAmount($auction->quantity) }} </span> <span>{{ __($general->coin_symbol) }}</span>
            <ul class="deposit-preview-list">

                <div class="row">

                    <div class="col-lg-12 form-group">

                        <div class="d-block">
                            <label for="coin_quantity">@lang('Seller'):</label>
                            {{ __($auction->user->fullname) }}
                        </div>

                        <div class="d-block">
                            <label for="coin_quantity">@lang('Price'):</label>
                            @php
                                $getAmountOfParcent = ($auction->expected_profit * $phase->price) / 100;
                                $finalPrice = $phase->price + $getAmountOfParcent;
                                $amount = $auction->quantity * $finalPrice;
                            @endphp
                            {{ getAmount($amount) }} {{ __($general->cur_text) }}
                        </div>

                    </div>

                    <div class="col-lg-12">
                        <button class="btn btn--base w-100 mt-4 confirm" data-id="{{ $auction->id }}">@lang('Buy Now')</button>
                    </div>

                </div>
            </ul>
          </div>
        </div>
        @empty
            <div class="cd-inline-flex w-auto"><h3>@lang('Auction Not Found')</h3></div>
        @endforelse

      </div>
      <div class="mt-4">
        {{$auctions->links()}}
    </div>
    </div>

</section>


<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-dark">@lang('Confirmation')!</h5>
                <button type="button" class="btn-close bg-danger text-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('user.auction.buy') }}" method="POST">
                @csrf
                <input type="hidden" name="id">
                <div class="modal-body">
                    <p class="text-white">@lang('Are you sure to buy this Aution?')</p>
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

        $('.confirm').on('click', function() {
            var modal = $('#exampleModal');
            modal.find('input[name=id]').val($(this).data('id'));
            modal.modal('show');
        });

    })(jQuery);

</script>
@endpush
