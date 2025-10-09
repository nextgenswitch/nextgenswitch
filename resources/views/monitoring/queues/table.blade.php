<div class="row">
    <div class="col-sm-12">

        <table class="table table-striped ">
            <thead>
                <tr>
                    <th></th>
                    <th class="sortable" sort-by="created_at">{{ __('Date/Time') }}</th>
                    <th class="sortable" sort-by="queue_name">{{ __('Name') }}</th>
                    <th>{{ __('Caller ID') }}</th>
                    <th>{{ __('Agent') }}</th>
                    <th>{{ __('Number') }}</th>
                    <th class="sortable" sort-by="duration">{{ __('Call Duration') }}</th>
                    <th class="sortable" sort-by="waiting_duration">{{ __('Waiting Duration') }}</th>
                   
                    <th class="sortable" sort-by="status">{{ __('Status') }}</th>
                    <th>{{ __('Record') }}</th>
                </tr>
            </thead>
            <tbody>
                @inject('Monitoring', 'App\Http\Controllers\MonitoringController')

                @foreach ($calls as $call)
                    <tr>
                        <td>   @if($call->status->value < 2)
                        <i data-feather="phone-incoming" style="width:24px;height:24px;" title="Incoming" class="{{ $call->status->getCss() }}"></i> 
                        @elseif($call->status->value < 4)
                        <i data-feather="phone-call" style="width:24px;height:24px;" title="Incoming" class="{{ $call->status->getCss() }}"></i> 
                        @else
                        <i data-feather="phone-missed" style="width:24px;height:24px;" title="Incoming" class="{{ $call->status->getCss() }}"></i> 
                        @endif  </td>
                        <td>
                       
                        {{ date_time_format($call->created_at) }}</td>
                        <td>{{ $call->queue_name }}</td>
                        <td qcall="{{ $call->call_id }}" call="{{ $call->call->id }}">{{ $call->call->caller_id }} </td>
                        <td> @if($call->bridgeCall) {{ $call->bridgeCall->destination }}  @endif</td>
                        <td>{{ $call->call->destination }}</td>
                        
                        <td>{{  sprintf('%dm %ds', $call->duration / 60, floor($call->duration ) % 60) }}</td>
                        <td>{{  sprintf('%dm %ds', $call->waiting_duration / 60, floor($call->waiting_duration ) % 60) }}</td>

                       

                        <td> 
                      
                        {{ $call->status->getText() }}

                        
                        </td>
                        <td class="voice-preview">
                            @if($call->record_file)
                            <span class="btn btn-outline-primary btn-sm play" src="{{ url('storage/'. $call->record_file) }}"><i class="fa fa-play"></i></span>
                            <span class="btn btn-outline-primary btn-sm stop d-none"><i class="fa fa-stop"></i></span>
                            @endif
                        </td>
                        
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>
<div class="row">
    {!! $calls->render() !!}
</div>
