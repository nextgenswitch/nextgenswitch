<div class="modal fade" id="smsModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Message</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="" accept-charset="UTF-8" id="sms_modal_form" class="form-horizontal">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="to" id="sms_to">
                    <textarea name="body" id="sms_body" class="form-control" rows="7" placeholder="Enter your message here"></textarea>
                </div>
            </form>

            <div class="modal-footer">
                <button id="btn_send_ws" class="btn btn-primary btn-sm"> {{ __('Send to Whatsapp') }} </button>
                <button id="btn_send_sms" class="btn btn-primary btn-sm"> {{ __('Send SMS') }} </button>
            </div>

            
        </div>
    </div>
</div>


@push('script')

<script>
    $(document).ready(function(){

        $("#btn_send_sms").click(function(e){
            e.preventDefault();
            console.log('clicked sms button');

            var sms_body = $("#sms_body").val().trim();
            var sms_to = $("#sms_to").val().trim();

            if( sms_body && sms_body.length > 0 && sms_to && sms_to.length > 0){
                $.get("{{ route('contacts.contact.send_sms') }}?to=" + sms_to + "&body=" + sms_body, function(res){
                    console.log(res);

                    if(res.success){
                        showToast('SMS sent successfully');
                    }
                    else{
                        showToast('sending sms failed please retry later', false);
                    }
                })
            }
            else{
                showToast('Message body could not be empty', false);
            }


        });

        $("#btn_send_ws").click(function(e){
            e.preventDefault();

            console.log('clicked whatsapp button');

            var sms_body = $("#sms_body").val().trim();

            if(sms_body != null && sms_body.length > 0){
                var sms_to = $("#sms_to").val().trim();

                var whatsapp_api_url = 'https://api.whatsapp.com/send?phone='+sms_to+'&text=' + sms_body;

                window.open(whatsapp_api_url, "_blank");
            }
            else{
                showToast('Message body could not be empty', false);
            }
            
        });

    
        function showToast(message, success = true) {

            let toast = {
                title: (success) ? "Success" : "Failed",
                message: message,
                status: (success) ? TOAST_STATUS.SUCCESS : TOAST_STATUS.DANGER,
                timeout: 5000
            }

            Toast.create(toast);
        }
    })
</script>

@endpush