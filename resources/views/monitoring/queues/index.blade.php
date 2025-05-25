@extends('layouts.app')

@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('js/flatpickr/flatpickr.min.css') }}">
@endpush

@section('content')
    @if (Session::has('success_message'))
        <div class="alert alert-success">
            <span class="glyphicon glyphicon-ok"></span>
            {!! session('success_message') !!}

            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>

        </div>
    @endif



    <div class="panel panel-default">


        <div class="panel-heading clearfix">

            <div class="pull-left">
                <h4 class="tile-title">{{ __('Queue call list') }}</h4>
            </div>

        </div>


        <div class="panel-body panel-body-with-table">
            {{-- <div class="table-responsive"> --}}

            <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                <div class="dataTables_length py-2" id="sampleTable_length">
                    <div class="row">
                        <div class="col-sm-12 col-md-8">
                            @include('monitoring.search', ['type' => 'callQueue'])
                        </div>


                        <div
                            class="col-sm-12 col-md-4 text-md-right table-toolbar-right justify-content-sm-start justify-content-md-end">
                            <div class="btn-group btn-group-sm py-2" role="group"
                                aria-label="Button group with nested dropdown">

                                <div class="btn-group btn-group-sm" role="group">
                                    <button id="btnGroupDrop1" type="button"
                                        class="btn  dropdown-toggle btn-outline-secondary" data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        {{ __('Export') }}
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right shadow-dropdown" aria-labelledby="btnGroupDrop1">
                                        <a class="dropdown-item" href="#" id="csvD">{{ __('CSV') }}</a>
                                        <a class="dropdown-item" href="#" id="printTable">{{ __('Print') }}</a>
                                    </div>

                                </div>


                                <div id="sampleTable_filter" class="dataTables_filter btn-group btn-group-sm">

                                    <button id="btnFilter" type="button" class="btn btn-outline-secondary "
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span
                                            data-toggle="tooltip" data-placement="left" title="{{ __('Filter By') }}"><i
                                                class="fa fa-filter"></i></span></button>

                                    <div class="dropdown-menu shadow-dropdown dropdown-menu-right" aria-labelledby="btnFilter">
                                        <a class="dropdown-item" href="{!! route('monitoring.queue.call') !!}">{{ __('All') }}</a>
                                        <div class="dropdown-divider"></div>
                                        @foreach ( $statuses as $k => $status)
                                            <a class="dropdown-item"
                                                href="{!! route('monitoring.queue.call') !!}?filter=status:{{ $k }}">{{ __($status) }}</a>
                                        @endforeach
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>
                </div>

                <div class="table-responsive">
                    <div id="crud_contents">
                        @include('monitoring.queues.table')
                    </div>
                </div>


            </div>
            {{-- </div> --}}




        </div>
    </div>
@endsection


@push('script')
<script src="{{ asset('js/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('js/play.js') }}"></script>
@endpush
