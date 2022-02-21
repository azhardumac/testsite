<!-- meta tags and other links -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  @include('partials.seo')
  <!-- bootstrap 4  -->
  <link rel="stylesheet" href="{{asset($activeTemplateTrue.'/css/lib/bootstrap.min.css')}}">
  <!-- fontawesome 5  -->
  <link rel="stylesheet" href="{{asset($activeTemplateTrue.'/css/all.min.css')}}">
  <!-- lineawesome font -->
  <link rel="stylesheet" href="{{asset($activeTemplateTrue.'/css/line-awesome.min.css')}}">

  <link rel="stylesheet" href="{{asset($activeTemplateTrue.'/css/lightcase.css')}}">
  <!-- slick slider css -->
  <link rel="stylesheet" href="{{asset($activeTemplateTrue.'/css/lib/slick.css')}}">
  <!-- main css -->
  <link rel="stylesheet" href="{{asset($activeTemplateTrue.'/css/main.css')}}">

  <link rel="stylesheet" href="{{asset($activeTemplateTrue.'css/color.php?color='.$general->base_color.'&secondColor='.$general->secondary_color)}}">
  <link rel="stylesheet" href="{{asset($activeTemplateTrue.'css/custom.css')}}">

  @stack('style')

</head>
  <body>

    <div class="cursor"></div>
    <div class="cursor-follower"></div>

    <div class="preloader-holder">
        <div class="preloader"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
      </div>

    <!-- scroll-to-top start -->
    <div class="scroll-to-top">
      <span class="scroll-icon">
        <i class="fa fa-rocket" aria-hidden="true"></i>
      </span>
    </div>
    <!-- scroll-to-top end -->

<div class="main-wrapper">

    <!-- hero start -->
    <!-- hero end -->

    @yield('content')


</div><!-- main-wrapper end -->

<!-- footer section start -->
    {{-- @if(!in_array(Route::currentRouteName(), $notInclude))
        @include($activeTemplate.'partials.footer')
    @endif --}}
<!-- footer section end -->

    <!-- jQuery library -->
  <script src="{{asset($activeTemplateTrue.'/js/lib/jquery-3.6.0.min.js')}}"></script>
  <!-- bootstrap js -->
  <script src="{{asset($activeTemplateTrue.'/js/lib/bootstrap.bundle.min.js')}}"></script>
  <!-- slick slider js -->
  <script src="{{asset($activeTemplateTrue.'/js/lib/slick.min.js')}}"></script>
  <!-- scroll animation -->
  <script src="{{asset($activeTemplateTrue.'/js/lib/wow.min.js')}}"></script>
  <!-- lightcase js -->
  <script src="{{asset($activeTemplateTrue.'/js/lib/lightcase.min.js')}}"></script>
  <!-- parallax js -->
  <script src="{{asset($activeTemplateTrue.'/js/lib/jquery.paroller.min.js')}}"></script>
  <!-- Tweenmax Js -->
  <script src="{{asset($activeTemplateTrue.'/js/lib/TweenMax.min.js')}}"></script>
  <!-- main js -->
  <script src="{{asset($activeTemplateTrue.'/js/app.js')}}"></script>

  @include('admin.partials.notify')

  @stack('script-lib')

  @stack('script')

  <script>

    "use strict";

    $(document).on("change", ".langSel", function() {
        window.location.href = "{{url('/')}}/change/"+$(this).val() ;
    });

  </script>

  </body>
</html>
