@php

    use App\IcoPlan;
    use Carbon\Carbon;

    $phase = IcoPlan::whereDate('start_date', '<=', Carbon::now())->whereDate('end_date', '>=', Carbon::now())->first();

    $info = getContent('headerInfo.content', true);
@endphp

<header class="header">
    <div class="header__top">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-6 col-md-8">
            <div class="top-info d-flex flex-wrap align-items-center justify-content-md-start justify-content-center text-white font-size--12px">
              <a hre="tel:{{ __(@$info->data_values->mobile) }}" class="me-3"><i class="las la-phone-volume font-size--18px"></i> <span class="font-size--14px">{{ __(@$info->data_values->mobile) }}</span></a>
              <a href="mailto:{{ __(@$info->data_values->email) }}"><i class="las la-envelope font-size--18px"></i> <span class="font-size--14px">{{ __(@$info->data_values->email) }}</span></a>
            </div>
          </div>
          <div class="col-lg-6 col-md-4 text-md-end text-center mt-md-0 mt-1">
            <select class="select w-auto h-auto px-2 py-1 font-size--14px langSel">
                @foreach($language as $item)
                    <option value="{{$item->code}}" @if(session('lang') == $item->code) selected @endif>{{ __($item->name) }}</option>
                @endforeach
            </select>
          </div>
        </div>
      </div>
    </div>
    <div class="header__bottom">
      <div class="container">
        <nav class="navbar navbar-expand-xl p-0 align-items-center">
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="menu-toggle"></span>
          </button>
          <a class="site-logo site-title" href="{{ route('home') }}"><img src="{{getImage(imagePath()['logoIcon']['path'] .'/logo.png')}}" alt="site-logo"></a>
          <div class="collapse navbar-collapse mt-lg-0 mt-3" id="navbarSupportedContent">
            <ul class="navbar-nav main-menu m-auto">

            <li><a href="{{ route('user.home') }}">@lang('Dashboard')</a></li>

            <li><a href="{{ route('user.deposit') }}">@lang('Deposit')</a></li>

            @if($general->withdraw_permission)
                <li><a href="{{ route('user.withdraw') }}">@lang('Withdraw')</a></li>
            @endif

            <li><a href="{{ route('user.coin.buy.log') }}">@lang('Coin Log')</a></li>
            <li><a href="{{ route('user.referrals') }}">@lang('Referrals')</a></li>

            <li class="menu_has_children"><a href="#0">@lang('Auction')</a>
                <ul class="sub-menu">
                @if($phase)
                    <li><a href="{{ route('user.auction.list') }}">@lang('Auction List')</a></li>
                @endif
                    <li><a href="{{ route('user.auction.my') }}">@lang('My Auctions')</a></li>
                </ul>
            </li>

            <li class="menu_has_children"><a href="#0">@lang('Account')</a>
              <ul class="sub-menu">
                <li><a href="{{ route('user.profile-setting') }}">@lang('Profile')</a></li>
                <li><a href="{{ route('user.twofactor') }}">@lang('2FA Security')</a></li>
                <li><a href="{{ route('user.change-password') }}">@lang('Change Password')</a></li>
                <li><a href="{{ route('user.transaction.log') }}">@lang('Transaction Logs')</a></li>
                <li><a href="{{ route('ticket') }}">@lang('My Tickets')</a></li>
                <li><a href="{{ route('user.logout') }}">@lang('Logout')</a></li>
              </ul>
            </li>

            </ul>
            <div class="nav-right">
                <a href="{{ route('user.logout') }}" class="header-login-btn"><i class="las la-user-circle"></i> @lang('Logout')</a>
            </div>
          </div>
        </nav>
      </div>
    </div><!-- header__bottom end -->
  </header>

