@extends('layouts.app')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('js/flatpickr/flatpickr.min.css') }}">
@endpush

@section('content')
    @include('partials.message')



    <div class="panel panel-default">


        <div class="panel-heading clearfix">

            <div class="pull-left">
                <h4 class="tile-title">{{ __('All Voice Mails') }}</h4>
            </div>

        </div>


        <div class="panel-body panel-body-with-table">
            <div class="table-responsive">

                <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">

                    <div class="row">
                        <div class="col-sm-12 col-md-8">
                            @include('monitoring.search', ['type' => 'voice_mail'])
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
                                    <div class="dropdown-menu shadow-dropdown dropdown-menu-right" aria-labelledby="btnGroupDrop1">
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
                                        <a class="dropdown-item" href="{!! route('monitoring.voice_mails.index') !!}">{{ __('All') }}</a>
                                        
                                    </div>
                                </div>

                                

                            </div>

                        </div>

                    </div>

                    <div id="crud_contents">
                        @include('monitoring.voice_mails.table')
                    </div>

                
                </div>
            </div>




        </div>
    </div>
@endsection


@push('script')
<script src="{{ asset('js/flatpickr/flatpickr.js') }}"></script>
<script src="{{ asset('js/play.js') }}"></script>
    <script>
        $(function() {

            $('#filter_group').change(function(){
                window.location.href = "{{URL::to('/panel/monitoring/voice-mails?filter=status:')}}" + $(this).val();
            });
            
        })
    </script>
@endpush