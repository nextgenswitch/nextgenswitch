@extends('layouts.app')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('js/flatpickr/flatpickr.min.css') }}">
@endpush


@section('content')
@include('partials.message')


    <div class="panel panel-default">


        <div class="panel-heading clearfix">

            <div class="pull-left">
                <h4 class="tile-title">{{ __('SMS Histories') }}</h4>
            </div>

        </div>


        <div class="panel-body panel-body-with-table">


            <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">

                <div class="dataTables_length py-2" id="sampleTable_length">
                    <div class="row">
                        <div class="col-sm-12 col-md-8">
                            <form action="" id="searchForm">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        {!! Form::select('crud_per_page', config('enums.pagination_count'), app('request')->input('per_page'), [
                                            'id' => 'crud_per_page',
                                            'class' => 'form-control form-control-sm ',
                                            'style' => 'width:90px',
                                        ]) !!}
                                    </div>

                                    <input type="search" name="to" id="to" value="{{ app('request')->input('to') }}"
                                            class="form-control app-search__input form-control-sm" placeholder="{{ __('To') }}">
                            
                                    <input type="text" name="date" id="date" value="{{ app('request')->input('date') }}"
                                        class="form-control app-search__input form-control-sm" placeholder="{{ __('date') }}">
                            
                            
                                    <div class="input-group-append">
                                        <button class="btn btn-sm btn-secondary" type="submit">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </div>
                            
                                </div>
                            
                            </form>
                            
                            

                            
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
                                    <div class="dropdown-menu shadow-dropdown dropdown-menu-right aria-labelledby="btnGroupDrop1">
                                        <a class="dropdown-item" href="#" id="csvD">{{ __('CSV') }}</a>
                                        <a class="dropdown-item" href="#" id="printTable">{{ __('Print') }}</a>
                                    </div>

                                </div>


                                <div id="sampleTable_filter" class="dataTables_filter btn-group btn-group-sm">

                                    <button id="btnFilter" type="button" class="btn btn-outline-secondary "
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span
                                            data-toggle="tooltip" data-placement="left" title="{{ __('Filter By') }}"><i
                                                class="fa fa-filter"></i></span></button>

                                    <div class="dropdown-menu dropdown-menu-right shadow-dropdown" aria-labelledby="btnFilter">
                                        <a class="dropdown-item" href="{!! route('monitoring.log.call') !!}">{{ __('All') }}</a>
                                        <div class="dropdown-divider"></div>
                                        {{-- @foreach ($statuses as $k => $status)
                                            <a class="dropdown-item"
                                                href="{!! route('monitoring.log.call') !!}?filter=status:{{ $k }}">{{ __($status) }}</a>
                                        @endforeach --}}
                                        </a>

                                    </div>
                                </div>



                            </div>
                        </div>
                    </div>


                    <div class="table-responsive">
                        <div id="crud_contents">
                            @include('monitoring.sms_histories.table')
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
<script src="{{ asset('js/flatpickr/flatpickr.js') }}"></script>
    <script>
        $(document).ready(function() {
            $crud = $('#crud_contents').crud();

            $("#date").flatpickr({
                dateFormat: "Y-m-d",
            });

            $("#searchForm").submit((e) => {
                e.preventDefault();
                var q = '';

                var to = $("#to").val();
                var date = $("#date").val();
                
                if (to !== undefined && to.length > 0) {
                    q += 'name:' + to + ',';
                }

                if (date !== undefined && date.length > 0) {
                    q += 'date:' + date + ',';
                }

                console.log(q)

                $crud.setUrlParam('q', q);
                $crud.reload_data()

                console.log('submitted');
            })

            var q = '{{ app('request')->input('q') }}';

            if (q != '') {
                var qs = q.split(',');
                qs.forEach((item) => {
                    console.log(item)
                    qarr = item.split(':')
                    $("#" + qarr[0]).val(qarr[1]);
                })
            }


        })
    </script>

@endpush
