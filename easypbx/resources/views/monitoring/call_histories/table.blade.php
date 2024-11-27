<div class="row">
    <div class="col-sm-12">

        <table class="table table-striped ">
            <thead>
                <tr>

                    <th class="sortable" sort-by="created_at">{{ __('Date/Time') }}</th>
                    <th>{{ __('From') }}</th>
                    <th>{{ __('To') }}</th>
                    <th>{{ __('Recieved By') }}</th>
                    <th class="sortable" sort-by="channel">{{ __('Channel') }}</th>
                    <th class="sortable" sort-by="duration">{{ __('Call Duration') }}</th>
                    <th>{{ __('Record') }}</th>
                    <th class="sortable" sort-by="status">{{ __('Status') }}</th>
                </tr>
            </thead>
            <tbody>
                @inject('Monitoring', 'App\Http\Controllers\MonitoringController')

                @foreach ($calls as $call)
                    <tr data-call-id="{{ $call->call_id }}" data-bridge-call-id="{{ $call->bridge_call_id }}">
                        <td>{{ date_time_format($call->created_at) }}</td>
                        <td>{{ $call->bridgeCall->caller_id }}</td>
                       
                        <!-- <td> {{ $call->call->destination }} <i tel="{{ $call->call->destination }}" class="fa fa-phone call-now"></i></td> -->

                        <td> @include('contacts.call_sms_popup', ['tel_no' => $call->bridgeCall->destination]) </td>

                        <td>@if($call->bridgeCall) {{ $call->bridgeCall->destination }}  @endif</td>
                        <td>@if($call->bridgeCall) <span title="{{ $call->bridgeCall->channel }}">{{ Str::limit($call->bridgeCall->channel, 10, '...') }}</span>   @endif</td>
                        <td>{{  sprintf('%dm %ds', $call->duration / 60, floor($call->duration ) % 60) }}</td>
                        
                        <td class="voice-preview">
                            @if(!empty($call->record_file))
                            <span class="btn btn-outline-primary btn-sm play" src="{{ url('storage/' . $call->record_file ) }}"><i class="fa fa-play"></i></span>
                            <span class="btn btn-outline-primary btn-sm stop d-none"><i class="fa fa-stop"></i></span>
                            @endif
                        </td>

                        <td> {{ $call->status->getText() }}</td>
                        
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>
<div class="row">
    {!! $calls->render() !!}
</div>
