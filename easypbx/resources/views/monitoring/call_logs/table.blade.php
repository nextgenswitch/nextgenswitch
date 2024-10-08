
<div class="row">
    <div class="col-sm-12">

        <table class="table table-striped   " data-resizable="true">
            <thead>
                <tr>

                    <th class="sortable" sort-by="destination">{{ __('Destination') }}</th>
                    <th class="sortable" sort-by="caller_id">{{ __('Caller ID') }}</th>
                    <th>{{ __('Channel') }}</th>
                    <th class="sortable" sort-by="connect_time">Date/Time</th>
                    
                    <th class="sortable" sort-by="duration">{{ __('Duration') }}</th>
                    <th>{{ __('Type') }}</th>
                    <th class="sortable" sort-by="status">{{ __('Status') }}</th>
                    <th>{{ __('Record') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($calls as $call)
                    <tr data-id="{{ $call->id }}">
                        
                        <td>{{ $call->destination }} <i tel="{{ $call->destination }}" class="fa fa-phone call-now"></i></td>
                        <td>{{ $call->caller_id }}</td>
                        
                        <td> <span title="{{ $call->channel }}">{{ Str::limit($call->channel, 10, '...') }}</span></td>

                        <td data-toggle="tooltip" data-placement="top" data-html="true" title="Connect :{{ $call->connect_time }} <br />Establish :{{ $call->establish_time }} <br />Disconnect :{{ $call->disconnect_time }} ">{{ $call->connect_time }}</td>
                       
                      
                        <td>{{ $call->human_readable_duration }}</td>
                        <td>{{ $call->uas == 1 ? 'Incoming' : 'Outgoing' }}</td>
                        <td> {{ $call->status->getText() }}</td>
                        <td>
                            @if($call->records->count())
                            <a class="btn btn-outline-primary" href="{{ route('call.records.index', $call->id) }}"> <i class="fa fa-link"></i> </a>

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
@push('script')
<script>
 $(function(){
  $(".table").resizableColumns({
    store: window.store
  });
});
</script>
@endpush
