@push('script')
<script>
    $( document ).ready(function() {
        ws_opened = false;
        dcall_id = '';
        var socket;
        connectWebsocket();

        var formElements = @json(optional($campaign->form)->fields);
        formElements = JSON.parse(formElements)
        // console.log(formElements);

        buildForm(formElements);
        $(".date").flatpickr({
            dateFormat: "Y-m-d",
        });

        setTimeout(() => {
            $("#btndial").click() 
            console.log('Dial button clicked automatically');
        }, 1000);

        $("#btndial").click(function(){
            console.log('clicked dial button');
            
            dial_tel_no = $('#dial_tel_no').val();

            if(dial_tel_no != undefined && dial_tel_no.length > 0){
                $("#btndial").addClass('d-none');
                $("#btnhangup").removeClass('d-none');

                $.get("{{ route('dialer_campaigns.dialer_campaign.dial') }}?tel_no=" + dial_tel_no, function(data, status){
                    console.log(data);

                    if(data.error == true){
                        $('#dial-status').html(data.error_message)
                        $("#btnhangup").addClass('d-none');
                        $("#btndial").removeClass('d-none');
                    }else{
                        $("#call_id").val(data['call_id']);
                        onCallDataRecieve(data);                  
                    }
                });
            }  
        })

        $("#btnhangup").click(function(){
            $.get("{{ route('dialer_campaigns.dialer_campaign.hangup') }}?call_id=" + dcall_id, function(data, status){
                $('#btnhangup').addClass('d-none');
                // $('#btnforward').addClass('d-none');
                // $("#forwardGroup").addClass('d-none');
                $('#btndial').removeClass('d-none');
            }); 
        })


        $("#form_data_form").submit(function(){
            console.log('submitted');
            var form_data = $(this).serialize();

            $.ajax({
                type: "POST",
                url: '{{ route("dialer_campaigns.dialer_campaign.form_data") }}',
                data: form_data, // serializes the form's elements.
                success: function(data, message, xhr) {
                    console.log(data);

                    if(data.status == 'success'){
                        showToast('Form data saved successfully');
                    }
                }
            });
        });

        $("#btn_send_ws").click(function(e){
            e.preventDefault();

            console.log('clicked whatsapp button');

            var sms_body = $("#sms_body").val().trim();
            var telno = $("#dial_tel_no").val().trim();

            var whatsapp_api_url = 'https://api.whatsapp.com/send?phone='+telno+'&text=' + sms_body;

            
  
            openCenteredWindow(whatsapp_api_url, 'Share with Whatsapp', 50,50);


        });

        $("#btn_send_sms").click(function(e){
            e.preventDefault();
            
            console.log('clicked sms button');

            var sms_body = $("#sms_body").val().trim();
            var telno = $("#dial_tel_no").val().trim();
            var from = 'EasyPbx';

            var sms_url = "{{ route('dialer_campaigns.dialer_campaign.send.sms') }}?from=" + from + "&to=" + telno + "&body=" + sms_body;

            $.get(sms_url, function(response){
                console.log(response);
                if(response.status){
                    showToast('Sms sent successfully');
                }
                else {
                    showToast('There was something went wrong!');
                }                
            })
            
        });


        function onCallDataRecieve(call){
            console.log(call)

            if(call.call_id !='') dcall_id = call.call_id
            $('#dial-status').html(call.status);
            $("#tel_no").val(call.to);

            if(call["status-code"] >=3){                
                $('#btnhangup').addClass('d-none');
                // $('#btnforward').addClass('d-none');
                // $("#forwardGroup").addClass('d-none');
                $('#btndial').removeClass('d-none');
                // $('#dial-status').addClass('badge-danger');
                // $('#dial-status').removeClass('badge-success');

                window.location.reload();

            }else if(call["status-code"] == 2){
                $('#btndial').addClass('d-none');
                // $('#btnforward').removeClass('d-none');
                $('#btnhangup').removeClass('d-none');
                // $('#dial-status').addClass('badge-success');
                // $('#dial-status').removeClass('badge-danger');
            }else{
                $('#btndial').addClass('d-none');
                // $('#btnhangup').removeClass('d-none');
                // $('#dial-status').addClass('badge-success');
                // $('#dial-status').removeClass('badge-danger');
                
            }
        }

        function connectWebsocket(){
            if(ws_opened) { console.log('websock allready connected'); return;}
            var client_id =$("#dialer_call_content").attr('client_id');
            console.log("websocket connecting");
            var url = '{{ url('/websocket') }}/?;client_id=' + client_id
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

        
        function buildForm(formElements){
            console.log('generating form');
            var form  = '';
            
            $.each(formElements, function(index, item){
                tr = generatePriviewField(item);
                form += $(tr).prop('outerHTML');
                
            });

            // console.log(form);

            $("#nav-custom-fields table").html(form);
        }

        function generatePriviewField(item){
            // console.log(item)

            var field = '';

            field = '<tr><td class="text-right" width="30%"><label for="followup">' + item.label;
            
            if(item.required){
                field += '<span class="text-required">*</span>';
            }
                
            field += '</label></td><td>';

            switch(item.type) {
                case 'text':
                case 'email':
                case 'number':
                    input = $('<input>')
                        .attr('type', item.type)
                        .attr('name', item.name)
                        .attr('required', item.required)
                        .attr('readonly', item.readonly)
                        .attr('placeholder', item.placeholder)
                        .addClass('form-control');
                    break;
                case 'textarea':
                    input = $('<textarea></textarea>')
                        .attr('name', item.name)
                        .attr('required', item.required)
                        .attr('readonly', item.readonly)
                        .attr('placeholder', item.placeholder)
                        .addClass('form-control');
                    break;
                case 'select':
                    input = $('<select></select>')
                        .attr('name', item.name)
                        .attr('required', item.required)
                        .attr('readonly', item.readonly)
                        .addClass('form-control');
                    
                    var plholder = $('<option></option>')
                        .attr('value', "")
                        .text(item.placeholder);
                    input.append(plholder);
                    item.options.forEach(function(option) {
                        var opt = $('<option></option>')
                            .attr('value', option.value)
                            .text(option.text);
                        input.append(opt);
                    });
                    break;

                case 'checkbox':
                    input = $('<div></div>').addClass('custom-control custom-switch');
                    var ccinput = $('<input>')
                        .attr('type', item.type)
                        .attr('name', item.name)
                        .attr('id', item.name)
                        // .attr('required', item.required)
                        // .attr('readonly', item.readonly)
                        // .attr('placeholder', item.placeholder)
                        .addClass('custom-control-input');
                    input.append(ccinput);
                    var label = '<label class="custom-control-label" for="'+ item.name +'">'+ item.placeholder+'</label>';
                    input.append(label);
                    break;

                case 'date':
                    input = $('<input>')
                        .attr('type', 'text')
                        .attr('name', item.name)
                        .attr('required', item.required)
                        .attr('readonly', item.readonly)
                        .attr('placeholder', item.placeholder)
                        .addClass('form-control date');
                    break

                case 'time':
                    input = $('<input>')
                        .attr('type', 'text')
                        .attr('name', item.name)
                        .attr('required', item.required)
                        .attr('readonly', item.readonly)
                        .attr('placeholder', item.placeholder)
                        .addClass('form-control time');
                    break
                    
                default:
                    console.log('Unsupported input type: ' + item.type);
            }

            
            field += input.prop('outerHTML') + '</td></tr>';

            return field;    
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

        function openCenteredWindow(url, title, widthPercent, heightPercent) {
              
              const screenWidth = window.screen.width;
              const screenHeight = window.screen.height;

              
              const width = Math.round(screenWidth * (widthPercent / 100));
              const height = Math.round(screenHeight * (heightPercent / 100));

              
              const left = Math.round((screenWidth / 2) - (width / 2));
              const top = Math.round((screenHeight / 2) - (height / 2));

              
              const options = `width=${width},height=${height},top=${top},left=${left},resizable=yes,scrollbars=yes`;

              
              window.open(url, title, options);
          }


    })
</script>
