@extends('admin.layouts.app')

@section('panel')

    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body">
                    @if($exists)
                        <iframe src="{{ asset($pdf) }}" style="width:100%; min-height: 700px !important;" frameborder="0"></iframe>
                    @else
                        <p class="text-center">@lang('There is no data pdf')</p>
                    @endif
                </div>
            </div>
        </div>
    </div>


    {{-- UPDATE METHOD MODAL --}}
    <div id="editModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Update White Paper - PDF (Max 5 MB)')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.whitePaper.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">

                            <div class="col-lg-12 form-group">
                                {{-- <label for="pdf">@lang('PDF-FILE') *</label> --}}
                                <input type="file" accept=".pdf, .PDF" required name="pdf" id="pdf2" class="form-control-file">
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary w-100">@lang('Save')</button>
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
                    <h5 class="modal-title">@lang('White Paper - PDF (Max 5 MB)')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.whitePaper.create') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">

                            <div class="col-lg-12 form-group">
                                {{-- <label for="pdf">@lang('PDF-FILE') *</label> --}}
                                <input type="file" accept=".pdf, .PDF" required name="pdf" id="pdf" class="form-control-file">
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary w-100">@lang('Save')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection



@push('breadcrumb-plugins')
    @if($exists)
        <a class="btn btn-sm btn--primary box--shadow1 text--small" href="{{ asset($pdf) }}" download><i class="fa fa-file-pdf"></i>@lang('Download PDF')</a>
        <a class="btn btn-sm btn--primary box--shadow1 text--small editBtn" href="javascript:"><i class="fa fa-edit"></i>@lang('Updte')</a>
    @else
        <a class="btn btn-sm btn--primary box--shadow1 text--small addModal" href="javascript:void(0)"><i class="fa fa-fw fa-plus"></i>@lang('Add White Paper')</a>
    @endif
@endpush

@push('script')
    <script>
        $(function () {

            "use strict";

            $('.editBtn').on('click', function () {
                var modal = $('#editModal');
                modal.modal('show');
            });

            // $('.removeBtn').on('click', function () {
            //     var modal = $('#removeModal');
            //     modal.modal('show');
            // });

            $('.addModal').on('click', function () {
                var modal = $('#addModal');
                modal.modal('show');
            });


        });
    </script>
@endpush
