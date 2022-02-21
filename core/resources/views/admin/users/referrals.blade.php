@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                            <tr>
                                <th scope="col">@lang('User')</th>
                                <th scope="col">@lang('Username')</th>
                                <th scope="col">@lang('Email')</th>
                                <th scope="col">@lang('Phone')</th>
                                <th scope="col">@lang('Total Deposit')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($referrals as $user)
                                <tr>
                                    <td data-label="@lang('User')">
                                        <div class="user">
                                            <div class="thumb">
                                                <img src="{{ getImage(imagePath()['profile']['user']['path'].'/'.$user->image,imagePath()['profile']['user']['size'])}}" alt="@lang('image')">
                                            </div>
                                            <span class="name"><a href="{{ route('admin.users.detail', $user->id) }}">{{ $user->username }}</a></span>
                                        </div>
                                    </td>
                                    <td data-label="@lang('Username')">{{$user->username}}</td>
                                    <td data-label="@lang('Email')">{{$user->email}}</td>
                                    <td data-label="@lang('Phone')">{{$user->mobile}}</td>
                                    <td data-label="@lang('Total Deposit')">{{getAmount($user->deposits()->sum('amount'))}} {{ $general->cur_text }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="100%" class="text-center"> @lang('No results found')!</td>
                                </tr>
                            @endforelse

                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
                <div class="card-footer py-4">
                    {{ $referrals->links() }}
                </div>
            </div><!-- card end -->
        </div>


    </div>
@endsection
