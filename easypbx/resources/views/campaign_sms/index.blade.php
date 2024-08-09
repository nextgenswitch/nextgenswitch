@extends('layouts.app')

@section('title', __('Campaign sms histroy'))

@section('content')

    @include('partials.message')



    <div class="panel panel-default">


        <div class="panel-heading clearfix">

            <div class="pull-left">
                <h4 class="tile-title">{{ __('Campaign sms histories') }} # {{ $campaign->name }}</h4>
            </div>

            <div class="pull-right" role="group">
            <a href="{{ route('campaigns.campaign.index') }}" class="btn btn-primary btn-sm">
            <span class="fa fa-list" aria-hidden="true"></span>
            </a>
            @if ($campaign->status == 0 || $campaign->status == 2)
            <a href="{{ route('campaigns.campaign.updateField', $campaign->id) }}" cid="{{$campaign->id}}" class="btn btn-primary btn-sm btnStatus"
                data-status="1">
                <span class="fa fa-play" aria-hidden="true"></span>{{ __('Start') }}
            </a>
            @elseif($campaign->status == 1)

            <button type="button" class="btn btn-primary btn-sm mr-2 campaignLog">
                <i class="fa fa-tasks"> Logs </i>
            </button>

            <a href="{{ route('campaigns.campaign.updateField', $campaign->id) }}" class="btn btn-primary btn-sm btnStatus"
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
                                <div class="input-group-prepend"> {!! Form::select('crud_per_page', config('enums.pagination_count'), app('request')->input('per_page'), [
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
                                    class="app-search__input form-control form-control-sm"
                                    placeholder="{{ __('search') }}">


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
                                    <a class="dropdown-item" href="{!! route('campaign_sms.campaign_sms.index', $campaign->id) !!}">{{ __('All') }}</a>
                                </div>
                            </div>

                            <button id="btn-refreash" type="button" class="btn btn-outline-secondary "
                                data-toggle="tooltip" data-placement="top" title="{{ __('Reload') }}"><span><i
                                        class="fa fa-refresh"></i></span></button>

                        </div>

                    </div>

                </div>

                <div class="table-responsive">
                    <div id="crud_contents" cid="{{ $campaign->id }}">
                        @include ('campaign_sms.table', ['campaignSms' => $campaignSms])
                    </div>



                </div>
            </div>




        </div>
    </div>


    @include('campaigns.wslog')


@endsection

@push('script')
    <script src="{{ asset('js/index.js?v=' . rand()) }}"></script>

    <script type="text/javascript">
        $(function() {

            $('#filter_group').change(function() {
                window.location.href = "{{ URL::to('/admin/campaign_calls') }}/" + $(this).val();
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
