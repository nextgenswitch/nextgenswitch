@push('script')
<script>
    $( document ).ready(function() {
        ws_opened = false;
        dcall_id = '';
        var socket;
        record = true;
        
        $('#dialerModal').on('show.bs.modal', function () {
            $.get("{{ route('dialer.index') }}", function(data, status){
                //alert("Data: " + data + "\nStatus: " + status);

                 //console.log(data);
                $('#dialer_ajax_content').html(data)
                if($("#dialer_ajax_content").attr('call_id') != undefined)
                    $('#dialerModal').trigger("afterConnect");
                //console.log("dialer call id " + $("#dialer_ajax_content").attr('call_id'));

                reloadRecordBtn();
            })
        })
   
        $('#dailerCallButtonOnNav').popoverButton({
                target: '#dialerModal',
                placement: 'bottom',
                closePopoverOnBlur: false,

        });
   


        $('#dialerModal').popoverX('show');
        $('#dialerModal').popoverX('hide');
        $('#dialerModal').on("dial", function(e,tel,rec){
            //alert(telno);
           // console.log(campaign_id);
            if(tel !== undefined){
                //console.log(tel)
                $("#tel_no").val(tel)
                
                $('#dialerModal').popoverX('show');
                if(ws_opened) $('#btndial').click();
            }
        });

    

        $("#crud_contents").on('click', '.call-now', function(){
            
            var tel = $(this).attr('tel').trim();
            console.log(tel);

            if(tel !== undefined){
                console.log(tel)
                $("#tel_no").val(tel)
                
                $('#dialerModal').popoverX('show');
                $('#btndial').click();
            }

        });


        $("#crud_contents").on('click', '.send-sms-now', function(){
            
            var tel = $(this).attr('tel');

            if(tel != null && tel.length > 0){
                $("#sms_to").val(tel);
                $("#smsModal").modal('toggle');
            }
            
        });

        $("#dialer_ajax_content").on('submit','.login-form',function(e) {
            e.preventDefault();

            var form = $("#dialer_ajax_content .login-form");
            var actionUrl = form.attr('action');

         
            $.ajax({
                type: "POST",
                url: actionUrl,
                data: form.serialize(), // serializes the form's elements.
                success: function(data, message, xhr) {
                    console.log(data, message, xhr.status)
                    
                    if (data.status == 'error') {
                        console.log(data.errors)
                        clearErrorForm(form);

                        $.each(data.errors, (key, item) => {
                            console.log(key, item);

                            var input = form.find('#' + key).addClass('is-invalid');

                            var invalid_feedback = input.closest('.form-group')
                                .find('.invalid-feedback strong');

                            invalid_feedback.text(item);

                        })

                        $("#dialer_ajax_content .loader").addClass('v-hidden');
                    }
                    console.log(data.status);
                    if (data.status == 'success') {
                        clearErrorForm(form);
                        resetForm(form);
                        form.addClass('d-none');
                        $("#dialer_ajax_content").attr('call_id',data.call_id)
                        connectWebsocket();
                        //$("#dialer_ajax_content").trigger('load_index', []);
                        console.log("after login");                      
                        $.get("{{ route('dialer.index') }}", function(data, status){
                            $('#dialer_ajax_content').html(data)
                            $('.dialpanel').addClass('d-none')
                            $('#dial-status').html("Dialer connecting")
                        })
                    }

                }
            });

        });

        $("#dialer_ajax_content").on('click','#btnMic',function(e) {
            e.preventDefault();
            record = !record;
            console.log(record);
            reloadRecordBtn();
        })

        $("#dialer_ajax_content").on('click','#btnLogout',function(e) {
            e.preventDefault();
            logout();

            // $.get("{{ route('dialer.logout') }}", function(data, status){
            //     //alert("Data: " + data + "\nStatus: " + status);
            //     $('#dialerModal').modal('hide');
            //     socket.close();
            //     ws_opened = false;
            // });
        });

        function logout(){
            $.get("{{ route('dialer.logout') }}", function(data, status){
                //alert("Data: " + data + "\nStatus: " + status);
                $('#dialerModal').popoverX('hide');
                socket.close();
                ws_opened = false;
            });
        }

      /*   function dial(tel_no,camapign_id){
            $.get("{{ route('dialer.dial') }}?tel_no=" + $('#tel_no').val(), function(data, status){
           
                if(data.error == true){
                    $('#dial-status').html(data.error_message)
                }else{
                    onCallDataRecieve(data);                  
                }
                           
           });    
        } */

        $("#dialer_ajax_content").on('load_index', function(e, data) { 
            connectWebsocket();

            console.log("outgoing from load index", data);
            
            if( Object.keys(data).length > 0 ){
                onCallDataRecieve(data);
            }
            
         
        });


        $("#dialer_ajax_content").on('click','#btndial', function(e) { 
            $("#dial_input_form").submit();
        });

        $("#dialer_ajax_content").on("submit", "#dial_input_form", function(e){
            e.preventDefault();

            var tel_no = $('#tel_no').val();

            if(tel_no.length > 0){
                $.get("{{ route('dialer.dial') }}?tel_no=" + tel_no + '&record=' + record, function(data, status){
                    //console.log(data);
                    if(data.error == true){
                        $('#dial-status').html(data.error_message)
                    }else if(data.status != undefined){
                        onCallDataRecieve(data);
                    }
                });
            }
                     

        })
        

        $("#dialer_ajax_content").on('click','#btnhangup', function(e) { 
            $.get("{{ route('dialer.hangup') }}?call_id=" + dcall_id, function(data, status){
                $('#btnhangup').addClass('d-none');
                $('#btnforward').addClass('d-none');
                $('#btndial').removeClass('d-none');

            });            
        });   

        $("#dialer_ajax_content").on('click','#btnforward', function(e) { 
            $("#dial_input_form").addClass('d-none');
            $("#forward_input_form").removeClass('d-none');
            $("#forward_to_function_switch").closest('.custom-control').removeClass('d-none');

        }); 

        $("#dialer_ajax_content").on("click", '#cancelForwardBtn', function(){
            $("#dial_input_form").removeClass('d-none');
            $("#forward_input_form").addClass('d-none');
            $("#forward_to_function_switch").closest('.custom-control').addClass('d-none');
        });

        $("#dialer_ajax_content").on("change", '#forward_to_function_switch', function(){
            
            if ($(this).is(':checked')) {
                $("#forward_input_form").addClass('d-none');
                $("#forward_function_form").removeClass('d-none');
                
            }
            else{
                $("#forward_input_form").removeClass('d-none');
                $("#forward_function_form").addClass('d-none');
            }
            

        });



        destinations = "{{ route('dialer.destinations', 0) }}"

        $("#dialer_ajax_content").on('change', '#dialer_function_id', function(e) {
            e.preventDefault()

            var val = $(this).val().trim()

            if (val != undefined && val != '') {
                route = destinations.trim().slice(0, -1) + val
                console.log(route)

                $.get(route, function(res) {
                    console.log(res)
                    $("#dialer_destination_id").html(res)
                })

            } else
                $("#dialer_destination_id").html('<option> Select destination </option>')

        })

        $("#dialer_ajax_content").on('click','#forwardCallBtn', function(e) { 
            e.preventDefault();
            $("#forward_input_form").submit();
        });

        $("#dialer_ajax_content").on("submit", "#forward_input_form", function(event){
            event.preventDefault();;

            var fwd = $("#forwardNoInput").val().trim();

            // console.log(fwd.length);
            console.log('forwarding to ' + fwd);

            if(fwd.length > 0){
                console.log('submitted 2');
                $.get("{{ route('dialer.forward') }}?call_id=" + dcall_id + '&forward=' +  fwd  , function(data, status){
                    $('#btnhangup').removeClass('d-none');
                    $('#btnforward').removeClass('d-none');
                    $('#btndial').addClass('d-none');

                    $("#dial_input_form").removeClass('d-none');
                    $("#forward_input_form").addClass('d-none');
                    $("#dial_input_form").trigger('reset');
                }); 

            }
            return false;
            
        });

        $("#dialer_ajax_content").on('click','#forwardFuncBtn', function(event) { 
            event.preventDefault();

            function_id = $("#dialer_function_id").val();
            destination_id = $("#dialer_destination_id").val();

            if(function_id.length > 0 && destination_id.length > 0){
                $.get("{{ route('dialer.forward') }}?call_id=" + dcall_id + '&function_id=' +  function_id.trim() + '&destination_id=' + destination_id  , function(data, status){
                    // console.log(data)
                    // console.log(status)

                    if(status){
                        $("#forward_function_form").addClass('d-none');
                        $("#forward_to_function_switch").prop('checked', false);
                        $("#forward_to_function_switch").parent().addClass('d-none');
                        $("#dial_input_form").removeClass('d-none');
                    }
                });
            }
           
        });

        $("#dialer_ajax_content").on("click", "#forwardFuncCancelBtn", function(e){
            e.preventDefault();

            $("#forward_function_form").addClass('d-none');
            $("#dial_input_form").removeClass('d-none');

            $("#forward_to_function_switch").prop('checked', false);
            $("#forward_to_function_switch").parent().addClass('d-none');

            
        });

    
        function reloadRecordBtn(){
            if(record){
                $("#btnMic").addClass('bg-success');
                $("#btnMic").removeClass('bg-secondary');

                $("#btnMic i").addClass('fa-microphone');
                $("#btnMic i").removeClass('fa-microphone-slash');
            }
            else{
                $("#btnMic").addClass('bg-secondary');
                $("#btnMic").removeClass('bg-success');

                $("#btnMic i").addClass('fa-microphone-slash');
                $("#btnMic i").removeClass('fa-microphone');
            }

        }

          
        function onCallDataRecieve(call){
            
            if(call.call_id !='') dcall_id = call.call_id
            $('#dial-status').html(call.status);
            //$("#tel_no").val(call.to);
            if(call.to == undefined)
                call.to = $("#tel_no").val();
            
            $('#dialerModal').trigger('dialStatus',call);

            if(call["status-code"] >=3){                
                $('#btnhangup').addClass('d-none');
                $('#btnforward').addClass('d-none');
                $('#btndial').removeClass('d-none');
                $('#dial-status').addClass('badge-danger');
                $('#dial-status').removeClass('badge-success');

                $("#forward_input_form").trigger('reset');
                $("#forward_input_form").addClass('d-none');
                $("#dial_input_form").removeClass('d-none');

                $("#forward_function_form").addClass('d-none');
                $("#forward_to_function_switch").prop('checked', false);
                $("#forward_to_function_switch").parent().addClass('d-none');


            }else if(call["status-code"] == 2){
                $('#btndial').addClass('d-none');
                $('#btnforward').removeClass('d-none');
                $('#btnhangup').removeClass('d-none');
                $('#dial-status').addClass('badge-success');
                $('#dial-status').removeClass('badge-danger');
            }else{
                $('#btndial').addClass('d-none');
                $('#btnhangup').removeClass('d-none');
                $('#dial-status').addClass('badge-success');
                $('#dial-status').removeClass('badge-danger');
                
            }
        }

        function connectWebsocket(){
            if(ws_opened) { console.log('websock allready connected'); return;}
            var call_id =$("#dialer_ajax_content").attr('call_id')
            console.log("websocket connecting");
            var url = '{{ url('/websocket') }}/?;client_id=' + call_id
            url = url.replace("http",'ws')
            url = url.replace("https",'wss')
            console.log(url);
            const ws = new WebSocket(url)
            ws.onopen = () => {
            console.log('ws opened on browser')
            //ws.send('hello world')
            ws_opened = true
            socket = ws
            
            }

            ws.onmessage = (message) => {
                console.log(`message received`, message.data)
                const data = JSON.parse(message.data);
                
                if(data.type ==1){
                    
                    call = data.data
                    console.log("call data recieved");
                    console.log(call);
                    //console.log('here hangup' + call["status-code"])
                    onCallDataRecieve(call);

                }else if(data.type ==0){
                    console.log("dialer call data")
                    
                    call = data.data
                    console.log(call)
                    if(call["status-code"] >=3){
                        // $('#logout').click();
                        logout()
                    }
                        
                    else if(call["status-code"] ==2){
                        $('.dialpanel').removeClass('d-none')
                        $('#dial-status').html("Ready for call")
                        $('#dialerModal').attr('connected','1');
                        $('#dialerModal').trigger("afterConnect");
                        /* console.log("after login" + $('#tel_no').val());
                        if($('#tel_no').val() != ''){
                            $('#btndial').click();
                        }  */
                    }
                }

                   
            }

            ws.onclose = function (e){
                console.log('websock connection closed');
                //connectWebsocket();
            };
        }

        
        

        function showLoader(text,show){
            var content = ' <div class="d-flex justify-content-center loader v-hidden"><div class="spinner-border" role="status"><span class="visually-hidden"></span></div><strong>&nbsp;&nbsp; ' +  text + '</strong></div>';


        }

        function clearErrorForm(activeForm) {
            var is_invalids = activeForm.find('.is-invalid');

            is_invalids.each((index, item) => {
                $(item).removeClass('is-invalid');
            });

            var invalid_feedbacks = activeForm.find('.invalid-feedback strong')
            invalid_feedbacks.each((index, item) => {
                $(item).text('');
            });

        }

        function resetForm(activeForm) {
            activeForm.trigger("reset");
        }

        function loging(){
            console.log('sdfsdf')
        }

        function showToast(message, success = true) {
            let toast = {
                title: (success) ? "Success" : "Failed",
                message: message,
                status: (success) ? TOAST_STATUS.SUCCESS : TOAST_STATUS.DANGER,
                timeout: 5000
            }
            Toast.create(toast);
        }

    });        

</script>

@endpush
