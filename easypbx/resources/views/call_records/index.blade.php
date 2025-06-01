@extends('layouts.app')



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
                <h4 class="tile-title">{{ __('Call Records') }}</h4>
            </div>

            <div class="btn-group btn-group-sm pull-right" role="group">

                <a href="{{ route('monitoring.log.call') }}" class="btn btn-primary" title="{{ __('Show Call Logs') }}">
                    <span class="fa fa-list" aria-hidden="true"></span>
                </a>

            </div>

        </div>


        <div class="panel-body panel-body-with-table">
            <div class="table-responsive">

                <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">

                    <div class="row">
                        <div class="col-sm-12 col-md-4">
                            <div class="dataTables_length py-2" id="sampleTable_length">
                                <div class="input-group">
                                    <div class="input-group-prepend"> {!! Form::select('crud_per_page', config('enums.pagination_count'), app('request')->input('per_page'), [
                                        'id' => 'crud_per_page',
                                        'class' => 'form-control form-control-sm ',
                                        'style' => 'width:90px',
                                    ]) !!}

                                    </div>&nbsp;
                                </div>
                            </div>
                        </div>


                        <div
                            class="col-sm-12 col-md-8 text-md-right table-toolbar-right justify-content-sm-start justify-content-md-end">
                            <div class="btn-group btn-group-sm py-2" role="group"
                                aria-label="Button group with nested dropdown">

                                <div class="btn-group btn-group-sm" role="group">
                                    <button id="btnGroupDrop1" type="button"
                                        class="btn  dropdown-toggle btn-outline-secondary" data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        {{ __('Export') }}
                                    </button>
                                    <div class="dropdown-menu shadow-dropdown" aria-labelledby="btnGroupDrop1">
                                        <a class="dropdown-item" href="#" id="csvD">{{ __('CSV') }}</a>
                                        <a class="dropdown-item" href="#" id="printTable">{{ __('Print') }}</a>
                                    </div>
                                     
                                </div>


                        

                            </div>

                        </div>

                    </div>

                    <div id="crud_contents">
                        @include('call_records.table', ['callRecords' => $callRecords])
                    </div>

                
                </div>
            </div>




        </div>
    </div>
@endsection


@push('script')
    
    <script src="{{ asset('js/play.js') }}"></script>
    <script>
        $(function() {

            $('#filter_group').change(function(){
                window.location.href = "{{URL::to('/panel/monitoring/queue-call?filter=status:')}}" + $(this).val();
            });
            
        })
    </script>
    
@endpush


