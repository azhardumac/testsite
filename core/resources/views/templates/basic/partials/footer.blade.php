@php
    $pageLinks = getContent('links.element');
    $data = getContent('footer.content', true);
    $socialLinks = getContent('footer.element');
@endphp

<footer class="footer section-top--border scroll-section">
    <div class="footer__top">
      <div class="container">
        <div class="row">
          <div class="col-lg-12 text-center">
            <a href="{{ route('home') }}" class="footer-logo"><img src="{{getImage(imagePath()['logoIcon']['path'] .'/logo.png')}}" alt="image"></a>
            <ul class="footer-menu d-flex flex-wrap justify-content-center mt-4">

            </ul>
            <ul class="social-links d-flex flex-wrap align-items-center mt-4 justify-content-center">

            @foreach($socialLinks as $index => $socialLink)
                <li><a href="{{ $socialLink->data_values->social_link }}" target="_blank">
                  @php echo $socialLink->data_values->social_icon; @endphp</a>
                </li>
            @endforeach

            </ul>
            <div class="col-lg-6 mx-auto mt-3">
              <p>{{ __(@$data->data_values->heading) }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="footer__bottom">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-md-6 text-md-start text-center">
            <p>{{ @$data->data_values->copy_right }}</p>
          </div>
          <div class="col-md-6">
            <ul class="footer-menu d-flex flex-wrap justify-content-end">

            @foreach($pageLinks as $link)
                <li>
                  <a href="{{ route('slug.page', ['id'=>$link->id, 'slug'=>slug($link->data_values->title)]) }}">
                    {{ __($link->data_values->title) }}</a>
                </li>
            @endforeach

            </ul>
          </div>
        </div>
      </div>
    </div>
  </footer>
