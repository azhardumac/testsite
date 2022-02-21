@extends($activeTemplate.'layouts.frontend')

@php
    $contact = getContent('contact.content', true);
    $datas = getContent('contact.element');
@endphp

@section('content')

<!-- contact section start -->
<div class="pt-100 pb-100 overlay--one bg_img" style="background-image: url(' {{ getImage( 'assets/images/frontend/contact/' .@$contact->data_values->background_image, '1920x1010') }} ');">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <form class="contact-form rounded-3" method="post" action="">
              @csrf
            <h2 class="text-white mb-4">{{ __(@$contact->data_values->heading) }}</h2>
            <div class="row">
              <div class="col-lg-6 form-group">
                <label>@lang('Name') <sup class="">*</sup></label>
                <input name="name" type="text" placeholder="@lang('Your Name')" class="form-control" value="{{ old('name') }}" required>
              </div>
              <div class="col-lg-6 form-group">
                <label>@lang('Email') <sup class="">*</sup></label>
                <input name="email" type="text" placeholder="@lang('Enter E-Mail Address')" class="form-control" value="{{old('email')}}" required>
              </div>
              <div class="col-lg-12 form-group">
                <label>@lang('Subject') <sup class="">*</sup></label>
                <input name="subject" type="text" placeholder="@lang('Write your subject')" class="form-control" value="{{old('subject')}}" required>
              </div>
              <div class="col-lg-12 form-group">
                <label>@lang('Message') <sup class="">*</sup></label>
                <textarea name="message" wrap="off" placeholder="@lang('Write your message')" class="form-control">{{old('message')}}</textarea>
              </div>
              <div class="col-lg-12 text-center">
                <button type="submit" class="btn btn--base">@lang('Send Message') <i class="las la-long-arrow-alt-right ml-2 font-size--18px"></i></button>
              </div>
            </div><!-- row end -->
          </form>
        </div>
      </div><!-- row end -->
      <div class="row justify-content-center mt-5 pt-3 mb-none-30">

        @foreach($datas as $index => $data)
            <div class="col-md-4 col-sm-12 mb-30">
                <div class="contact-card rounded-3">
                    <div class="contact-card__icon text--base">
                        @php echo $data->data_values->icon; @endphp
                    </div>
                    <div class="contact-card__content">
                        <h4 class="title">{{ __($data->data_values->heading) }}</h4>
                        <p class="caption">{{ __($data->data_values->text) }}</p>
                    </div>
                </div><!-- contact-info-card end -->
            </div>
        @endforeach

      </div>
    </div>
  </div>
  <!-- contact section end -->

@endsection
