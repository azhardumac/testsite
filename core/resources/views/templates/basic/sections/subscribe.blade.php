  @php

    $data = getContent('subscribe.content', true);

  @endphp

  <!-- subscribe section start -->
  <div class="pt-100 pb-100 bg_img" style="background-image: url('{{ getImage( 'assets/images/frontend/subscribe/' .@$data->data_values->image, '1920x865') }}')">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <div class="subscribe-wrapper text-center">
            <h2 class="title">{{ __(@$data->data_values->heading) }}</h2>
            <p>
                {{ __(@$data->data_values->sub_heading) }}
            </p>
            <form class="subscribe-form mt-4" action="" method="post" id="subscribe">
                @csrf
              <input type="email" required name="email" class="form-control email" placeholder="Enter email address">
              <button type="submit" class="subscribe-btn">{{ __($data->data_values->btn_text) }} <i class="far fa-paper-plane ms-2"></i></button>
            </form>
          </div><!-- subscribe-wrapper end -->
        </div>
      </div>
    </div>
  </div>

@push('script')

  <script>
      (function($){

          "use strict";

          var formEl = $("#subscribe");

          formEl.on('submit', function(e){
              e.preventDefault();
              var data = formEl.serialize();

              $.ajax({
              url:'{{ route('subscribe') }}',
              method:'post',
              data:data,

              success:function(response){
                  if(response.success){
                      $('.email').val('');
                      notify('success', response.message);
                  }else{
                      $.each(response.error, function( key, value ) {
                          notify('error', value);
                      });
                  }
              },
              error:function(error){
                  console.log(error)
              }

              });
          });

      })(jQuery);
  </script>

@endpush
