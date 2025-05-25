@extends('layouts.app')

@section('content')

    <div class="panel panel-default">

    <div class="panel-heading clearfix">

        <div class="pull-left">
            <h4 class="mb-5">{{ !empty($campaign->name) ? $campaign->name : __('Broadcast name') }}</h4>
        </div>

        <div class="btn-group btn-group-sm pull-right" role="group">

            <a href="{{ route('broadcasts.broadcast.index') }}" class="btn btn-primary" title="{{ __('Show all Broadcasts') }}">
                <span class="fa fa-list" aria-hidden="true"></span>
            </a>

            <a href="{{ route('broadcasts.broadcast.create') }}" class="btn btn-primary" title="{{ 'Create new Broadcast' }}">
                <span class="fa fa-plus" aria-hidden="true"></span>
            </a>

        </div>
    </div>

    <div class="panel-body">

             
    <div class="container-fluid text-center bg-primary">
        <h6 class="text-light py-2 " id="campaign_call_status">
         Getting Broadcast Status   
        
        
        </h6>
 
    </div> 

    <div class="row px-3">
        <div class="col-md-6">
            <h4 class="brand-logo">Broadcast Stats</h4>
        </div>
       
        <div class="col-md-6 text-right">
            <div class="btn-group" role="group">
            <form method="POST" action="{!! route('broadcasts.broadcast.run', $campaign->id) !!}" accept-charset="UTF-8" class="deleteFrm">
                @csrf
                @method('POST') 
               
                <button name="action" value="1" type="submit" class="btn btn-success btn-sm" id="startDial" onclick="return confirm('{{ __('Click Ok to start Broadcast.')}}')">
                    <i class="fa fa-play"></i>
                    <span> Start/Resume</span>
                </button>
                <button name="action" value="2" type="submit" class="btn btn-danger btn-sm" id="stopDial" onclick="return confirm('{{ __('Click Ok to stop Broadcast.')}}')">
                    <i class="fa fa-stop"></i>
                    <span>Pause/Stop</span>
                </button>
               
                <a href="{{ route('broadcasts.broadcast.edit', $campaign->id ) }}" class="btn btn-secondary btn-sm">
                    <i class="fa fa-edit"></i>
                    <span>Edit</span>
                </a>
                <a href="{{ route('broadcast_calls.broadcast_call.index' ,['id'=>$campaign->id]) }}" class="btn btn-info btn-sm">
                    <i class="fa fa-list"></i>
                    <span>Full Report</span>
                </a>
            </form>    
            </div>
        </div>
       
    </div>

    <div class="table-responsive">
        <table class="table overview-table">
            <tr>
              
                
                <th class="text-center">Contacts</th>
                <th class="text-center">Processed</th>
                <th class="text-center">Successfull</th>
                <th class="text-center">Failed</th>
                <th class="text-center">Duration</th>
               
            </tr>
            <tr>                                
                <td class="text-center" id="stats_total_contact">{{$stats['total_contact']}}</td>
                <td class="text-center" id="stats_processed">{{$stats['processed']}}</td>
                <td class="text-center" id="stats_successfull">{{$stats['successfull']}}</td>
                <td class="text-center" id="stats_failed">{{$stats['failed']}}</td>
                <td class="text-center" id="stats_duration">{{$stats['duration']}}</td>
            </tr>
        </table>
    </div>
    

    <div class="row px-3">
    <div class="col-md-11"><h4 class="brand-logo">Live Logs</h4></div>
    <div class="col-md-1">
    <div class="spinner-border " role="status">
    <span class="sr-only">Loading...</span>
    </div>
    </div>
   </div>

    <div class="table-responsive">
 
    

    <table class="table border-top-0" >
                    <thead>
                    <tr>
                        <th> Datetime </th>
                        <th> Contact </th>
                        <th> Status </th>
                    </tr>

                    </thead>
                    <tbody id="logContent">

                    </tbody>

                </table>
    </div>






        </div>
    </div>

@endsection

@push('script')
<script>
    $(document).ready(function() {
       var status = {{ $campaign->status }};
       var ws_opened = false;
       
       function get_stats(){
            $.get("{{ route('broadcasts.broadcast.stats', [ $campaign->id,'ajax'=>true]) }}" , function(data, status){
                console.log(data);
                var statusText = "{{ __('Broadcast is not started yet') }}"
                var color  = 'bg-primary';
                if(data.status == 1 && data.in_time == true){
                    statusText = "{{ __('Broadcast is runnung') }}"; color='bg-success'; }
                else if(data.status == 1 && data.in_time == false){
                    statusText = "{{ __('Broadcast will  start on scheduled time') }}"; color='bg-info'; }
                else if(data.status == 2 ){
                    statusText = "{{ __('Broadcast is stopped') }}";   color='bg-danger';  }
                else if(data.status == 3 ){
                    statusText = "{{ __('Broadcast is completed') }}";  color='bg-secondary';   
                    $('#startDial').hide();
                    $('#stoptDial').hide();
                    
                }
                
                $('#campaign_call_status').parent('div').removeClass('bg-primary bg-success bg-info bg-warning bg-secondary').addClass(color);
                $('#campaign_call_status').text(statusText);
                $('#stats_total_contact').text(data.total_contact);
                $('#stats_processed').text(data.processed);
                $('#stats_duration').text(data.duration);
                $('#stats_successfull').text(data.successfull);
                $('#stats_failed').text(data.failed);

                setTimeout(get_stats,5000);
            });
       } 
       get_stats();

       if(status == 1) $('#startDial').hide();
       else $('#stopDial').hide();
       
       connectWebsocket({{ $campaign->id }});

       function connectWebsocket(campaignId) {

            if (ws_opened) {
                console.log('websock allready connected');
                return;
            }

            var campaign_id = 'campaign_' + campaignId;

            console.log("websocket connecting");

            var url = "{{ url('/websocket') }}/?;client_id=" + campaign_id
            url = url.replace("http", 'ws')
            url = url.replace("https", 'wss')
            console.log(url);

            const ws = new WebSocket(url)

            ws.onopen = () => {
                console.log('ws opened on browser')
                ws.send('hello world')
                ws_opened = true
                socket = ws
            }

            ws.onmessage = (message) => {
                console.log(message.data)
                const data = JSON.parse(message.data);
                displayLog(data);
            }

            ws.onclose = function(e) {
                console.log('websock connection closed');
                //connectWebsocket();
            };

        }

        function displayLog(data) {

            if($("#log_" + data.contact).length > 0 ){
                var td = '<td>' + data.date + '</td> <td>' + data.contact + '</td><td>' + data.status + '</td>';
                $("#log_" + data.contact).html(td);
            }
            else{
                var tr = '<tr id="log_'+ data.contact +'"> <td>' + data.date + '</td> <td>' + data.contact + '</td><td>' + data.status + '</td> </tr>';
                $("#logContent").prepend(tr);
            }

        }

        function clearLog(){
         $("#logContent").html('');
        }


    });
</script>
@endpush