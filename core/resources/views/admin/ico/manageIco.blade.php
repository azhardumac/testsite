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
                                <th scope="col">@lang('Start date')</th>
                                <th scope="col">@lang('End Date')</th>
                                <th scope="col">@lang('Total')</th>
                                <th scope="col">@lang('Price')</th>
                                <th scope="col">@lang('Unsold')</th>
                                <th scope="col">@lang('Sold')</th>
                                <th scope="col">@lang('Status')</th>
                                <th scope="col">@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($phases as $index => $phase)
                                <tr>
                                    <td data-label="@lang('Start Date')">
                                        {{ showDatetime($phase->start_date) }}
                                    </td>
                                    <td data-label="@lang('End Date')">
                                        {{ showDatetime($phase->end_date) }}
                                    </td>
                                    <td data-label="@lang('Total')">
                                        {{ getAmount($phase->total_coin) }} {{ __($general->coin_symbol) }}
                                    </td>
                                    <td data-label="@lang('Price')">
                                        {{ getAmount($phase->price) }} {{ __($general->cur_text) }}
                                    </td>
                                    <td data-label="@lang('Unsold')">
                                        {{ getAmount($phase->coin_token) }} {{ __($general->coin_symbol) }}
                                    </td>
                                    <td data-label="@lang('Sold')">
                                        {{ getAmount($phase->sold) }} {{ __($general->coin_symbol) }}
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar" style="width: {{ $phase->sold / ($phase->total_coin) * 100 }}%" aria-valuenow="{{ $phase->sold / ($phase->total_coin) * 100 }}" aria-valuemin="0" aria-valuemax="100">{{ number_format($phase->sold / ($phase->total_coin) * 100, 0) }}%</div>
                                        </div>
                                    </td>
                                    <td data-label="@lang('Status')">
                                      @if( $phase->start_date <= Carbon::now() and $phase->end_date >= Carbon::now())
                                        <span class="text-small badge font-weight-normal badge--primary">
                                            @lang('Running')
                                        </span>
                                      @elseif($phase->start_date < Carbon::now() and $phase->end_date < Carbon::now())
                                        <span class="text-small badge font-weight-normal badge--dark">
                                            @lang('Closed')
                                        </span>
                                      @elseif($phase->end_date > Carbon::now() and $phase->start_date > Carbon::now())
                                        <span class="text-small badge font-weight-normal badge--warning">
                                            @lang('Up Comming')
                                        </span>
                                      @endif
                                    </td>
                                    <td data-label="@lang('Action')">

                                        <a href="javascript:void(0)"
                                            data-id="{{ $phase->id }}"
                                            data-stage="{{ $phase->stage }}"
                                            data-price="{{ getAmount($phase->price) }}"
                                            class="icon-btn ml-1 editBtn" data-toggle="tooltip"
                                            data-original-title="@lang('Edit')">
                                            <i class="las la-edit"></i>
                                        </a>

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
                    {{ $phases->links('admin.partials.paginate') }}
                </div>
            </div><!-- card end -->
        </div>
    </div>

    {{-- UPDATE METHOD MODAL --}}
    <div id="editModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Edit Plan')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.ico.edit') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="id">

                            <div class="col-lg-12 form-group">
                                <label for="stage2">@lang('Stage of phase') *</label>
                                <div class="input-group">
                                    <input type="text" required name="stage" value="{{ old('stage') }}" id="stage2" class="form-control">
                                    <div class="input-group-append">
                                      <span class="input-group-text">
                                          @lang('Stage')
                                      </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12 form-group">
                                <label for="coin_token2">@lang('Add coin token to sell') *</label>
                                <div class="input-group">
                                    <input type="text" name="coin_token" placeholder="0" value="{{ old('coin_token') }}" id="coin_token2" class="form-control">
                                    <div class="input-group-append">
                                      <span class="input-group-text">
                                        {{ __($general->coin_symbol) }}
                                      </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 form-group">
                                <label for="price2">@lang('Price') *</label>
                                <div class="input-group">
                                    <input type="text" required name="price" id="price2" value="{{ old('price') }}" class="form-control">
                                    <div class="input-group-append">
                                          <span class="input-group-text">
                                            {{ __($general->cur_text) }}
                                          </span>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Cancel')</button>
                        <button type="submit" class="btn btn--primary">@lang('Update')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ADD METHOD MODAL --}}
    <div id="addModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Add New Plan')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.ico.create') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">

                            <div class="col-lg-12 form-group">
                                <label for="start_date">@lang('Start date') *</label>
                                <div class="input-group">
                                    <input type="text" value="{{ old('start_date') }}" required name="start_date" data-date-format="yyyy-mm-dd" class="datepicker-here form-control" data-language='en' data-position='bottom left' placeholder="Start date" aria-describedby="start_date">
                                    <div class="input-group-append">
                                      <span class="input-group-text" id="start_date">
                                          <i class="fa fa-calendar"></i>
                                      </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12 form-group">
                                <label for="end_date">@lang('End date') *</label>
                                <div class="input-group">
                                    <input type="text" required value="{{ old('end_date') }}" name="end_date" data-date-format="yyyy-mm-dd" class="datepicker-here form-control" data-language='en' data-position='bottom left' placeholder="Start date" aria-describedby="end_date">
                                    <div class="input-group-append">
                                      <span class="input-group-text" id="end_date">
                                          <i class="fa fa-calendar"></i>
                                      </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12 form-group">
                                <label for="stage">@lang('Stage of phase') *</label>
                                <div class="input-group">
                                    <input type="text" required name="stage" value="{{ old('stage') }}" id="stage" class="form-control">
                                    <div class="input-group-append">
                                      <span class="input-group-text">
                                          @lang('Stage')
                                      </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12 form-group">
                                <label for="coin_token">@lang('Coin token (Total quantity to sell)') *</label>
                                <div class="input-group">
                                    <input type="text" required name="coin_token" value="{{ old('coin_token') }}" id="coin_token" class="form-control">
                                    <div class="input-group-append">
                                      <span class="input-group-text">
                                        {{ __($general->coin_symbol) }}
                                      </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 form-group">
                                <label for="price">@lang('Price') *</label>
                                <div class="input-group">
                                    <input type="text" required name="price" id="price" value="{{ old('price') }}" class="form-control">
                                    <div class="input-group-append">
                                      <span class="input-group-text">
                                            {{ __($general->cur_text) }}
                                      </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Cancel')</button>
                        <button type="submit" class="btn btn--primary">@lang('Save')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('breadcrumb-plugins')
    <a class="btn btn-sm btn--primary box--shadow1 text--small addModal" href="javascript:void(0)"><i class="fa fa-fw fa-plus"></i>@lang('Add New Plan')</a>
@endpush


@push('script')
    <script>
        (function($){

            "use strict";

            $('.datepicker-here').datepicker();
            $('.datepicker').css('z-index', 10000);

            $('.editBtn').on('click', function () {
                var modal = $('#editModal');
                modal.find('input[name=id]').val($(this).data('id'));
                modal.find('input[name=stage]').val($(this).data('stage'));
                modal.find('input[name=price]').val($(this).data('price'));
                modal.modal('show');
            });

            $('.removeBtn').on('click', function () {
                var modal = $('#removeModal');
                modal.find('input[name=id]').val($(this).data('id'));
                modal.modal('show');
            });

            $('.addModal').on('click', function () {
                var modal = $('#addModal');
                modal.modal('show');
            });

        })(jQuery);
    </script>
@endpush
