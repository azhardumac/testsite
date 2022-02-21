 @php
     $team = getContent('team.content', true);
     $members = getContent('team.element');
 @endphp

 <!-- team section start -->
 <section class="pt-100 pb-100">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-6 text-center">
          <div class="section-header">
            <h2 class="section-title">{{ __(@$team->data_values->heading) }}</h2>
            <p class="mt-3">
                {{ __(@$team->data_values->sub_heading) }}
            </p>
          </div>
        </div>
      </div><!-- row end -->
      <div class="row justify-content-center mb-none-30">

        @foreach($members as $index => $member)
            <div class="col-xl-3 col-lg-4 col-sm-6 mb-30 wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.3s">
                <div class="team-card" style="background-image: url('{{ asset($activeTemplateTrue.'images/team/team-bg.jpg') }}')">
                <div class="team-card__thumb">
                    <img src="{{ getImage( 'assets/images/frontend/team/' .$member->data_values->image, '275x335') }}" alt="image" class="rounded-3">
                </div>
                <div class="team-card__content text-center">
                    <h3 class="name">{{ __($member->data_values->name) }}</h3>
                    <span class="designation">{{ __($member->data_values->designation) }}</span>
                </div>
                </div><!-- team-card end -->
          </div>
        @endforeach

      </div>
    </div>
  </section>
  <!-- team section end -->
