@extends('layouts.app')



@section('content')
    @include('partials.message')



    <div class="panel panel-default">


        <div class="panel-heading clearfix">

            <div class="pull-left">
                <h4 class="tile-title">{{ __('Api Access Logs') }}</h4>
            </div>

            <div class="btn-group btn-group-sm pull-right" role="group">
                <a href="{{ route('apis.api.index') }}" class="btn btn-primary" title="{{ __('show all apis') }}">
                    <span class="fa fa-list" aria-hidden="true"></span>
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
                                    'style' => 'width:70px',
                                ]) !!}

                                </div>&nbsp;

                                <!--  {!! Form::select('filter_group', [], null, [
                                    'placeholder' => 'Any Contact Group',
                                    'id' => 'filter_group',
                                    'class' => 'form-control form-control-sm ',
                                ]) !!}   -->
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
                            <button id="btn-refreash" type="button" class="btn btn-outline-secondary "
                                data-toggle="tooltip" data-placement="top" title="{{ __('Reload') }}"><span><i
                                        class="fa fa-refresh"></i></span></button>

                        </div>

                    </div>

                </div>
                <div class="table-responsive">
                    <div id="crud_contents">
                        @include ('apis.logs.table', ['logs' => $logs])
                    </div>
                </div>
            </div>




        </div>
    </div>



   
@endsection


@push('script')
    <script src="{{ asset('js/index.js') }}"></script>
@endpush