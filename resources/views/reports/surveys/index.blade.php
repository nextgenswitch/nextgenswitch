@extends('layouts.app')



@section('content')
    @include('partials.message')


    <div class="panel panel-default">


        <div class="panel-heading clearfix">

            <div class="pull-left">
                <h4 class="tile-title">{{ __('Survey Results') }}</h4>
            </div>

        </div>


        <div class="panel-body panel-body-with-table">


            <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">

                <div class="row">
                    <div class="col-sm-12 col-md-6">
                        <div class="dataTables_length py-2" id="sampleTable_length">
                            <div class="input-group">
                                <div class="input-group-prepend"> {!! Form::select('crud_per_page', config('enums.pagination_count'), app('request')->input('per_page'), [
                                    'id' => 'crud_per_page',
                                    'class' => 'form-control form-control-sm ',
                                ]) !!}

                                </div>&nbsp;

                                {!! Form::select('filter_group', $surveys, $survey->id, [
                                    'id' => 'filter_group',
                                    'data-live-search' => 'true',
                                    'class' => 'form-control form-control-sm selectpicker',
                                ]) !!}

                                

                            </div>
                        </div>
                    </div>


                    <div
                        class="col-sm-12 col-md-6 text-md-right table-toolbar-right justify-content-sm-start justify-content-md-end">
                        <div class="btn-group btn-group-sm py-2" role="group"
                            aria-label="Button group with nested dropdown">

                            <div class="btn-group btn-group-sm" role="group">
                                <button id="btnGroupDrop1" type="button" class="btn  dropdown-toggle btn-outline-secondary"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{ __('Export') }}
                                </button>
                                <div class="dropdown-menu dropdown-menu-right shadow-dropdown" aria-labelledby="btnGroupDrop1">
                                    <a class="dropdown-item" href="#" id="csvD">{{ __('CSV') }}</a>
                                    <a class="dropdown-item" href="#" id="printTable">{{ __('Print') }}</a>
                                </div>

                                <a href="{{ route('monitoring.surveys', ['survey_id' => $survey->id, 'clear' => '1']) }}" class="btn btn-outline-secondary" id="clearSurvey">
                                    <i class="fa fa-times"></i>
                                    {{ __('Clear') }}
                                </a>

                            </div>

                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <div id="crud_contents">
                        @include('reports.surveys.table')
                    </div>
                </div>

            </div>





        </div>
    </div>
    @include('contacts.sms_modal')
@endsection

@push('script')
    <script src="{{ asset('js/play.js') }}"></script>

    
    <script>
        $crud = $('#crud_contents').crud();

        $(document).ready(function(){
            $("#clearSurvey").click(function(e){
                if (confirm("Are you sure you want to clear all survey results?") == false) {
                    e.preventDefault();
                }
            });           
            

            $('#filter_group').change(function() {
                window.location.href = "{!! route('monitoring.surveys') !!}/?survey_id=" + $(this).val(); 
            });
        })
    </script>
@endpush
