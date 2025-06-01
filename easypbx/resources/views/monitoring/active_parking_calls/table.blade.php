<div class="row">
    <div class="col-sm-12">

        <table class="table table-striped ">
            <thead>
                <tr>
                    <th>{{ __('Parking Name') }}</th>
                    <th class="sortable" sort-by="parking_no">{{ __('Parking No') }}</th>
                    <th class="sortable" sort-by="from">{{ __('From') }}</th>
                    <th class="sortable" sort-by="to">{{ __('To') }}</th>
                    <th class="sortable" sort-by="to">{{ __('Waiting Time') }}</th>
                    
                </tr>
            </thead>
            <tbody>
                @foreach ($cpLogs as $parkLog) 
                    <tr>
                        <td>{{ optional($parkLog->callParking)->name }}</td>
                        <td>{{ $parkLog->parking_no }}</td>
                        <td>{{ $parkLog->call->caller_id }}</td>
                        <td>{{ $parkLog->call->destination }}</td>
                        <td>{{ duration_format($parkLog->created_at->diffInSeconds(now())); }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>
<div class="row">
    {!! $cpLogs->render() !!}
</div>
