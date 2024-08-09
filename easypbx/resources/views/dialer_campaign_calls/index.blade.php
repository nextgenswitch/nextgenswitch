@extends('layouts.app')

@section('title', __('Campaign call History'))

@section('content')

@include('partials.message')



<div class="panel panel-default">


    <div class="panel-heading clearfix">

        <div class="pull-left">
            <h4 class="tile-title">{{ __('Campaign call history') }} # {{ $campaign->name }}</h4>
        </div>

        <div class="pull-right" role="group">
            <a href="{{ route('dialer_campaigns.dialer_campaign.index') }}" class="btn btn-primary btn-sm">
            <span class="fa fa-list" aria-hidden="true"></span>
            </a>
        </div>

    </div>


    <div class="panel-body panel-body-with-table">


        <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">

            <div class="row">
                <div class="col-sm-12 col-md-6">
                    <div class="dataTables_length py-2" id="sampleTable_length">
                        <div class="input-group">
                            <div class="input-group-prepend"> {!! Form::select('crud_per_page',
                                config('enums.pagination_count'), app('request')->input('per_page'), [
                                'id' => 'crud_per_page',
                                'class' => 'form-control form-control-sm ',
                                ]) !!}

                            </div>&nbsp;

                            {!! Form::select('filter_group', $campaigns, $campaign->id, [
                            'id' => 'filter_group',
                            'data-live-search' => 'true',
                            'class' => 'form-control form-control-sm selectpicker',
                            ]) !!}
                            <input type="search" name="search" id="search" value="{{ app('request')->input('q') }}"
                                class="app-search__input form-control form-control-sm" placeholder="{{ __('search') }}">


                            <div class="input-group-append">
                                <button class="btn btn-sm btn-secondary" type="button" id="btnSearch">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>

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
                            <div class="dropdown-menu shadow-dropdown" aria-labelledby="btnGroupDrop1">
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
                                <a class="dropdown-item"
                                    href="{!! route('campaign_calls.campaign_call.index') !!}?id={{ $campaign->id }}">{{ __('All') }}</a>
                            </div>
                        </div>

                        <button id="btn-refreash" type="button" class="btn btn-outline-secondary " data-toggle="tooltip"
                            data-placement="top" title="{{ __('Reload') }}"><span><i
                                    class="fa fa-refresh"></i></span></button>

                    </div>

                </div>

            </div>
            <div class="table-responsive">
                <div id="crud_contents" cid="{{ $campaign->id }}">
                    @include ('dialer_campaign_calls.table', ['campaignCalls' => $campaignCalls])
                </div>



            </div>
        </div>




    </div>
</div>





<!-- Modal for add edit forms-->
<div class="modal fade drawer right-align" id="FormDataModal" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Form Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">


            </div>
            
        </div>
    </div>
</div>

@endsection

@push('script')


<script type="text/javascript">
$(document).ready(function(){
    $crud = $('#crud_contents').crud();
    
    $("#crud_contents").on("click", '.btn-show-form-data', function(){
        var form_data = JSON.parse($(this).attr('form-data'));
        var table = '<table class="table">';

        $.each(form_data, function(key, value){
            key = key.replaceAll('_', ' ');
            key = capitalizeFirstLetter(key);
            table += "<tr> <th> " + key + "</th> <td>"+ value +" </td></tr>";
        })

        table += '</table>';
        
        console.log(form_data);
        $("#FormDataModal .modal-body").html(table);
        $("#FormDataModal").modal('toggle');
    })

    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

});
</script>
@endpush