@php
    $info = getContent('headerInfo.content', true);
@endphp

<header class="header">
    <div class="header__top">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-6 col-md-8">
            <div class="top-info d-flex flex-wrap align-items-center justify-content-md-start justify-content-center text-white font-size--12px">
              <a href="tel:{{ __(@$info->data_values->mobile) }}" class="me-3"><i class="las la-phone-volume font-size--18px"></i> <span class="font-size--14px">{{ __(@$info->data_values->mobile) }}</span></a>
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
              <li><a href="{{ route('home') }}">@lang('Home')</a></li>

            @foreach($pages as $k => $data)
                <li class=""><a href="{{route('pages',[$data->slug])}}"  class="nav-link">{{trans($data->name)}}</a></li>
            @endforeach

            @if($pdfExists)
                <li><a href="{{ asset($pdf) }}" download >@lang('White Paper')</a></li>
            @endif

            <li><a href="{{ route('contact') }}">@lang('Contact')</a></li>

            @if(!Auth::user())
              <li><a href="{{ route('user.register') }}">@lang('Registration')</a></li>
            @endif

            </ul>
            <div class="nav-right">
            @if(Auth::user())
                <a href="{{ route('user.home') }}" class="header-login-btn"><i class="las la-user-circle"></i> @lang('DASHBOARD')</a>
            @else
                <a href="{{ route('user.login') }}" class="header-login-btn"><i class="las la-user-circle"></i> @lang('LOGIN')</a>
            @endif
            </div>
          </div>
        </nav>
      </div>
    </div><!-- header__bottom end -->
  </header>
