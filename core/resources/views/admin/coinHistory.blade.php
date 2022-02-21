@extends('admin.layouts.app')

@section('panel')

@php
    use Carbon\Carbon;
@endphp
    <div class="row">

        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">

                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">

                            <thead>
                            <tr>
                                <th scope="col">@lang('Coin')</th>
                                <th scope="col">@lang('Rate')</th>
                                <th scope="col">@lang('Amount')</th>
                                <th scope="col">@lang('Post Balance')</th>
                                <th scope="col">@lang('Username')</th>
                                <th scope="col">@lang('Status')</th>
                                <th scope="col">@lang('Details')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($histories as $index => $history)
                                <tr>
                                    <td data-label="@lang('Coin')">
                                        {{ $history->type }}
                                        {{ getAmount($history->coin_quantity ) }}
                                        {{ __($general->coin_name) }}
                                    </td>

                                    <td data-label="@lang('Rate')">
                                        {{ getAmount($history->coin_rate) }}
                                        {{ __($general->cur_text) }}
                                    </td>

                                    <td data-label="@lang('Amount')">
                                        {{ getAmount($history->amount) }}
                                        {{ __($general->cur_text) }}
                                    </td>

                                    <td data-label="@lang('Post Balance')">
                                        {{ getAmount($history->coin_post_balance) }}
                                        {{ __($general->coin_name) }}
                                    </td>

                                    <td data-label="@lang('Username')">
                                        <a href="{{ route('admin.users.detail', $history->user_id) }}">
                                            {{ __($history->user->username) }}
                                        </a>
                                    </td>

                                    <td data-label="@lang('Status')">
                                        @if($history->status == 1)
                                            <span class="badge badge--success">@lang('Success')</span>
                                        @else
                                            <span class="text--small badge font-weight-normal badge--warning">@lang('Pending')</span>
                                        @endif
                                    </td>

                                    <td data-label="@lang('Details')">
                                        {{ __($history->details) }}
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">{{ __($empty_message) }}</td>
                                </tr>
                            @endforelse

                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
                <div class="card-footer py-4">
                    {{ $histories->links('admin.partials.paginate') }}
                </div>
            </div><!-- card end -->
        </div>
    </div>


@endsection

