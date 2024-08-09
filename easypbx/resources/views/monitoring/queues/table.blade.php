<div class="row">
    <div class="col-sm-12">

        <table class="table table-striped ">
            <thead>
                <tr>

                    <th class="sortable" sort-by="created_at">{{ __('Date/Time') }}</th>
                    <th class="sortable" sort-by="queue_name">{{ __('Name') }}</th>
                    <th>{{ __('Caller ID') }}</th>
                    <th>{{ __('Agent') }}</th>
                    <th>{{ __('Number') }}</th>
                    <th class="sortable" sort-by="duration">{{ __('Call Duration') }}</th>
                    <th class="sortable" sort-by="waiting_duration">{{ __('Waiting Duration') }}</th>
                    <th>{{ __('Record') }}</th>
                    <th class="sortable" sort-by="status">{{ __('Status') }}</th>
                </tr>
            </thead>
            <tbody>
                @inject('Monitoring', 'App\Http\Controllers\MonitoringController')

                @foreach ($calls as $call)
                    <tr>
                        <td>{{ $call->created_at }}</td>
                        <td>{{ $call->queue_name }}</td>
                        <td>{{ $call->call->caller_id }} <i tel="{{ $call->call->caller_id }}" class="fa fa-phone call-now"></i></td>
                        <td> @if($call->bridgeCall) {{ $call->bridgeCall->destination }}  @endif</td>
                        <td>{{ $call->call->destination }}</td>
                        
                        <td>{{  sprintf('%dm %ds', $call->duration / 60, floor($call->duration ) % 60) }}</td>
                        <td>{{  sprintf('%dm %ds', $call->waiting_duration / 60, floor($call->waiting_duration ) % 60) }}</td>

                        <td class="voice-preview">
                            @if($call->record_file)
                            <span class="btn btn-outline-primary btn-sm play" src="{{ url('storage/'. $call->record_file) }}"><i class="fa fa-play"></i></span>
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
