<div class="dialer-index">
        <div id="dial-index">
            <div class="text-left pb-2">
                <span id="dial-status" class="badge badge-danger"></span>
            </div>
            <form class="form-inline" id="dial_input_form">
                <input type="text" class="form-control mr-1" id="tel_no" placeholder="Enter number to dial">
                <button type="button" class="btn btn-success" id='btndial'>Dial</button>
                <button type="button" class="btn btn-danger d-none mr-1" id='btnhangup'>Hangup</button>
                <button type="button" class="btn btn-info d-none ml-1" id='btnforward'>Forward</button>
                
                <div class="input-group d-none" id="forwardGroup">
                    <input type="text" class="form-control" placeholder="Enter forward no" id="forwardNoInput">
                    <div class="input-group-append">
                        <button class="btn btn-info" type="button" id="forwardCallBtn"><i class="fa fa-share"></i></button>
                    </div>
                </div>
                

            </form>
        </div>
        <div class="row justify-content-center" id="dial-index-pre"></div>
</div>     


<script>
     $( document ).ready(function() {
        var outgoingCall =@json($outgoingCall);

        $("#dialer_ajax_content").attr('call_id','{{ $call_id }}');
        $("#dialer_ajax_content").trigger('load_index', [outgoingCall]);


        
       
     });
</script>
