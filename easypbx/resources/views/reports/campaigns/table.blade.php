<div class="row">
    <div class="col-sm-12">

        <table class="table table-striped ">
            <thead>
                <tr>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Total Sent') }}</th>
                    <th>{{ __('Total Successfull') }}</th>
                    <th>{{ __('Total Failed') }}</th>
                    <th>{{ __('Date/Time') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($campaigns as $result)
                    <tr>
                        
                        <td><a href="{{ route('campaigns.campaign.edit', $result->id) }}"> {{ $result->name }} </a></td>
                        <td>{{ $result->total_sent }}</td>
                        <td>
                            {{ floatval($result->total_successfull) }} 
                            
                            @if( $result->total_successfull > 0 )
                                ({{ number_format((floatval($result->total_successfull) * floatval(100) / floatval($result->total_sent)), 2) }}%)
                            @else
                                {{ __('(0.00%)') }}
                            @endif
                        </td>
                        <td>
                            {{ floatval($result->total_failed) }} 
                            
                            @if( $result->total_failed > 0 )
                                ({{  number_format((floatval($result->total_failed) * floatval(100) / floatval($result->total_sent)), 2) }}%)
                            @else
                                {{ __('(0.00%)') }}
                            @endif

                        </td>
                        <td>{{ date_time_format($result->created_at) }}</td>

                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>

