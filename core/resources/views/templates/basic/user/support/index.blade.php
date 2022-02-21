@extends($activeTemplate.'layouts.master')

@php
    $userDashboardImage = getContent('user.content', true);
@endphp

@section('content')

<section class="pt-100 pb-100 overlay--one bg_img" style="background-image: url('{{ getImage( 'assets/images/frontend/user/' .@$userDashboardImage->data_values->background_image, '1920x1080') }}');">
    <div class="container">


    <div class="text-end mb-4">
        <a href="{{ route('ticket.open') }}" class="custom--card d-inline-flex card-header highlighted-text" style="color: white !important;">
            @lang('New Ticekt')
        </a>
    </div>


      <div class="row mt-5">
        <div class="col-lg-12">
          <div class="table-responsive table-responsive--sm">
            <table class="table custom--table">
              <thead>
                <tr>
                    <th scope="col">@lang('Subject')</th>
                    <th scope="col">@lang('Status')</th>
                    <th scope="col">@lang('Last Reply')</th>
                    <th scope="col">@lang('Action')</th>
                </tr>
              </thead>
              <tbody>
                @forelse($supports as $key => $support)
                    <tr>
                        <td data-label="@lang('Subject')"> <a href="{{ route('ticket.view', $support->ticket) }}" class="font-weight-bold text-white"> [Ticket#{{ $support->ticket }}] {{ __($support->subject) }} </a></td>
                        <td data-label="@lang('Status')">
                            @if($support->status == 0)
                                <span class="badge badge--success rounded-pill">@lang('Open')</span>
                            @elseif($support->status == 1)
                                <span class="badge badge--info rounded-pill">@lang('Answered')</span>
                            @elseif($support->status == 2)
                                <span class="badge badge--warning rounded-pill">@lang('Customer Reply')</span>
                            @elseif($support->status == 3)
                                <span class="badge badge--danger rounded-pill">@lang('Closed')</span>
                            @endif
                        </td>
                        <td data-label="@lang('Last Reply')">{{ \Carbon\Carbon::parse($support->last_reply)->diffForHumans() }} </td>

                        <td data-label="@lang('Action')">
                            <a href="{{ route('ticket.view', $support->ticket) }}" class="btn btn--primary btn-sm text-dark">
                                <i class="fa fa-desktop"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="100%" class="text-center"> @lang('No results found')!</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
            {{$supports->links()}}
          </div>
        </div>
      </div><!-- row end -->
    </div>
  </section>

@endsection
