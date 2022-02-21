@extends($activeTemplate.'layouts.master')

@php
    $userDashboardImage = getContent('user.content', true);
@endphp

@section('content')
<section class="pt-100 pb-100 overlay--one bg_img" style="background-image: url('{{ getImage( 'assets/images/frontend/user/' .@$userDashboardImage->data_values->background_image, '1920x1080') }}');">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="custom--card custom--border">
                    <div class="card-header card-header-bg">
                        <h3>{{__($page_title)}}</h3>
                    </div>
                    <div class="card-body  ">
                        <form action="{{ route('user.deposit.manual.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <p class="text-center mt-2">@lang('You have requested') <b class="text-success">{{ getAmount($data['amount'])  }} {{__($general->cur_text)}}</b> , @lang('Please pay')
                                        <b class="text-success">{{getAmount($data['final_amo']) .' '.$data['method_currency'] }} </b> @lang('for successful payment')
                                    </p>

                                    <h4 class="text-center mb-4">@lang('Please follow the instruction bellow')</h4>

                                    <p class="my-4 text-center custom--cl description">@php echo $data->gateway->description @endphp</p>

                                </div>

                                @if($method->gateway_parameter)

                                    @foreach(json_decode($method->gateway_parameter) as $k => $v)

                                        @if($v->type == "text")
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label><strong>{{__(inputTitle($v->field_level))}} @if($v->validation == 'required') <span>*</span>  @endif</strong></label>
                                                    <input type="text" class="form-control form-control-lg" name="{{$k}}" value="{{old($k)}}" placeholder="{{__($v->field_level)}}">
                                                </div>
                                            </div>
                                        @elseif($v->type == "textarea")
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label><strong>{{__(inputTitle($v->field_level))}} @if($v->validation == 'required') <span>*</span>  @endif</strong></label>
                                                        <textarea name="{{$k}}"  class="form-control"  placeholder="{{__($v->field_level)}}" rows="3">{{old($k)}}</textarea>

                                                    </div>
                                                </div>
                                        @elseif($v->type == "file")
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label><strong>{{__($v->field_level)}} @if($v->validation == 'required') <span>*</span>  @endif</strong></label>
                                                    <br>

                                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                                        <div class="fileinput-new thumbnail withdraw-thumbnail" data-trigger="fileinput">
                                                            <img src="{{ asset(getImage('/')) }}" alt="@lang('Image')">
                                                        </div>
                                                        <div class="fileinput-preview fileinput-exists thumbnail"></div>

                                                        <div class="img-input-div">
                                                            <span class="btn btn-info btn-file">
                                                                <span class="fileinput-new clickBtn"> @lang('Select') {{__($v->field_level)}}</span>
                                                                <span class="fileinput-exists text-white"> @lang('Change')</span>
                                                                <input type="file" name="{{$k}}" accept="image/*" >
                                                            </span>
                                                            <a href="#" class="btn btn-danger fileinput-exists"
                                                            data-dismiss="fileinput"> @lang('Remove')</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn--base mt-2 text-center">@lang('Pay Now')</button>
                                    </div>
                                </div>

                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('style')
    <style>
       .fileinput-preview {
            width: 100%;
            /*height: 250px;*/
            display: block;
        }
        .fileinput-preview img{
            width: 400px;
        }
        .description span{
            color: white !important;
        }
    </style>
@endpush

@push('style')
    <link rel="stylesheet" href="{{asset($activeTemplateTrue. '/css/bootstrap-fileinput.css')}}">
@endpush

@push('script-lib')
    <script src="{{asset($activeTemplateTrue. '/js/bootstrap-fileinput.js')}}"></script>
@endpush

@push('script')
<script>

    (function($){

        "use strict";

            $('.withdraw-thumbnail').hide();

            $('.clickBtn').on('click', function() {

                var classNmae = $('.fileinput').attr('class');

                if(classNmae != 'fileinput fileinput-exists'){
                    $('.withdraw-thumbnail').hide();
                }else{
                    $('.fileinput-preview img').css({"width":"100%", "height":"300px", "object-fit":"contain"});
                    $('.withdraw-thumbnail').show();
                }

            });

    })(jQuery);

</script>
@endpush
