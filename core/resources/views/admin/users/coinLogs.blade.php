@extends('admin.layouts.app')

@section('panel')
    <div class="row">

        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                            <tr>
                                <th scope="col">@lang('Quantity')</th>
                                <th scope="col">@lang('Coin Rate')</th>
                                <th scope="col">@lang('Post Balance')</th>
                                <th scope="col">@lang('Amount')</th>
                                <th scope="col">@lang('Status')</th>
                                <th scope="col">@lang('Date')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($logs as $index => $log)
                                <tr>
                                    <td data-label="@lang('Quantity')">
                                        {{ getAmount($log->coin_quantity) }}
                                        {{ __($general->coin_name) }}
                                    </td>
                                    <td data-label="@lang('Coin Rate')">{{ getAmount($log->coin_rate) }} {{ __($general->cur_text) }}</td>
                                    <td data-label="@lang('Post Balance')">
                                        {{ getAmount($log->coin_post_balance) }}
                                        {{ __($general->coin_name) }}
                                    </td>
                                    <td data-label="@lang('Amount')">{{ getAmount($log->amount) }} {{ __($general->cur_text) }}</td>
                                    <td data-label="@lang('Status')">
                                        @if($log->status == 1)
                                        <span class="badge badge--success">@lang('Success')</span>
                                        @else
                                        <span class="badge badge--danger">@lang('Pending')</span>
                                        @endif
                                    </td>
                                    <td data-label="@lang('Date')">
                                        {{ showDateTime($log->created_at) }}
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
                {{ $logs->links('admin.partials.paginate') }}
            </div>
            </div><!-- card end -->
        </div>
    </div>

@endsection



