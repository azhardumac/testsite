@php
    $data = getContent('feature.content', true);
    $datas = getContent('feature.element');
@endphp

 <!-- feature section start -->
  <section class="pt-100 pb-100">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-6 text-center">
          <div class="section-header">
            <h2 class="section-title">{{ __(@$data->data_values->heading) }}</h2>
            <p class="mt-3">{{ __(@$data->data_values->sub_heading) }}</p>
          </div>
        </div>
      </div><!-- row end -->
      <div class="row mb-none-30">

    @foreach($datas as $value)

    <div class="col-lg-4 col-md-6 mb-30 wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="1.3s">
        <div class="feature-card" style="background-image: url(' {{ getImage( 'assets/images/frontend/feature/' .$value->data_values->background_image, '625x315') }} ');">
        <div class="feature-card__icon">
            <i class="las la-shield-alt"></i>
        </div>
        <div class="feature-card__content">
            <h4 class="title">{{ __($value->data_values->title) }}</h4>
            <p class="mt-3">{{ __($value->data_values->description) }}</p>
        </div>
        </div><!-- feature-card end -->
    </div>

    @endforeach

      </div>
    </div>
  </section>
  <!-- feature section end -->
