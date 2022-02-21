@extends($activeTemplate.'layouts.frontend')

@php
    $image = getContent('links.content', true);
@endphp

@section('content')

<section class="pt-100 pb-100 overlay--one bg_img" style="background-image: url('{{ getImage( 'assets/images/frontend/links/' .@$image->data_values->image, '1920x1010') }}');">
    <div class="container">
        <div class="row mb-none-30">
            @php echo $content->data_values->content; @endphp
        </div>
    </div>
</section>

@endsection


