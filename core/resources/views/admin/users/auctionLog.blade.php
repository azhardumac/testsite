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
                                <th scope="col">@lang('Quantity')</th>
                                <th scope="col">@lang('Profit')</th>
                                <th scope="col">@lang('Amount')</th>
                                <th scope="col">@lang('Status')</th>
                                <th scope="col">@lang('Published At')</th>
                                <th scope="col">@lang('Completed At')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($histories as $index => $history)
                                <tr>
                                    <td data-label="@lang('Quantity')">
                                        {{ getAmount($history->quantity ) }}
                                        {{ __($general->coin_name) }}
                                    </td>

                                    <td data-label="@lang('Profit')">
                                        {{ getAmount($history->expected_profit) }}%
                                    </td>

                                    <td data-label="@lang('Amount')">
                                        {{ getAmount($history->amount) }}
                                        {{ __($general->cur_text) }}
                                    </td>

                                    <td data-label="@lang('Auction Status')">
                                        @if($history->status == 1)
                                            <span class="badge badge--primary">@lang('Running')</span>
                                        @elseif($history->status == 2)
                                            <span class="badge badge--danger">@lang('Backed')</span>
                                        @elseif($history->status == 3)
                                            <span class="badge badge--success">@lang('Completed')</span>
                                        @endif
                                    </td>

                                    <td data-label="@lang('Published At')">
                                        {{ showDateTime($history->created_at) }}
                                    </td>

                                    <td data-label="@lang('Completed At')">
                                        @if($history->auction_completed)
                                            {{ showDateTime($history->auction_completed) }}
                                        @else 
                                            @lang('N/A')
                                        @endif
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

