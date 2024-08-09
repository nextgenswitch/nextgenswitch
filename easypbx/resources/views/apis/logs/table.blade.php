<div class="row"><div class="col-sm-12">

    <table class="table table-striped " id="api_table" secret="{{ session()->has('secret') ? session('secret') : '' }}">
        <thead>
            <tr>
                
                <th class="sortable" sort-by="title">{{ __('IP') }}</th>
                <th>{{ __('URL') }}</th>
                
            </tr>
        </thead>
        <tbody>
        @foreach($logs as $log)
            <tr>
                
                <td>{{ $log->ip_address }}</td>
                
                <td>{{ $log->url }}</td>

               
            </tr>
        @endforeach
        </tbody>
    </table>

    </div>            
</div>

<div class="row">

{!! $logs->render() !!}
</div>

