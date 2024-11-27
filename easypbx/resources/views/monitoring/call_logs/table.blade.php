
<div class="row">
    <div class="col-sm-12">

        <table class="table table-striped   " data-resizable="true">
            <thead>
                <tr>
                    <th>{{ __('Type') }}</th>
                    <th class="sortable" sort-by="destination">{{ __('Destination') }}</th>
                    <th class="sortable" sort-by="caller_id">{{ __('Caller ID') }}</th>
                    <th>{{ __('Channel') }}</th>
                    <th class="sortable" sort-by="connect_time">Date/Time</th>
                    
                    <th class="sortable" sort-by="duration">{{ __('Duration') }}</th>
                   
                    <th class="sortable" sort-by="status">{{ __('Status') }}</th>
                    <!-- <th>{{ __('Record') }}</th> -->
                </tr>
            </thead>
            <tbody>
                @foreach ($calls as $call)
                    <tr data-id="{{ $call->id }}">
                    <td>

                        @if($call->uas == 1)
                        <i data-feather="phone-incoming" style="width:24px;height:24px;" title="Incoming" class="{{ $call->status->getCss() }}"></i>
                        @else
                        <i data-feather="phone-outgoing" style="width:24px;height:24px;" title="Outgoing" class="{{ $call->status->getCss() }}"></i>
                        @endif
                        </td>
                        <td>
                            @include('contacts.call_sms_popup', ['tel_no' => $call->destination])
                        </td>
                        <!-- <td>{{ $call->destination }} <i tel="{{ $call->destination }}" class="fa fa-phone call-now"></i></td> -->
                        <td>{{ $call->caller_id }}</td>
                        
                        <td> <span title="{{ $call->channel }}">{{ Str::limit($call->channel, 25, '...') }}</span></td>

                        <td data-toggle="tooltip" data-placement="top" data-html="true" title="Connect :{{ date_time_format($call->connect_time) }} <br />Establish :{{ date_time_format($call->establish_time) }} <br />Disconnect :{{ date_time_format($call->disconnect_time) }} ">{{ date_time_format($call->connect_time) }}</td>
                       
                      
                        <td>{{ duration_format($call->duration) }}</td>
        
                        <td> {{ $call->status->getText() }}</td>
                        <!-- <td>
                            @if($call->records->count())
                            <a class="btn btn-outline-primary" href="{{ route('call.records.index', $call->id) }}"> <i class="fa fa-link"></i> </a>

                            @endif
                        </td> -->
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
