@extends($activeTemplate.'layouts.master')

@php
    $userDashboardImage = getContent('user.content', true);
@endphp

@section('content')

<section class="pt-100 pb-100 overlay--one bg_img" style="background-image: url(' {{ getImage( 'assets/images/frontend/user/' .@$userDashboardImage->data_values->background_image, '1920x1080') }} '); background-attachment: fixed;">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-10">
          <div class="card custom--card">
            <div class="card-header">
              <div class="row">
                <div class="col-sm-10 d-flex flex-wrap align-items-center">
                    <h4 class="ms-2">@lang('Support Tickets')</h4>
                </div>
                <div class="col-sm-2 text-end">
                  <a class="btn btn--base btn-sm delete-message" href="{{route('ticket') }}">@lang('My Supports')</a>
                </div>
              </div>
            </div>
            <div class="card-body">

                <form  action="{{route('ticket.store')}}"  method="post" enctype="multipart/form-data" onsubmit="return submitUserForm();">
                    @csrf

                <div class="row">
                    <div class="form-group col-lg-6">
                        <input type="text" name="name" value="{{@$user->firstname . ' '.@$user->lastname}}" class="form-control form-control-lg" placeholder="@lang('Enter Name')" required>
                    </div>

                    <div class="form-group col-lg-6">
                        <input type="email"  name="email" value="{{@$user->email}}" class="form-control form-control-lg" placeholder="@lang('Enter your Email')" required>
                    </div>

                    <div class="form-group">
                        <input type="text" name="subject" value="{{old('subject')}}" class="form-control form-control" placeholder="@lang('Subject')" >
                    </div>

                    <div class="form-group">
                        <textarea name="message" placeholder="@lang('Your Reply')" id="inputMessage" class="form-control"></textarea>
                    </div>

                    <div class="form-group text-end">
                        <a href="#0" onclick="extraTicketAttachment()" class="btn btn--base"><i class="fa fa-plus"></i></a>
                        <button type="submit" class="btn btn--base" id="recaptcha" value="1">@lang('Submit')</button>
                    </div>

                    <div class="form-group">
                        <label for="supportTicketFile" class="form-label">@lang('Attachments')</label>
                        <input class="form-control custom--file-upload" type="file" id="inputAttachments" multiple name="attachments[]">
                        <div id="fileUploadsContainer"></div>
                        <div class="form-text text--muted">
                            @lang('Allowed File Extensions'): .@lang('jpg'), .@lang('jpeg'), .@lang('png'), .@lang('pdf'), .@lang('doc'), .@lang('docx')
                        </div>
                    </div>
                </div>

            </form>

            </div>
          </div><!-- card end -->
        </div>
      </div>
    </div>
  </section>

@endsection


@push('script')
    <script>
        function extraTicketAttachment() {
            $("#fileUploadsContainer").append('<input type="file" name="attachments[]" class="form-control my-3 form-control custom--file-upload" required/>')
        }
        function formReset() {
            window.location.href = "{{url()->current()}}"
        }
    </script>
@endpush
