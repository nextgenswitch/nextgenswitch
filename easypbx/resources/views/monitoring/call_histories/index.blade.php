@extends('layouts.app')

@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('js/flatpickr/flatpickr.min.css') }}">
    <style>
        .conversation {
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 16px;
            background-color: #f9f9f9;
        }
        .customer,
        .agent {
        padding: 12px 16px;
        margin: 10px 0;
        border-radius: 8px;
        }

        .customer {
        background-color: #e0f7fa;
        color: #006064;
        text-align: left;
        }

        .agent {
        background-color: #e8f5e9;
        color: #2e7d32;
        text-align: right;
        }

    </style>
@endpush


@section('content')

@include('partials.message')

    <div class="panel panel-default">


        <div class="panel-heading clearfix">

            <div class="pull-left">
                <h4 class="tile-title">{{ __('Bridge Calls') }}</h4>
            </div>

        </div>


        <div class="panel-body panel-body-with-table">
            {{-- <div class="table-responsive"> --}}

            <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                <div class="dataTables_length py-2" id="sampleTable_length">
                    <div class="row">
                        <div class="col-sm-12 col-md-8">
                            @include('monitoring.search', ['type' => 'callHistory'])
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
                                        <a class="dropdown-item" href="{!! route('monitoring.call.history') !!}">{{ __('All') }}</a>
                                        <div class="dropdown-divider"></div>
                                        @foreach ( $statuses as $k => $status)
                                            <a class="dropdown-item"
                                                href="{!! route('monitoring.call.history') !!}?filter=status:{{ $k }}">{{ __($status) }}</a>
                                        @endforeach
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>
                </div>

                <div class="table-responsive">
                    <div id="crud_contents">
                        @include('monitoring.call_histories.table')
                    </div>
                </div>


            </div>
            {{-- </div> --}}




        </div>
    </div>

    @include('contacts.sms_modal')

    <!-- Modal for add/edit forms -->
    <div class="modal fade" id="summarizeAlertModal" tabindex="-1" role="dialog" aria-labelledby="summarizeAlertModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="summarizeAlertModalLabel">{{ __('Summarize Conversation') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                

                <div class="modal-body">
                    <p>{{ __('Are you sure you want to summarize this conversation or view only the Speech-to-Text of the voice conversation?') }}</p>
                </div>
                <div id="summarize_loader" class="overlay-spinner text-center mt-4" style="display: none">
                    <div class="spinner-grow text-primary"><span class="sr-only">Loading...</span></div>
                    <div class="spinner-grow text-secondary"><span class="sr-only">Loading...</span></div>
                    <div class="spinner-grow text-success"><span class="sr-only">Loading...</span></div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" stt_only="1" class="btn btn-secondary btnConSummary">{{ __('Speech-to-Text Only') }}</button>
                    <button type="button" stt_only="0" class="btn btn-primary btnConSummary">{{ __('Conversation Summary') }}</button>
                </div>
            </div>
        </div>
    </div>

@endsection


@push('script')
    <script src="{{ asset('js/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('js/play.js') }}"></script>

    <script>
        $(document).ready(function() {
            var record_file = '';

            $(".btn-summarize-conversation").on('click', function() {
                record_file = $(this).attr('record_file');
                $("#summarizeAlertModal .modal-body").html("{{ __('Are you sure you want to summarize this conversation or view only the Speech-to-Text of the voice conversation?') }}");
                $('#summarizeAlertModal').modal('show');
            });

            $(".btnConSummary").on('click', function() {
                console.log(record_file);
                var stt_only = $(this).attr('stt_only');
                $("#summarize_loader").show(); // Show loader

                $.ajax({
                    url: "{{ route('monitoring.conversation.summarize') }}",
                    type: "POST",
                    data: {
                        record_file: record_file,
                        stt_only: stt_only,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        console.log(response);
                        // Handle success response

                        var title = stt_only == 1 ? "{{ __('Speech-to-Text') }}" : "{{ __('Conversation Summary') }}";
                        $('#summarizeAlertModal .modal-title').html(title);

                        $('#summarizeAlertModal .modal-body').html(response.text);
                        $("#summarize_loader").hide(); // Hide loader
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        // Handle error response
                        $("#summarize_loader").hide(); // Hide loader
                    }
                });

            });

           
        });
    </script>
@endpush
