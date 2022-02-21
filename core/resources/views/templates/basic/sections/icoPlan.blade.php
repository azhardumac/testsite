@php
    use Carbon\Carbon;
    use App\IcoPlan;

    $plans = IcoPlan::get();
    $text = getContent('icoPlan.content', true);

@endphp

<!-- ico section start -->
<section class="pt-100 pb-100 bg_img overlay--one" style="background-image: url('{{ getImage( 'assets/images/frontend/icoPlan/' .@$text->data_values->background_image, '1920x1280') }} ');"
>
<div class="container">
  <div class="row justify-content-center">
    <div class="col-lg-6 text-center">
      <div class="section-header">
        <h2 class="section-title">{{ __(@$text->data_values->heading) }}</h2>
        <p class="mt-3">{{ __(@$text->data_values->sub_heading) }}</p>
      </div>
    </div>
  </div><!-- row end -->
  <div class="row">
    <div class="col-lg-12 wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.3s">
      <div class="table-responsive table-responsive--sm">
        <table class="table custom--table">
          <thead>
            <tr>
              <th>@lang('Start Date')</th>
              <th>@lang('End Date')</th>
              <th>@lang('Quantity')</th>
              <th>@lang('Price')</th>
              <th>@lang('Sold')</th>
              <th>@lang('Status')</th>
            </tr>
          </thead>
          <tbody>

        @forelse($plans as $index => $plan)

            <tr>
              <td data-label="Start Date">{{ showDateTime($plan->start_date) }}</td>
              <td data-label="End Date">{{ showDateTime($plan->end_date) }}</td>
              <td data-label="Quantity">{{ getAmount($plan->total_coin) }} {{ __($general->coin_name) }}</td>
              <td data-label="Price"> {{ getAmount($plan->price) }} {{ __($general->cur_text) }}</td>
              <td data-label="Sold">
                <div class="progress" style="height: 10px;">
                  <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: {{ getAmount($plan->sold / ($plan->total_coin ) * 100) }}%"></div>
                </div>
                <span class="text--success custom--cl">
                    {{ number_format($plan->sold / ($plan->total_coin ) * 100, 0) }}%
                </span>
              </td>

              @if($plan->start_date <= Carbon::now() and $plan->end_date >= Carbon::now())
                <td data-label="Status"><span class="badge badge--base rounded-pill">@lang('Running')</span></td>
              @elseif($plan->start_date > Carbon::now())
                <td data-label="Status"><span class="badge badge--info rounded-pill">@lang('Up Comming')</span></td>
              @elseif($plan->end_date < Carbon::now())
                <td data-label="Status"><span class="badge badge--success rounded-pill">@lang('Completed')</span></td>
              @endif

            </tr>

        @empty
            <tr>
                <td class="text-muted text-center" colspan="100%"><span class="text-white">@lang('There is no Phase')</span></td>
            </tr>
        @endforelse

          </tbody>
        </table>
      </div>
    </div>
  </div><!-- row end -->
</div>
</section>
<!-- ico section end -->
