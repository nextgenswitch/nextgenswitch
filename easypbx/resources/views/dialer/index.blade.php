<div class="dialer-index">
        <div id="dial-index">
            <div class="toolbar">
                <button type="button" id="btnMic" class="btn-icon bg-success"><i class="fa fa-microphone mr-0"></i></button>
                <button type="button" id="btnLogout" class="btn-icon bg-danger"><i class="fa fa-sign-out mr-0"></i></button>
            </div>

            <div class="text-left pb-2">
                <span id="dial-status" class="badge badge-danger"></span>
            </div>
            <form class="form-inline" id="dial_input_form">
                <input type="text" class="form-control mr-1" id="tel_no" placeholder="Enter number to dial">
                <button type="button" class="btn btn-success" id='btndial' data-toggle="tooltip" title="Dial">
                    <!-- Dial -->
                     <i class="fa fa-phone"></i>
                </button>
                <button type="button" class="btn btn-danger d-none mr-1" id='btnhangup' data-toggle="tooltip" title="Hangup">
                    <!-- Hangup -->
                     <i class="fa fa-times"></i>
                </button>
                <button type="button" class="btn btn-info d-none ml-1" id='btnforward' title="Forward">
                    <!-- Forward -->
                     <i class="fa fa-share"></i>
                </button>
            </form>
            
        
            

            <div class="custom-control custom-switch d-none mt-1 mb-2">
                <input type="checkbox" class="custom-control-input" id="forward_to_function_switch">
                <label class="custom-control-label" for="forward_to_function_switch">Forward to function</label>
            </div>

            <form class="d-none" id="forward_input_form">
                <input type="text" class="form-control mr-1 mb-2" placeholder="Enter forward no" id="forwardNoInput">
                <button class="btn btn-success btn-sm" type="button" id="forwardCallBtn"><i class="fa fa-share"></i> Forward</button>
                <button class="btn btn-danger btn-sm" id="cancelForwardBtn"> <i class="fa fa-times"></i> Cancel</button>
            </form>

                
            
                <!-- <div class="divider text-center">
                    <span class="line"></span>
                    <span class="or">OR</span>
                </div> -->

            <form id="forward_function_form" class="d-none">
                {!! Form::select('dialer_function_id', $dialer_functions, '', [
                    'class' => 'form-control',
                    'id' => 'dialer_function_id',
                    'required' => true,
                    'placeholder' => __('Destination Module'),
                ]) !!}

                {!! Form::select('dialer_destination_id', [], '', [
                        'class' => 'form-control',
                        'id' => 'dialer_destination_id',
                        'required' => true, 
                        'placeholder' => __('Select destination')
                    ]
                ) !!}

                        
                <button class="btn btn-success btn-sm" type="button" id="forwardFuncBtn"><i class="fa fa-share"></i> Forward</button>
                <button class="btn btn-danger btn-sm" id="forwardFuncCancelBtn"> <i class="fa fa-times"></i> Cancel</button>
            </form>
            
        </div>
        <div class="row justify-content-center" id="dial-index-pre"></div>
</div>     


<script>
     $( document ).ready(function() {
        var outgoingCall =@json($outgoingCall);

        $("#dialer_ajax_content").attr('call_id','{{ $call_id }}');
        $("#dialer_ajax_content").trigger('load_index', [outgoingCall]);
        //$('#dialerModal').trigger("afterConnect");


        
       
     });
</script>
