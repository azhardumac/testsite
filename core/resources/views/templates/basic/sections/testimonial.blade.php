@php
    $data = getContent('testimonial.content', true);
    $users = getContent('testimonial.element');
@endphp

<!-- testimonial section start -->
 <section
 class="pt-100 pb-100"
 style="background-image: url(' {{ getImage( 'assets/images/frontend/testimonial/' .@$data->data_values->background_image, '1920x1280') }} ');"
>
 <div class="container">
   <div class="row justify-content-center">
     <div class="col-lg-6 text-center">
       <div class="section-header">
         <h2 class="section-title">{{ __(@$data->data_values->heading) }}</h2>
         <p class="mt-3">
            {{ __(@$data->data_values->sub_heading) }}
         </p>
       </div>
     </div>
   </div><!-- row end -->
   <div class="testimonial-slider">

    @foreach($users as $index => $user)
        <div class="single-slide">
        <div class="testimonial-card">
            <div class="testimonial-card__content">
            <p>{{ __($user->data_values->say) }}</p>
            </div>
            <div class="testimonial-card__client">
            <div class="thumb"><img src="{{ getImage( 'assets/images/frontend/testimonial/' .$user->data_values->image, '85x90') }}" alt="image" class="object-fit--cover"></div>
            <h4 class="name">{{ __($user->data_values->name) }}</h4>
            <span class="designation">{{ __($user->data_values->designation) }}</span>
            </div>
        </div><!-- testimonial-card end -->
        </div><!-- single-slide end -->
    @endforeach

   </div><!-- testimonial-slider end -->
 </div>
</section>
<!-- testimonial section end -->
