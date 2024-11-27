@push('css')
    <style>
        #campaignLogModal .table tr:first-child th {
            border-top: none;
        }
    </style>
@endpush

<div class="modal fade" id="campaignLogModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between">
                <h5 class="modal-title" id="campaignLabel">{{ __('Campaign Call/SMS Logs') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
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
</div>

@push('script')
<script type="text/javascript">

$(document).ready(function(){
    ws_opened = false;
    var socket;

    var cStatus = $(".btnStatus").attr('data-status');

    if(cStatus != undefined && cStatus == 2){
        connectWebsocket($("#crud_contents").attr('cid'));
        $("#campaignLogModal").modal('toggle');
    }

    $('.campaignLog').click(function(e) {
        e.preventDefault();
        clearLog();
        
        connectWebsocket($("#crud_contents").attr('cid'));
        $("#campaignLogModal").modal('toggle');

    });

    

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

})

</script>


@endpush