@extends('layouts.app')

@section('title', __('Campaign call History'))

@section('content')

@include('partials.message')



<div class="panel panel-default">


    <div class="panel-heading clearfix">

        <div class="pull-left">
            <h4 class="tile-title">{{ __('Broadcast history') }} # {{ $campaign->name }}</h4>
        </div>

        <div class="pull-right" role="group">
            <a href="{{ route('broadcasts.broadcast.index') }}" class="btn btn-primary btn-sm">
            <span class="fa fa-list" aria-hidden="true"></span>
            </a>
            @if ($campaign->status == 0 || $campaign->status == 2)
            <a href="{{ route('broadcasts.broadcast.updateField', $campaign->id) }}" cid="{{$campaign->id}}" class="btn btn-primary btn-sm btnStatus"
                data-status="1">
                <span class="fa fa-play" aria-hidden="true"></span>{{ __('Start') }}
            </a>
            @elseif($campaign->status == 1)

            <button type="button" class="btn btn-primary btn-sm mr-2 campaignLog">
                <i class="fa fa-tasks"> Logs </i>
            </button>

            <a href="{{ route('broadcasts.broadcast.updateField', $campaign->id) }}" class="btn btn-primary btn-sm btnStatus"
                title="{{ __('Campaign stop') }}" data-status="2">
                <span class="fa fa-stop" aria-hidden="true"></span>{{ __('Stop') }}
            </a>
            @endif
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
                    @include ('campaign_calls.table', ['campaignCalls' => $campaignCalls])
                </div>



            </div>
        </div>




    </div>
</div>

@include('broadcasts.wslog')

<!-- Modal for bulk actions-->
<div class="modal fade" id="bulkActionModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Bulk Action</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{!! route('campaign_calls.campaign_call.bulk', $campaign->id) !!}"
                    class="editableForm" id="massActionFrm" accept-charset="UTF-8">
                    {{ csrf_field() }}
                    <input name="_method" type="hidden" value="PUT">
                    <div class="form-check">
                        <label class="form-check-label">
                            <input class="form-check-input" type="checkbox" name="mass_delete" id="mass_delete"
                                value="1">Mass Delete
                        </label>
                    </div>
                    <div id="bulk_fields"></div>

                </form>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="mass_submit">Save changes</button>
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
                <h5 class="modal-title" id="exampleModalLabel">Create New Campaign Call</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary btnSave">Save changes</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('script')


<script type="text/javascript">
$(document).ready(function(){
    
    $('#menu-monitoring').addClass('is-expanded');

    $('#crud_contents').crud();

    $('#filter_group').change(function() {
        window.location.href = "{!! route('campaign_calls.campaign_call.index') !!}?id=" + $(this).val();

    });

    $('.btnStatus').click(function(e) {
        e.preventDefault(); // avoid to execute the actual submit of the form. 
        if (confirm("Are you sure ?") == false) return;
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var actionUrl = $(this).attr('href');
        var status = $(this).attr('data-status');

        $.ajax({
            type: "PUT",
            url: actionUrl,
            data: {
                _token: CSRF_TOKEN,
                'status': status
            }, // serializes the form's elements.
            success: function(data) {
                console.log(data); // show response from the php script.
                location.reload();
            }
        });
    });
});
</script>
@endpush