@extends('layouts.app')



@section('content')
    @include('partials.message')


    <div class="panel panel-default">


        <div class="panel-heading clearfix">

            <div class="pull-left">
                <h4 class="tile-title">{{ __('Active call list') }}</h4>
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
                                <button id="btnGroupDrop1" type="button" class="btn  dropdown-toggle btn-outline-secondary"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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

                                <div class="dropdown-menu dropdown-menu-right shadow-dropdown" aria-labelledby="btnFilter">
                                    <a class="dropdown-item" href="{!! route('monitoring.active.call') !!}">{{ __('All') }}</a>
                                    <div class="dropdown-divider"></div>
                                
                                    @foreach ($statuses as $k => $status)
                                        <a class="dropdown-item"
                                            href="{!! route('monitoring.active.call') !!}?filter=status:{{ $k }}">{{ $status }}</a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <div id="crud_contents">
                        @include('monitoring.active_calls.table')
                    </div>
                </div>

            </div>





        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('js/index.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('.btnhangup').click(function(){
                console.log($(this).closest('tr').attr('data-id'))
                var call_id = $(this).closest('tr').attr('data-id');
                $.get("{{ route('dialer.hangup') }}?call_id=" + call_id  , function(data, status){
                    document.location.reload();
                });   
            })

            $(".preview-audio").click(function(e) {
                e.preventDefault();
                let audio = $(this).attr('data-src');
                console.log(audio)

                $("#audio-src").attr('src', audio)
                $("#audio-player").trigger('load');

                $('#previdw-audio-model').modal('toggle')
            })


            $('#previdw-audio-model').on('hidden.bs.modal', function() {
                $("#audio-player").trigger('pause');
                $("#audio-player").prop("currentTime", 0);
            });


            $('#filter_group').change(function() {
                window.location.href = "{{ URL::to('/panel/monitoring/active-call?filter=status:') }}" + $(
                    this).val();
            });

        })
    </script>
@endpush
