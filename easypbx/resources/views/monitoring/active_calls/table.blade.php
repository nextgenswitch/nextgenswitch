<div class="row">
    <div class="col-sm-12">

        <table class="table table-striped ">
            <thead>
                <tr>

                  
                    <th class="sortable" sort-by="destination">{{ __('Destination') }}</th>
                    <th>{{ __('Channel') }}</th>
                    <th>{{ __('Caller ID') }}</th>
                    <th class="sortable" sort-by="connect_time">{{ __('Date/Time') }}</th>
                    <th class="sortable" sort-by="duration">{{ __('Duration') }}</th>
                    <th>{{ __('Type') }}</th>
                    <th class="sortable" sort-by="status">{{ __('Status') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($calls as $call)
                    <tr data-id="{{ $call->id }}">
                     
                        <td>{{ $call->destination }}</td>
                        <td  title="{{ $call->channel }}" data-toggle="tooltip" data-placement="top">{{ substr($call->channel, 0, 10) }}...</td>
                        <td>{{ $call->caller_id }}</td>
                        <td  data-toggle="tooltip" data-placement="top" data-html="true" title="Connect :{{ $call->connect_time }} <br />Establish :{{ $call->establish_time }} ">{{ $call->connect_time }}</td>
                        
                        <td>{{ $call->duration }}</td>
                        <td>{{ $call->uas == 1 ? 'Incoming' : 'Outgoing' }}</td>
                        <td> {{ $call->status->getText() }}</td>
                        <td>
                        <button type="button" class="btn btn-danger btnhangup"><i class="fa fa-phone"></i> Hangup</button>
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
