@php
    $data = getContent('roadMap.content', true);
    $datas = getContent('roadMap.element');
@endphp

<!-- roadmap section start -->
<section
class="pt-100 pb-100 bg_img overlay--one"
style="background-image: url('{{ getImage( 'assets/images/frontend/roadMap/' .@$data->data_values->background_image, '2250x1500') }}');"
data-paroller-factor="0.3"
>
<div class="container">
  <div class="row justify-content-center">
    <div class="col-lg-6 text-center">
      <div class="section-header">
        <h2 class="section-title">{{ __(@$data->data_values->heading) }}</h2>
        <p class="mt-3">{{ __(@$data->data_values->sub_heading) }}</p>
      </div>
    </div>
  </div><!-- row end -->
  <div class="row justify-content-center">
    <div class="col-lg-10">
      <div class="roadmap-wrapper">

    @foreach($datas as $value)
        <div class="single-roadmap wow fadeInLeft" data-wow-duration="0.5s" data-wow-delay="0.3s">
            <div class="roadmap-dot"></div>
            <span class="roadmap-date">{{ __($value->data_values->date) }}</span>
            <h4 class="title">{{ __($value->data_values->title) }}</h4>
            <p>{{ __($value->data_values->text) }}</p>
        </div><!-- single-roadmap end -->
    @endforeach

      </div><!-- roadmap-wrapper end -->
    </div>
  </div>
</div>
</section>
<!-- roadmap section end -->
