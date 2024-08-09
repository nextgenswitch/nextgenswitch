@extends('layouts.app')



@section('content')
    @include('partials.message')



    <div class="panel panel-default">


        <div class="panel-heading clearfix">

            <div class="pull-left">
                <h4 class="tile-title">{{ __('Custom Function') }}</h4>
            </div>



            <div class="btn-group btn-group-sm pull-right" role="group">

                <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="#"
                    class="btn btn-primary dropdown-toggle" title="{{ __('Create New Custom Func') }}">
                    <span class="fa fa-plus" aria-hidden="true"></span>{{ __('Create New Custom Function') }}
                </a>
                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                    <a class="dropdown-item"
                        href="{{ route('custom_funcs.custom_func.create', 0) }}">{{ __('Create Url Function') }}</a>
                    <a class="dropdown-item"
                        href="{{ route('custom_funcs.custom_func.create', 1) }}">{{ __('Create Xml Function') }}</a>
                    <a class="dropdown-item"
                        href="{{ route('custom_funcs.custom_func.create', 2) }}">{{ __('Create Php Function') }}</a>
                </div>
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

                                <input type="search" name="search" id="search" value="{{ app('request')->input('q') }}"
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

                                <div class="dropdown-menu dropdown-menu-right shadow-dropdown" aria-labelledby="btnFilter">
                                    <a class="dropdown-item" href="{!! route('custom_funcs.custom_func.index') !!}">{{ __('All') }}</a>
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
                        @include ('custom_funcs.table', ['customFuncs' => $customFuncs])
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
                    <form method="POST" action="{!! route('custom_funcs.custom_func.bulk') !!}" class="editableForm" id="massActionFrm"
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
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('Create New Custom Func') }}</h5>
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
    <script src="{{ asset('js/index.js') }}"></script>
@endpush
