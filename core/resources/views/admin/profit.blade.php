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
                                <th scope="col">@lang('Trx')</th>
                                <th scope="col">@lang('Charge')</th>
                                <th scope="col">@lang('From')</th>
                                <th scope="col">@lang('Method Currency')</th>
                                <th scope="col">@lang('Username')</th>
                                <th scope="col">@lang('Date')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($charges as $index => $charge)
                                <tr>
                                    <td data-label="@lang('Trx')">
                                        {{ $charge['trx'] }}
                                    </td>

                                    <td data-label="@lang('Charge')">
                                        {{ getAmount($charge['charge']) }} {{ __($general->cur_text)}}
                                    </td>
                                    <td data-label="@lang('From')">
                                        {{ __($charge['type']) }}
                                    </td>
                                    <td data-label="@lang('Method Currency')">
                                        {{ __($charge['method']) }}
                                    </td>
                                    <td data-label="@lang('Username')">
                                        <a href="{{ route('admin.users.detail', $charge['user_id']) }}">
                                           {{ $charge['username'] }}
                                        </a>
                                    </td>
                                    <td data-label="@lang('Date')">
                                        {{ showDateTime($charge['time']) }}
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

                {{ $charges->links('admin.partials.paginate') }}

                </div>
            </div><!-- card end -->
        </div>
    </div>


@endsection

