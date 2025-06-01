@extends('layouts.app')



@section('content')
    @include('partials.message')

    <div class="panel panel-default">


        <div class="panel-heading clearfix">

            <div class="pull-left">
                <h4 class="tile-title">{{ __('Voice Files') }}</h4>
            </div>

            <div class="btn-group btn-group-sm pull-right" role="group">
                <a href="{{ route('voice_files.voice_file.create') }}" class="btn btn-primary"
                    title="{{ __('Create New Voice File') }}">
                    <span class="fa fa-plus" aria-hidden="true"></span>{{ __('Create New Voice File') }}
                </a>
            </div>

        </div>


        <div class="panel-body panel-body-with-table">
            

                <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">

                    <div class="row">
                        <div class="col-sm-12 col-md-4">
                            <div class="dataTables_length py-2" id="sampleTable_length">
                                <div class="input-group">
                                    <div class="input-group-prepend"> {!! Form::select('crud_per_page', config('enums.pagination_count'), app('request')->input('per_page'), [
                                        'id' => 'crud_per_page',
                                        'class' => 'form-control form-control-sm ',
                                        'style' => 'width:50px',
                                    ]) !!}

                                    </div>&nbsp;

                                    <!--  {!! Form::select('filter_group', [], null, [
                                        'placeholder' => 'Any Contact Group',
                                        'id' => 'filter_group',
                                        'class' => 'form-control form-control-sm ',
                                    ]) !!}   -->
                                    <input type="search" name="search" id="search"
                                        value="{{ app('request')->input('q') }}"
                                        class="app-search__input form-control form-control-sm"
                                        placeholder="{{ __('Search') }}">


                                    <div class="input-group-append">
                                        <button class="btn btn-sm btn-secondary" type="button" id="btnSearch">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </div>


                        <div
                            class="col-sm-12 col-md-8 text-md-right table-toolbar-right justify-content-sm-start justify-content-md-end">
                            <div class="btn-group btn-group-sm py-2" role="group"
                                aria-label="Button group with nested dropdown">

                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" class="btn btn-outline-secondary"
                                        id="printTable">{{ __('Print') }}</button>
                                    <button type="button" class="btn btn-outline-secondary " data-toggle="modal"
                                        data-target="#bulkActionModal">{{ __('Bulk Actions') }}</button>
                                </div>


                                <div id="sampleTable_filter" class="dataTables_filter btn-group btn-group-sm">

                                    <button id="btnFilter" type="button" class="btn btn-outline-secondary "
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span
                                            data-toggle="tooltip" data-placement="left" title="{{ __('Filter By') }}"><i
                                                class="fa fa-filter"></i></span></button>

                                    <div class="dropdown-menu shadow-dropdown" aria-labelledby="btnFilter">
                                        <a class="dropdown-item" href="{!! route('voice_files.voice_file.index') !!}">{{ __('All') }}</a>
                                        <div class="dropdown-divider"></div>
                                    </div>
                                </div>

                                <button id="btn-refreash" type="button" class="btn btn-outline-secondary "
                                    data-toggle="tooltip" data-placement="top" title="{{ __('Reload') }}"><span><i
                                            class="fa fa-refresh"></i></span></button>

                            </div>

                        </div>

                    </div>

                    <div class="table-responsive">
                    <div id="crud_contents">
                        @include ('voice_files.table', ['voiceFiles' => $voiceFiles])
                    </div>



                </div>
            </div>




        </div>
    </div>


    <!-- Modal for bulk actions-->
    <div class="modal fade" id="bulkActionModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('Bulk Action') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{!! route('voice_files.voice_file.bulk') !!}" class="editableForm" id="massActionFrm"
                        accept-charset="UTF-8">
                        {{ csrf_field() }}
                        <input name="_method" type="hidden" value="PUT">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="form-check-input" type="checkbox" name="mass_delete" id="mass_delete"
                                    value="1">{{ __('Mass Delete') }}
                            </label>
                        </div>
                        <div id="bulk_fields">
                            <hr>
                            <!--<div class="form-group">
                            <label for="user_status">Set status</label>
                            {!! Form::select('status', [], null, ['class' => 'form-control selectpicker']) !!}
                            
                    </div>  -->
                        </div>

                    </form>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                    <button type="button" class="btn btn-primary" id="mass_submit">{{ __('Save changes') }}</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal for add edit forms-->
    <div class="modal fade drawer right-align" id="FormModal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('Create New Voice File') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                    <button type="button" class="btn btn-primary btnSave">{{ __('Save changes') }}</button>
                </div>
            </div>
        </div>
    </div>
    
    

@endsection

@push('script')
    <script src="{{ asset('js/play.js') }}"></script>

    <script>
        $(document).ready(function(){
            $(document).on("click", ".voice-preview .btn-refresh", function (e) {
                e.preventDefault();
                var voice_file_id = $(this).attr('voice_file_id');
                
             
                if(voice_file_id > 0){
                    var path = window.media_play + "?voice_file_id=" + voice_file_id + "&refresh=1"                    
                    $.get(path, function (res) {
                        if (res.status) {
                            showToast("Voice file regenerate successfully");
                        }
                    })
                }
            
            });


            function showToast(message, success = true) {

                let toast = {
                    title: (success) ? "Success" : "Failed",
                    message: message,
                    status: (success) ? TOAST_STATUS.SUCCESS : TOAST_STATUS.DANGER,
                    timeout: 5000
                }

                Toast.create(toast);
            }

        })
    </script>
@endpush
