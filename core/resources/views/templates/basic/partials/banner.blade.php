@php
    $banner = getContent('banner.content', true);
@endphp


    <section class="hero bg_img scroll-section"
      style="background-image: url({{ getImage( 'assets/images/frontend/banner/' .@$banner->data_values->background_image, '1920x1080') }});"
      data-paroller-factor="0.3"
    >
      <div class="container">
        <div class="row justify-content-between align-items-center">
          <div class="col-xxl-6 col-lg-6 text-lg-start text-center">
            <h2 class="hero__title wow fadeInUp" data-wow-duration="0.5" data-wow-delay="0.3s">{{ __(@$banner->data_values->heading) }}</h2>
            <p class="mt-3 wow fadeInUp" data-wow-duration="0.5" data-wow-delay="0.5s">
                {{ __(@$banner->data_values->sub_heading) }}
            </p>
            <div class="mt-lg-5 mt-3 wow fadeInUp" data-wow-duration="0.5" data-wow-delay="0.3s">
              <a href="{{ @$banner->data_values->btn_link }}" class="btn btn--base magnetic-effect">
                {{ __(@$banner->data_values->btn_text) }}
              </a>
            </div>
          </div>
          <div class="col-xxl-5 col-lg-6 mt-lg-0 mt-5">
            <div class="count-wrapper glass--bg rounded-2 text-center">
              <h2 class="title">
                {{ __($phaseType) }}
                {{ __(strtoupper(@$plan->stage)) }}
                 @lang('PHASE')
              </h2>
              <p class="mt-2">{{ __($banner->data_values->phase_text) }}</p>

              <div id="countdown" class="mt-5" data-date="
                @if($phaseType == 'RUNNING')
                    {{ date('m, d, Y', strtotime(@$plan->end_date)) }}
                @elseif($phaseType == 'UP COMMING')
                    {{ date('m, d, Y', strtotime(@$plan->start_date)) }}
                @else
                    {{ date('Y-m-d', strtotime(' -1 day')) }}
                @endif
               24:00:00
               ">

                <ul class="date-unit-list d-flex flex-wrap justify-content-between">
                  <li class="single-unit"><span id="days"></span>@lang('Days')</li>
                  <li class="single-unit"><span id="hours"></span>@lang('Hours')</li>
                  <li class="single-unit"><span id="minutes"></span>@lang('Minutes')</li>
                  <li class="single-unit"><span id="seconds"></span>@lang('Seconds')</li>
                </ul>
              </div>
              <p class="mt-3">@lang('Current price') <span class="fw-bold">{{ getAmount(@$plan->price) }} {{ __($general->cur_text) }}</span></p>
              <a href="{{ Auth::user() != null ? route('user.coin.buy') : route('user.login') }}" class="btn btn--base mt-4">@lang('Buy Token Now')</a>
            </div>
          </div>
        </div>
      </div>
    </section>

@push('script')
<script>
    (function($){
        // variable for time units
        const second = 1000,
            minute = second * 60,
            hour = minute * 60,
            day = hour * 24;

        // get attribute date data from HTML
        let dateData = document.querySelector('#countdown').getAttribute('data-date');
        let finalDay = dateData,
            countDown = new Date(finalDay).getTime(),
            x = setInterval(function() {

            let now = new Date().getTime(),
                distance = countDown - now;

            let daysVar = document.getElementById("days");
                hoursVar = document.getElementById("hours");
                minutesVar = document.getElementById("minutes");
                secondsVar = document.getElementById("seconds");

            daysVar.innerText = Math.floor(distance / (day)),
            hoursVar.innerText = Math.floor((distance % (day)) / (hour)),
            minutesVar.innerText = Math.floor((distance % (hour)) / (minute)),
            secondsVar.innerText = Math.floor((distance % (minute)) / second);

            //do something later when date is reached
            if (distance < 0) {
                daysVar.innerText = "0";
                hoursVar.innerText = "0";
                minutesVar.innerText = "0";
                secondsVar.innerText = "0";
                clearInterval(x);
            }
            seconds
            }, 0)
        })(jQuery);
    </script>
@endpush
