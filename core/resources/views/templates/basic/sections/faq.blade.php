@php
    $faq = getContent('faq.content', true);
    $questions = getContent('faq.element');
@endphp

<!-- faq section start -->
<section class="pt-100 pb-100">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-6 text-center">
          <div class="section-header">
            <h2 class="section-title">{{ __(@$faq->data_values->heading) }}</h2>
            <p class="mt-3">
                {{ __(@$faq->data_values->sub_heading) }}
            </p>
          </div>
        </div>
      </div><!-- row end -->
      <div class="accordion custom--accordion" id="faqAccordion">
        <div class="row gy-4">

          @foreach($questions as $index => $qustion)
            <div class="col-lg-6">
              <div class="accordion-item">
                  <h2 class="accordion-header" id="h-{{ $qustion->id }}">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#c-{{ $qustion->id }}" aria-expanded="false" aria-controls="{{ $qustion->id }}">
                      {{ __($qustion->data_values->question) }}
                  </button>
                  </h2>
                  <div id="c-{{ $qustion->id }}" class="accordion-collapse collapse" aria-labelledby="h-{{ $qustion->id }}" data-bs-parent="#faqAccordion">
                  <div class="accordion-body">
                      <p>
                          {{ __($qustion->data_values->ans) }}
                      </p>
                  </div>
                  </div>
              </div><!-- accordion-item-->
            </div>
          @endforeach

        </div>
      </div>
    </div>
  </section>
  <!-- faq section end -->
