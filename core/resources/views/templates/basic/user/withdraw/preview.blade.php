@extends($activeTemplate.'layouts.master')

@php
    $userDashboardImage = getContent('user.content', true);
@endphp

@section('content')
<section class="pt-100 pb-100 overlay--one bg_img" style="background-image: url('{{ getImage( 'assets/images/frontend/user/' .@$userDashboardImage->data_values->background_image, '1920x1080') }}');">
    <div class="container">
        <div class="row justify-content-center mt-2">
            <div class="col-lg-9 ">
                <div class="custom--card custom--border">
                    <h5 class="text-center my-1">@lang('Current Balance') :
                        <strong class="custom--cl">{{ getAmount(auth()->user()->balance)}}  {{ __($general->cur_text) }}</strong></h5>

                    <div class="card-body mt-4">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="withdraw-details">
                                    <span class="font-weight-bold">@lang('Request Amount') :</span>
                                    <span class="font-weight-bold pull-right custom--cl">{{getAmount($withdraw->amount)  }} {{__($general->cur_text)}}</span>
                                </div>
                                <div class="withdraw-details text-danger">
                                    <span class="font-weight-bold text-white">@lang('Withdrawal Charge') :</span>
                                    <span class="font-weight-bold pull-right custom--cl">{{getAmount($withdraw->charge) }} {{__($general->cur_text)}}</span>
                                </div>
                                <div class="withdraw-details text-info">
                                    <span class="font-weight-bold text-white">@lang('After Charge') :</span>
                                    <span class="font-weight-bold pull-right custom--cl">{{getAmount($withdraw->after_charge) }} {{__($general->cur_text)}}</span>
                                </div>
                                <div class="withdraw-details">
                                    <span class="font-weight-bold">@lang('Conversion Rate') : <br>1 {{__($general->cur_text)}} = </span>
                                    <span class="font-weight-bold pull-right custom--cl">  {{getAmount($withdraw->rate)  }} {{__($withdraw->currency)}}</span>
                                </div>
                                <div class="withdraw-details text-success">
                                    <span class="font-weight-bold text-white">@lang('You Will Get') :</span>
                                    <span class="font-weight-bold pull-right custom--cl">{{getAmount($withdraw->final_amount) }} {{__($withdraw->currency)}}</span>
                                </div>
                                <div class="form-group mt-5">
                                    <label class="font-weight-bold">@lang('Balance Will be') : </label>
                                    <div class="input-group">
                                        <input type="text" value="{{getAmount($withdraw->user->balance - ($withdraw->amount))}}"  class="form-control form-control-lg" placeholder="@lang('Enter Amount')" required readonly>
                                            <span class="input-group-text">{{ __($general->cur_text) }} </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <form action="{{route('user.withdraw.submit')}}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    @if($withdraw->method->user_data)
                                    @foreach($withdraw->method->user_data as $k => $v)
                                        @if($v->type == "text")
                                            <div class="form-group">
                                                <label><strong>{{__($v->field_level)}} @if($v->validation == 'required') <span>*</span>  @endif</strong></label>
                                                <input type="text" name="{{$k}}" class="form-control" value="{{old($k)}}" placeholder="{{__($v->field_level)}}" @if($v->validation == "required") required @endif>
                                                @if ($errors->has($k))
                                                    <span class="text-danger">{{ __($errors->first($k)) }}</span>
                                                @endif
                                            </div>
                                        @elseif($v->type == "textarea")
                                            <div class="form-group">
                                                <label><strong>{{__($v->field_level)}} @if($v->validation == 'required') <span>*</span>  @endif</strong></label>
                                                <textarea name="{{$k}}"  class="form-control"  placeholder="{{__($v->field_level)}}" rows="3" @if($v->validation == "required") required @endif>{{old($k)}}</textarea>
                                                @if ($errors->has($k))
                                                    <span class="text-danger">{{ __($errors->first($k)) }}</span>
                                                @endif
                                            </div>
                                        @elseif($v->type == "file")
                                            <label><strong>{{__($v->field_level)}} @if($v->validation == 'required') <span>*</span>  @endif</strong></label>
                                            <div class="form-group">
                                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                                    <div class="fileinput-new thumbnail withdraw-thumbnail"
                                                         data-trigger="fileinput">
                                                        <img class="w-100" src="{{ getImage('/')}}" alt="@lang('Image')">
                                                    </div>
                                                    <div class="fileinput-preview fileinput-exists thumbnail"></div>
                                                    <div class="img-input-div">
                                                        <span class="btn btn-info btn-file">
                                                            <span class="fileinput-new clickBtn"> @lang('Select') {{__($v->field_level)}}</span>
                                                            <span class="fileinput-exists text-white"> @lang('Change')</span>
                                                            <input type="file" name="{{$k}}" accept="image/*" @if($v->validation == "required") required @endif>
                                                        </span>
                                                        <a href="#" class="btn btn-danger fileinput-exists"
                                                        data-dismiss="fileinput"> @lang('Remove')</a>
                                                    </div>
                                                </div>
                                                @if ($errors->has($k))
                                                    <br>
                                                    <span class="text-white">{{ __($errors->first($k)) }}</span>
                                                @endif
                                            </div>
                                        @endif
                                    @endforeach
                                    @endif
                                    <div class="form-group">
                                        <button type="submit" class="btn btn--base btn-lg mt-4 text-center">@lang('Confirm')</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('style')
<style type="text/css">
   .fileinput-preview {
        width: 100%;
        /*height: 250px;*/
        display: block;
    }
    .fileinput-preview img{
        width: 400px;
    }
</style>
@endpush

@push('style')
    <link rel="stylesheet" href="{{asset($activeTemplateTrue.'/css/bootstrap-fileinput.css')}}">
@endpush

@push('script-lib')
    <script src="{{asset($activeTemplateTrue.'/js/bootstrap-fileinput.js')}}"></script>
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

