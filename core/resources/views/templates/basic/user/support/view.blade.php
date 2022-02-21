@extends($extendTemplate)

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
                    @if($my_ticket->status == 0)
                        <span class="badge badge--success">@lang('Open')</span>
                    @elseif($my_ticket->status == 1)
                        <span class="badge badge--light">@lang('Answered')</span>
                    @elseif($my_ticket->status == 2)
                        <span class="badge badge--warning">@lang('Replied')</span>
                    @elseif($my_ticket->status == 3)
                        <span class="badge badge--danger">@lang('Closed')</span>
                    @endif
                  {{-- <span class="badge badge--success"></span> --}}
                  <h4 class="ms-2">[Ticket#{{ $my_ticket->ticket }}] {{ $my_ticket->subject }}</h4>
                </div>
                <div class="col-sm-2 text-end">
                  <button class="btn btn--danger btn-sm delete-message" data-bs-toggle="modal" data-bs-target="#DelModal"><i class="las la-times"></i></button>
                  </button>
                </div>
              </div>
            </div>
            <div class="card-body">

            @if($my_ticket->status != 4)

            <form method="post" action="{{ route('ticket.reply', $my_ticket->id) }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <textarea name="message" placeholder="@lang('Your Reply')" id="inputMessage" class="form-control"></textarea>
                </div>

                <div class="form-group text-end">
                    <a href="#0" onclick="extraTicketAttachment()" class="btn btn--base"><i class="fa fa-plus"></i></a>
                    <button type="submit" class="btn btn--base" name="replayTicket" value="1">@lang('Reply')</button>
                </div>

                <div class="form-group">
                    <label for="supportTicketFile" class="form-label">@lang('Attachments')</label>
                    <input class="form-control custom--file-upload" type="file" id="inputAttachments" multiple name="attachments[]">
                    <div id="fileUploadsContainer"></div>
                    <div class="form-text text--muted">
                        @lang('Allowed File Extensions'): .@lang('jpg'), .@lang('jpeg'), .@lang('png'), .@lang('pdf'), .@lang('doc'), .@lang('docx')
                    </div>
                </div>

            </form>

            @endif

              @foreach($messages as $message)
                @if($message->admin_id == 0)
                    <div class="single-reply">
                        <div class="left">
                            <h4>{{ $message->ticket->name }}</h4>
                        </div>
                        <div class="right">
                            <span class="fst-italic font-size--14px text--base mb-2">
                                @lang('Posted on') {{ $message->created_at->format('l, dS F Y @ H:i') }}
                            </span>
                            <p>{{$message->message}}</p>
                            @if($message->attachments()->count() > 0)
                                <div class="mt-2">
                                    @foreach($message->attachments as $k=> $image)
                                        <a href="{{route('ticket.download',encrypt($image->id))}}" class="mr-3 attachment-link"><i class="fa fa-file"></i>  @lang('Attachment') {{++$k}} </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="single-reply admin-reply">
                        <div class="left">
                            <h4>{{ $message->admin->name }}</h4>
                            <p class="text-muted text-white-50">@lang('Admin')</p>
                        </div>
                        <div class="right">
                            <p class="fst-italic font-size--14px text--base mb-2">
                                @lang('Posted on') {{ $message->created_at->format('l, dS F Y @ H:i') }}</p>
                            <p>{{$message->message}}</p>
                            @if($message->attachments()->count() > 0)
                                <div class="mt-2">
                                    @foreach($message->attachments as $k=> $image)
                                        <a href="{{route('ticket.download',encrypt($image->id))}}" class="mr-3"><i class="fa fa-file"></i>  @lang('Attachment') {{++$k}} </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        </div>
                    @endif
                @endforeach
            </div>
          </div><!-- card end -->
        </div>
      </div>
    </div>
  </section>


<div class="modal fade" id="DelModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="{{ route('ticket.reply', $my_ticket->id) }}">
                @csrf
                {{-- @method('PUT') --}}
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Confirmation')!</h5>
                    <button type="button" class="btn-close bg-danger text-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>@lang('Are you sure you want to close this support ticket')?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-danger text-white" data-bs-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn custom--bg text-white" name="replayTicket" value="2">@lang("Confirm")
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('script')
    <script>

        "use strict";

        (function($){
            $('.delete-message').on('click', function (e) {
                $('.message_id').val($(this).data('id'));
            });
        })(jQuery);

        function extraTicketAttachment() {
            $("#fileUploadsContainer").append('<input type="file" name="attachments[]" multiple class="form-control my-3 form-control custom--file-upload" required id="inputAttachments" style="margin"/>')
        }

    </script>
@endpush
