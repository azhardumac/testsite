@php
    $ico = getContent('whatIsICO.content', true);
@endphp

<!-- about section start -->
<section class="pt-100 pb-100 scroll-section">
    <div class="container">
    <div class="row align-items-center justify-content-between">
        <div class="col-lg-6 wow fadeInLeft" data-wow-duration="0.5" data-wow-delay="0.3s">
        <h2 class="section-title">{{ __(@$ico->data_values->heading) }}</h2>
        <p class="section-subtitle mt-3">
            {{ __(@$ico->data_values->description) }}
        </p>
        <div class="btn--group mt-4">
            <a href="{{ __(@$ico->data_values->btn_link) }}" class="btn btn--base">{{ __(@$ico->data_values->btn_text) }}</a>
            <a href="{{ @$ico->data_values->video_link }}" data-rel="lightcase:myCollection" class="video-btn video-btn--sm d-lg-none d-d-inline-flex"><i class="las la-play"></i></a>
        </div>
        </div>
        <div class="col-lg-5 d-lg-block d-none wow fadeInRight" data-wow-duration="0.5" data-wow-delay="0.5s">
            <div class="about-thumb">
                <a href="{{ @$ico->data_values->video_link }}" data-rel="lightcase:myCollection" class="video-btn"><i class="las la-play"></i></a>
                <img src="{{ getImage( 'assets/images/frontend/whatIsICO/' .@$ico->data_values->background_image, '526x582') }}" alt="image">
            </div>
        </div>
    </div>
    </div>
</section>
<!-- about section end -->
