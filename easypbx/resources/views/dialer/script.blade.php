@push('script')
<script>
    $( document ).ready(function() {
        ws_opened = false;
        dcall_id = '';
        var socket;
        
        $('#dialerModal').on('show.bs.modal', function () {
            $.get("{{ route('dialer.index') }}", function(data, status){
                //alert("Data: " + data + "\nStatus: " + status);

                // console.log(data);
                $('#dialer_ajax_content').html(data)
            })
        })
   
        $('#dailerCallButtonOnNav').popoverButton({
                target: '#dialerModal',
                placement: 'bottom',
                closePopoverOnBlur: false,

        });
   


        $('#dialerModal').popoverX('show');
        $('#dialerModal').popoverX('hide');

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

        // $("#dialer_ajax_content").on('click','#logout',function(e) {
       
        //     e.preventDefault();
        //     $.get("{{ route('dialer.logout') }}", function(data, status){
        //         //alert("Data: " + data + "\nStatus: " + status);
        //         $('#dialerModal').modal('hide');
        //         socket.close();
        //         ws_opened = false;
        //     });
        // });

        function logout(){
            $.get("{{ route('dialer.logout') }}", function(data, status){
                //alert("Data: " + data + "\nStatus: " + status);
                $('#dialerModal').popoverX('hide');
                socket.close();
                ws_opened = false;
            });
        }

        $("#dialer_ajax_content").on('load_index', function(e, data) { 
            connectWebsocket();

            console.log("outgoing from load index", data);
            
            if( Object.keys(data).length > 0 ){
                onCallDataRecieve(data);
            }
            
         
        });

        // $("#dialer_ajax_content").on('submit','#dial_input_form', function(e) { 
        //     e.preventDefault();
        //     console.log('submitted dial form');

        //     // if($('#btndial').contains('d-none')
            
        // });

        $("#dialer_ajax_content").on('click','#btndial', function(e) { 
            $.get("{{ route('dialer.dial') }}?tel_no=" + $('#tel_no').val(), function(data, status){
           
                if(data.error == true){
                    $('#dial-status').html(data.error_message)
                }else{
                    onCallDataRecieve(data);                  
                }
                
                
            });
        });

        $("#dialer_ajax_content").on('click','#btnhangup', function(e) { 
            $.get("{{ route('dialer.hangup') }}?call_id=" + dcall_id, function(data, status){
                $('#btnhangup').addClass('d-none');
                $('#btnforward').addClass('d-none');
                $("#forwardGroup").addClass('d-none');
                $('#btndial').removeClass('d-none');
            });            
        });   

        $("#dialer_ajax_content").on('click','#btnforward', function(e) { 
            if( $("#forwardGroup").hasClass('d-none') ) {
                $("#forwardNoInput").val('');
                $("#forwardGroup").removeClass('d-none');
            }
            else{
                $("#forwardGroup").addClass('d-none');
            }

            // $('#btnforward').popoverButton({
            //         target: '#popoverForward',
            //         placement: 'top'
            // });

            // $('#popoverForward').popoverX('show');

            // const forward = prompt("Please enter a number to forward");
            // if(forward == '') return;
            // $.get("{{ route('dialer.forward') }}?call_id=" + dcall_id + '&forward=' +  forward  , function(data, status){
            //     $('#btnhangup').addClass('d-none');
            //     $('#btnforward').addClass('d-none');
            //     $('#btndial').removeClass('d-none');
            // });   
        }); 

        $("#dialer_ajax_content").on('click','#forwardCallBtn', function(e) { 
            var forward = $("#forwardNoInput").val().trim();
            console.log("forwarding to ", forward);

            $.get("{{ route('dialer.forward') }}?call_id=" + dcall_id + '&forward=' +  forward  , function(data, status){
                $('#btnhangup').removeClass('d-none');
                $('#btnforward').removeClass('d-none');
                $('#btndial').addClass('d-none');
                $("#forwardGroup").addClass('d-none');
            }); 

        });
        


          
        function onCallDataRecieve(call){
            if(call.call_id !='') dcall_id = call.call_id
            $('#dial-status').html(call.status);
            $("#tel_no").val(call.to);

            if(call["status-code"] >=3){                
                $('#btnhangup').addClass('d-none');
                $('#btnforward').addClass('d-none');
                $("#forwardGroup").addClass('d-none');
                $('#btndial').removeClass('d-none');
                $('#dial-status').addClass('badge-danger');
                $('#dial-status').removeClass('badge-success');


                

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
            ws.send('hello world')
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
                       /*  if($('#dialerModal').attr('dial') != ''){
                            $('#tel_no').val($('#dialerModal').attr('dial'));
                            $('#dialerModal').removeAttr('dial');
                            $('#btndial').click();
                        } */
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

    });        

</script>

@endpush
