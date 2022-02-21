@extends($activeTemplate.'layouts.master')

@php
    $userDashboardImage = getContent('user.content', true);
@endphp

@section('content')

<section class="pt-100 pb-100 overlay--one bg_img" style="background-image: url('{{ getImage( 'assets/images/frontend/user/' .@$userDashboardImage->data_values->background_image, '1920x1080') }}');">
    <div class="container">

      <div class="row mb-none-30">

      </div><!-- row end -->
      <div class="row mt-5">
        <div class="col-lg-12">
          <div class="table-responsive table-responsive--sm">
            <table class="table custom--table">
              <thead>
                <tr>
                    <th scope="col">@lang('Username')</th>
                    <th scope="col">@lang('Email')</th>
                    <th scope="col">@lang('Phone')</th>
                    <th scope="col">@lang('Total Deposit')</th>
                </tr>
              </thead>
              <tbody>
                    @forelse($referrals as $user)
                        <tr>
                            <td data-label="@lang('Username')">{{$user->username}}</td>
                            <td data-label="@lang('Email')">{{$user->email}}</td>
                            <td data-label="@lang('Phone')">{{$user->mobile}}</td>
                            <td data-label="@lang('Total Deposit')">{{getAmount($user->deposits()->sum('amount'))}} {{ $general->cur_text }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="100%"> @lang('No results found')!</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{$referrals->links()}}
          </div>
        </div>
      </div><!-- row end -->
    </div>
  </section>

@endsection

