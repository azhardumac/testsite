@extends($activeTemplate.'layouts.master')

@php
    $userDashboardImage = getContent('user.content', true);
@endphp

@section('content')

<section class="pt-100 pb-100 overlay--one bg_img" style="background-image: url('{{ getImage( 'assets/images/frontend/user/' .@$userDashboardImage->data_values->background_image, '1920x1080') }}');">
    <div class="container">

    <div class="text-end mb-4">
        <div class="custom--card d-inline-flex card-header highlighted-text" style="color: white !important;">
            @lang('Current Coin Price') {{ getAmount(@$currentRate->price) }} {{ __($general->cur_text) }}
        </div>
    </div>


      <div class="row mb-none-30 justify-content-center">
        <div class="col-lg-12 mb-30">
            <div class="form-group">
                <label>@lang('Referral Link')</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="referralURL" value="{{ route('home') }}?reference={{ auth()->user()->username }}" readonly>
                    <div class="input-group-append">
                        <span class="input-group-text copytext copyBoard" id="copyBoard"> <i class="fa fa-copy"></i> </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-4 col-lg-4 col-md-6 mb-30">
          <a href="{{ route('user.transaction.log') }}" class="w-100">
            <div class="d-widget rounded-3">
                <div class="d-widget__icon">
                  <i class="las la-folder-open"></i>
                </div>
                <div class="d-widget__content">
                  <h3 class="d-widget__amount">{{ __($transaction) }}</h3>
                  <p class="captoin text-white">@lang('Total Transaction')</p>
                </div>
              </div><!-- d-widget end -->
          </a>
        </div>
        <div class="col-xxl-4 col-lg-4 col-md-6 mb-30">
            <a href="{{ route('user.coin.buy.log') }}" class="w-100">
                <div class="d-widget rounded-3">
                    <div class="d-widget__icon">
                      <i class="las la-coins"></i>
                    </div>
                    <div class="d-widget__content">
                      <h3 class="d-widget__amount">{{ __($coinHistory) }}</h3>
                      <p class="captoin text-white">@lang('Coin History')</p>
                    </div>
                </div><!-- d-widget end -->
            </a>
        </div>
        <div class="col-xxl-4 col-lg-4 col-md-6 mb-30">
         <a href="{{ route('user.withdraw.history') }}" class="w-100">
            <div class="d-widget rounded-3">
                <div class="d-widget__icon">
                  <i class="las la-hand-holding-usd"></i>
                </div>
                <div class="d-widget__content">
                  <h3 class="d-widget__amount">{{ __($withdraw) }}</h3>
                  <p class="captoin text-white">@lang('Total Withdraw')</p>
                </div>
            </div><!-- d-widget end -->
         </a>
        </div>
        <div class="col-xxl-4 col-lg-4 col-md-6 mb-30">
         <a href="{{ route('user.deposit.history') }}" class="w-100">
            <div class="d-widget rounded-3">
                <div class="d-widget__icon">
                  <i class="las la-money-bill-wave-alt"></i>
                </div>
                <div class="d-widget__content">
                  <h3 class="d-widget__amount">{{ __($deposit) }}</h3>
                  <p class="captoin text-white">@lang('Total Deposit')</p>
                </div>
            </div><!-- d-widget end -->
         </a>
        </div>

        <div class="col-xxl-4 col-lg-4 col-md-6 mb-30">
            <div class="d-widget rounded-3">
                <div class="d-widget__icon">
                    <i class="las la-money-bill-wave-alt"></i>
                </div>
                <div class="d-widget__content">
                    <h3 class="d-widget__amount">{{ getAmount($user->balance) }} {{ __($general->cur_text) }}</h3>
                    <p class="captoin text-white">@lang('Balance')</p>
                </div>
            </div><!-- d-widget end -->
        </div>

        <div class="col-xxl-4 col-lg-4 col-md-6 mb-30">
            <div class="d-widget rounded-3">
                <div class="d-widget__icon">
                    <i class="las la-coins"></i>
                </div>
                <div class="d-widget__content">
                    <h3 class="d-widget__amount">{{ getAmount($user->coin_balance) }} {{ __($general->coin_symbol) }}</h3>
                    <p class="captoin text-white">@lang('Coin Balance')</p>
                </div>
            </div><!-- d-widget end -->
        </div>

      </div><!-- row end -->
      <div class="row mt-5">
          <div class="col-lg-12 text-center mb-4">
              <div class="custom--card d-inline-flex card-header">
                <h3>@lang('Recent Transaction')</h3>
              </div>
          </div>
        <div class="col-lg-12">
          <div class="table-responsive table-responsive--sm">
            <table class="table custom--table">

            <thead>
                <tr>
                    <th scope="col">@lang('Date')</th>
                    <th scope="col">@lang('Transaction')</th>
                    <th scope="col">@lang('Amount')</th>
                    <th scope="col">@lang('Charge')</th>
                    <th scope="col">@lang('Balance')</th>
                    <th scope="col">@lang('Details')</th>
                </tr>
                </thead>
                <tbody>
                    @forelse($logs as $index => $log)
                        <tr>
                            <td data-label="@lang('Date')">
                                {{ showDateTime($log->created_at, 'Y-m-d') }}
                            </td>
                            <td data-label="#@lang('Transaction')">
                                {{ $log->trx }}
                            </td>
                            <td data-label="@lang('Amount')">
                                {{ __($log->trx_type) }}
                                {{ getAmount(__($log->amount)) }} {{ __($general->cur_text) }}
                            </td>
                            <td data-label="@lang('Charge')">
                                {{ getAmount(__($log->charge)) }} {{ __($general->cur_text) }}
                            </td>
                            <td data-label="@lang('Balance')">
                                {{ getAmount(__($log->post_balance)) }} {{ __($general->cur_text) }}
                            </td>

                            <td data-label="@lang('Details')">
                                {{ __($log->details) }}
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="100%" class="text-center"> @lang('No results found')!</td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
          </div>
        </div>
      </div><!-- row end -->
    </div>
  </section>

@endsection
@push('style')
<style>
  .copyBoard{
    height: 100%;
    border-radius: 0px;
    cursor: pointer;
  }
</style>
@endpush
@push('script')
    <script>
      $('.copyBoard').click(function(){
        "use strict";
            var copyText = document.getElementById("referralURL");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            /*For mobile devices*/
            document.execCommand("copy");
            iziToast.success({message: "Copied: " + copyText.value, position: "topRight"});
      });
    </script>
@endpush