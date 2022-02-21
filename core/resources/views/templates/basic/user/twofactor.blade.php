@extends($activeTemplate.'layouts.master')

@php
    $userDashboardImage = getContent('user.content', true);
@endphp

@section('content')

<div class="pt-100 pb-100 overlay--one bg_img" style="background-image: url('{{ getImage( 'assets/images/frontend/user/' .@$userDashboardImage->data_values->background_image, '1920x1080') }}');">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10 col-md-8">
          <div class="card custom--card">
            <div class="card-body p-4">
              <div class="row align-items-center">
                <div class="col-lg-4">
                  <div class="qr-code-wrapper rounded-2">
                    @if(!Auth::user()->ts)
                        <img src="{{$qrCodeUrl}}" alt="image" class="w-100">
                    @endif
                    <p class="font-size--14px text-center mt-3">Use Google Authentication App to scan the QR code. <a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en" target="_blank" class="text--base">App link</a></p>
                  </div>
                </div>
                <div class="col-lg-8 ps-lg-5 mt-lg-0 mt-5">
                  <div class="qr-code-content">
                    @if(!Auth::user()->ts)
                        <div class="qr-code text--base">
                        <form class="qr-code-copy-form" data-copy=true>
                            <input type="text" value="{{$secret}}" class="data-click-select-all" readonly>
                            <input type="submit" value="Copy">
                        </form>
                        </div>
                    @endif
                    <p class="mt-3">If you have any problem with scanning the QR code enter this code manually into the APP.</p>
                    @if(!Auth::user()->ts)
                        <form class="qr-code-form mt-4" onsubmit="return false;">
                            <button type="submit" class="bnt btn--base w-100 rounded" data-bs-toggle="modal" data-bs-target="#enableModal">@lang('Enable Two Factor Authenticator')</button>
                        </form>
                    @else
                        <button type="submit" class="bnt btn--base w-100 rounded" data-bs-toggle="modal" data-bs-target="#disableModal">@lang('Disable Two Factor Authenticator')</button>
                    @endif
                  </div>
                </div>
              </div>
            </div>
          </div><!-- card end -->
        </div>
      </div>
    </div>
  </div>


    <!--Enable Modal -->
    <div id="enableModal" class="modal fade" role="dialog">
        <div class="modal-dialog ">
            <!-- Modal content-->
            <div class="modal-content ">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('Verify Your Otp')</h4>
                    <button type="button" class="btn-close bg-danger text-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{route('user.twofactor.enable')}}" method="POST">
                    @csrf
                    <div class="modal-body ">
                        <div class="form-group">
                            <input type="hidden" name="key" value="{{$secret}}">
                            <input type="text" class="form-control" name="code" placeholder="@lang('Enter Google Authenticator Code')">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn bg-danger text-white" data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn custom--bg text-white">@lang('Verify')</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <!--Disable Modal -->
    <div id="disableModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('Verify Your Otp Disable')</h4>
                    <button type="button" class="btn-close bg-danger text-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{route('user.twofactor.disable')}}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="text" class="form-control" name="code" placeholder="@lang('Enter Google Authenticator Code')">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn bg-danger text-white" data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn custom--bg text-white">@lang('Verify')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('script')
    <script>
        "use strict";

        var els_copy = document.querySelectorAll("[data-copy]");
            for (var i = 0; i < els_copy.length; i++) {
            var el = els_copy[i];
            el.addEventListener("submit", function(e) {
                e.preventDefault();
                var text = e.target.querySelector('input[type="text"]').select();
                document.execCommand("copy");
                notify('success', 'Copied Successfully');
            });
            }

            var els_selectAll = document.querySelectorAll("[data-click-select-all]");
            for (var i = 0; i < els_selectAll.length; i++) {
            var el = els_selectAll[i];
            el.addEventListener("click", function(e) {
                e.target.select();
            });
        }

    </script>
@endpush


