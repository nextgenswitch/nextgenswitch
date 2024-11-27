@extends('layouts.app')

@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('js/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/mdtimepicker.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/mdtimepicker-theme.css') }}">

    <style>
        .text-required {
            font-size: 18px;
            color: rgb(247, 28, 28);
        }

        .overview-table th,
        .overview-table td {
            padding: 5 !important;
            /* border: none !important; */
        }

        .dialer-input {
            height: calc(1.5em + 0.5rem + 4px);
            padding: 0.25rem 0.5rem;
            font-size: 0.765625rem;
            line-height: 1.5;
            border-radius: 4px;
        }
        .tab-button:focus,
        .tab-button:focus-visible{
            border: none !important;
            outline: none !important;
        }
        .custom-fields-table td{
            padding: 5px 0px !important;
            vertical-align: top;
            border-top: none !important;
            
        }
        .custom-fields-table tr td:first-child{
            padding-right: 5px !important;
        }
        
    </style>

@endpush



@section('content')

    <div class="panel panel-default">

        <div class="panel-heading clearfix">

            <div class="pull-left">
                <h4 class="mb-3">{{ !empty($campaign->name) ? $campaign->name : __('Campaign') }}</h4>
            </div>

            <div class="btn-group btn-group-sm pull-right" role="group">

                <a href="{{ route('dialer_campaigns.dialer_campaign.index') }}" class="btn btn-primary" title="{{ __('Show All  Campaign') }}">
                    <span class="fa fa-list" aria-hidden="true"></span>
                </a>

                <a href="{{ route('dialer_campaigns.dialer_campaign.create') }}" class="btn btn-primary" title="{{ __('Create New  Campaign') }}">
                    <span class="fa fa-plus" aria-hidden="true"></span>
                </a>

            </div>
        </div>

        <div class="panel-body">

             
    <div class="container-fluid text-center bg-primary" id="campaign_statusbar">
        <h6 class="text-light py-2" id="campaign_call_status">You are not connected</h6>
    </div> 

    <div class="row px-3">
        
        <div class="col-md-12 text-left">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-danger btn-sm d-none" id="skipToNext">
                    <i class="fa fa-forward"></i>
                    <span>Skip to Next</span>
                </button>
                <button type="button" class="btn btn-warning btn-sm d-none" id="retryDial">
                    <i class="fa fa-repeat"></i>
                    <span>Retry</span>
                </button>
                <button type="button" class="btn btn-info btn-sm pauseResume d-none" id="pauseDial">
                    <i class="fa fa-pause"></i>
                    <span> Pause Dialing</span>
                </button>
                <button type="button" class="btn btn-success btn-sm  pauseResume" id="resumeDial">
                    <i class="fa fa-play"></i>
                    <span> Start Dialing</span>
                </button>
                <button type="button" class="btn btn-info btn-sm d-none" id="startRecord">
                <i class="fa fa-microphone"></i>
                    <span> Start Recording</span>
                </button>
                <button type="button" class="btn btn-danger btn-sm" id="endRecord">
                <i class="fa fa-microphone-slash"></i>
                    <span> Stop Recording</span>
                </button>
            </div>
        </div>
    </div>

    <div class="table-responsive pt-2">
        <table class="table overview-table">
            <tr>
                <th class="text-center">Caller ID</th>
                <th class="text-center">Duration</th>
                <th class="text-center">Contacts</th>
                <th class="text-center">Calls</th>
                <th class="text-center">Talks</th>
               
            </tr>
            <tr>
                <td class="text-center" id="Campaign_callerId"></td>
                <td class="text-center" id="Campaign_duration">{{ $stats['total_duration'] }}</td>
                <td class="text-center">{{ $stats['total_contacts'] }}</td>
                <td class="text-center" id="Campaign_numCalls">{{ $stats['processed_contacts'] }}</td>
                <td class="text-center" id="Campaign_numTalks">{{ $stats['total_successfull']  }}</td>
                
            </tr>
        </table>
    </div>


    <div class="row pt-3 px-3">
        <div class="col-md-6">
            <div class="customer">
               
                <form method="post" id="contact_form">
                    @csrf 
                    
                    <input type="hidden" name="id" id="contact_id" value="">

                    <div class="row pt-1 pb-2">
                        <div class="col-md-6">
                            <input type="text" name="first_name" id="first_name" value="" class="form-control dialer-input"
                                placeholder="First name">
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="last_name" id="last_name" value="" class="form-control dialer-input"
                                placeholder="Last name">
                        </div>
                    </div>
                    
                    <div class="row pt-1 pb-2">
                        <div class="col-md-6">
                            <input type="text" name="gender" id="gender" value="" placeholder="Gender" class="form-control dialer-input">
                        </div>

                        <div class="col-md-6">
                            <input type="email" name="email" id="email" value="" placeholder="Email address"
                                class="form-control dialer-input">
                        </div>
                    </div>

                    <div class="pb-2">
                        <input type="text" name="address" id="address" value="" class="form-control dialer-input"
                            placeholder="Address">
                    </div>
                    <div class="row">
                        <div class="col-md-4 pb-2">
                            <input type="text" name="city" id="city" value="" class="form-control dialer-input"
                                placeholder="City">
                        </div>
                        <div class="col-md-4 pb-2">
                            <input type="text" name="state" id="state" value="" class="form-control dialer-input"
                                placeholder="State">
                        </div>
                        <div class="col-md-4 pb-2">
                            <input type="text" name="post_code" id="post_code" value="" class="form-control dialer-input"
                                placeholder="Post code">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="notes">Notes</label>
                        <textarea name="notes" id="notes" rows="5" value="" class="form-control dialer-input"></textarea>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-6">
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <button class="nav-link active btn-sm tab-button" id="nav-custom-fields-tab" data-toggle="tab" data-target="#nav-custom-fields"
                        type="button" role="tab" aria-controls="nav-custom-fields" aria-selected="true">Custom Fields</button>
                    <button class="nav-link btn-sm tab-button" id="nav-phone-scripts-tab" data-toggle="tab" data-target="#nav-phone-scripts"
                        type="button" role="tab" aria-controls="nav-phone-scripts"
                        aria-selected="false">Phone Scripts</button>
                    <button class="nav-link btn-sm tab-button" id="nav-sms-tab" data-toggle="tab" data-target="#nav-sms"
                        type="button" role="tab" aria-controls="nav-sms"
                        aria-selected="false">SMS</button>
                </div>
            </nav>
            <div class="tab-content pt-2" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-custom-fields">
                    <form action="javascript:void(0)" id="form_data_form">
                        @csrf
                        <input type="hidden" name="dcam_id" id="dcam_id" value="{{ $campaign->id }}">
                        <input type="hidden" name="caller_id" id="caller_id">
                    
                        <table class="table custom-fields-table"> 

                        </table>

                    </form>

                </div>
                <div class="tab-pane fade" id="nav-phone-scripts">
                    <div class="card">
                        <div class="card-body" id="phone-scripts">
                            
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="nav-sms">
                    <form action="" method="post">
                        <textarea name="body" id="sms_body" class="form-control" rows="7"></textarea>
                        <button id="btn_send_sms" class="btn btn-primary btn-sm mt-2" type="submit">Send SMS</button>
                        <button id="btn_send_ws" class="btn btn-primary btn-sm mt-2" type="submit">Send To Whatsapp</button>
                    </form>
                </div>
            </div>

        </div>
    </div>



        </div>
    </div>

@endsection

@push('script')
<script src="{{ asset('js/plugins/mdtimepicker.min.js') }}"></script>
<script src="{{ asset('js/flatpickr/flatpickr.js') }}"></script>

<script>
$(document).ready(function() {
    var calls = {{ $stats['processed_contacts'] }} ;
    var contacts;
    var talks;
    var paused = true;
    var record = true;
    var campaign_intime = '{{ $stats['in_time'] }}';

    var ndiali = null; 
    

    var interval =  parseInt("{{ $campaign->call_interval }}");
    var sec = 1;

    //console.log(campaign_intime);


    var formElements = @json(optional($campaign->form)->fields);
    formElements = JSON.parse(formElements)
    // console.log(formElements);

    buildForm(formElements);
    $(".date").flatpickr({
        dateFormat: "Y-m-d",
    });
    
    setTimeout(function(){       
        $('#dialerModal').popoverX('show');
    }, 1000);

    /* $(document).bind('keydown', 'ctrl+space', function(){
      //  console.log('dsfsdfsd');
      //  $('.pauseResume').click();
    }); */

    function updateStats(){
        $('#Campaign_numContact').text(contacts);
        //$('#Campaign_numCalls').text();

    }


    function startDial(){
        if(campaign_intime == 0){
            $('#campaign_call_status').text("{{ __("Campaign can run only on scheduled time") }}");
            $("#campaign_statusbar").addClass('bg-danger');
            $("#pauseDial").addClass('d-none');
           // $("#endDial").addClass('d-none');
            return;
        }

        $.get("{{ route('dialer_campaigns.dialer_campaign.get_contact', $campaign->id) }}" , function(data, status){
          //console.log(data);

          if(data['cam_status'] == 2){
            $('#campaign_call_status').text("{{ __("No more contacts found for call") }}");
            //$("#pauseDial").click();
            return;
          }

          $("#form_data_form").trigger('reset');
          $("#sms_body").val('');
          $("#contact_form").trigger('reset');

          $('#dialerModal').trigger('dial',[data.tel_no,record]);
        
          $("#caller_id").val(data.tel_no);
          $("#contact_id").val(data.id);

          $('#Campaign_callerId').text(data.tel_no);
        

          $("#first_name").val(data.first_name);
          $("#last_name").val(data.last_name);
          $("#gender").val(data.gender);
          $("#email").val(data.email);
          $("#address").val(data.address);
          $("#city").val(data.city);
          $("#state").val(data.state);
          $("#post_code").val(data.post_code);
          $("#notes").val(data.notes);
          $('#phone-scripts').html(data.script_content);
        
          $("#skipToNext").addClass('d-none');
          $('#pauseDial').removeClass('d-none');
          $("#resumeDial").addClass('d-none');
          $("#retryDial").addClass('d-none');
          
          contacts++;

        });        
    }

    $('#startRecord').click(function(){
        console.log('record');
        record = true;
        $(this).addClass('d-none');
        $('#endRecord').removeClass('d-none');
    });

    $('#endRecord').click(function(){
        console.log('record');
        record = false;
        $(this).addClass('d-none');
        $('#startRecord').removeClass('d-none');
    });

    $("#retryDial").click(function(){
        $('#dialerModal').trigger('dial',[$("#caller_id").val()]);
        $("#retryDial").addClass('d-none');
    });

    $('.pauseResume').click(function(){
        paused = (paused == true) ? false : true;

        if( paused){
            $('#pauseDial').addClass('d-none');
            $("#resumeDial").removeClass('d-none');
            if(paused) $('#campaign_call_status').text("{{ __("Dialing paused now") }}");
        }
        else{
            $("#resumeDial").addClass('d-none');
            $('#pauseDial').removeClass('d-none');
            //ndial();
        }
        
    }); 

    $("#skipToNext").click(function(){
        paused = false;
        
        if($("#btnhangup").hasClass('d-none') == false){
            $("#btnhangup").click();
        }

        $('#campaign_call_status').text("Skipped the call");
        sec = interval;

        
    })

    $("#endDial").click(function(){
        paused = true;
        $('#campaign_call_status').text("Dialer ended");

        if($("#btnhangup").hasClass('d-none') == false){
            $("#btnhangup").click();
        }

        setTimeout(() => {
            window.location.href = "{{ route('dialer_campaigns.dialer_campaign.index') }}"
        }, 1000);
    })


    $('#dialerModal').on("afterConnect", function(e,data){
        console.log("dialer connected");
        //startDial();
        $('#campaign_call_status').text("You are now connected");


        /* if(campaign_intime == 0){
            $('#campaign_call_status').text("The current time is outside the permitted schedule.");
            $("#campaign_statusbar").addClass('bg-danger');
            $("#pauseDial").addClass('d-none');
            $("#endDial").addClass('d-none');
        } */

        if(ndiali === null ){    
            ndiali = setInterval(ndial, 1000);
        }
        
        
    });
    
    
    
    function ndial() {
       // console.log('Run counter...',paused,campaign_intime);

        if( !paused ) {
            sec = sec -1;
            //console.log("sec",sec);
            if(sec >= 0){
                $("#campaign_statusbar").removeClass('bg-danger');
                $("#campaign_statusbar").addClass('bg-primary');
                $('#campaign_call_status').text("Dialing next call in " + sec +" sec ...");    
            }
            
            if( sec == 0 ) startDial();
        }else{
            //console.log("could not dial now");
        }
    }


    $('#dialerModal').on("dialStatus", function(e,data){
        console.log("got dial status");
        console.log(data);
         $.ajax({
            type: "POST",
            url: '{{ route('dialer_campaigns.dialer_campaign.update_campaign_call', $campaign->id) }}',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: data, //form.serialize(), // serializes the form's elements.
            success: function(data, message, xhr) {
                //$("#Campaign_duration").text(data.duration);
                $("#Campaign_duration").text(data.total_duration);
                $("#Campaign_numContact").text(data.processed_contacts);
                $("#Campaign_numCalls").text(data.processed_contacts);
                $("#Campaign_numTalks").text(data.total_successfull);
                campaign_intime = data.in_time;
            }
        });            
        

        $('#campaign_call_status').text('Call ' + data.status); 

        if(data["status-code"] >=3){
            sec = interval;
            $("#skipToNext").addClass('d-none');

            $("#campaign_statusbar").removeClass('bg-success');
            $("#campaign_statusbar").addClass('bg-danger');

            if(data["status-code"] >3) $("#retryDial").removeClass('d-none');


            if(ndiali === null) ndiali = setInterval(ndial, 1000);    
              
        }
        else{
            $("#skipToNext").removeClass('d-none');
            $("#campaign_statusbar").addClass('bg-success');
            $("#campaign_statusbar").removeClass('bg-danger');
        }


    });

    


        $("#btn_send_ws").click(function(e){
            e.preventDefault();

            console.log('clicked whatsapp button');

            var sms_body = $("#sms_body").val().trim();
            var telno = $("#Campaign_callerId").text().trim();

            var whatsapp_api_url = 'https://api.whatsapp.com/send?phone='+telno+'&text=' + sms_body;
            window.open(whatsapp_api_url, '_blank');
        });

        $("#btn_send_sms").click(function(e){
            e.preventDefault();
            
            console.log('clicked sms button');

            var sms_body = $("#sms_body").val().trim();
            var telno = $("#Campaign_callerId").text().trim();
            var from = 'EasyPbx';

            var sms_url = "{{ route('dialer_campaigns.dialer_campaign.send.sms') }}?from=" + from + "&to=" + telno + "&body=" + sms_body;

            $.get(sms_url, function(response){
                console.log(response);
                if(response.status){
                    showToast('Sms sent successfully');
                }
                else {
                    showToast('There was something went wrong!', false);
                }                
            })
            
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
        $("#form_data_form :input").change(function() {
        //$("#form_data_form .form-control").blur(function(){
            console.log('submitted');
            var requiredEmpty = false;




            $('input[required]').each(function() {
                if ($(this).val() === '') {
                    // showToast('A required field is empty!', false);
                    requiredEmpty = true;
                    return false; // Stops the loop if an empty field is found
                }
            });

            if(requiredEmpty){
                console.log('A required field is empty!');
                return false;
            }

            if( $("#caller_id").val().trim().length == 0){
                return false;
            }
    
            var formData = $('#form_data_form').serializeArray();
            console.log(formData);

            formData = formData.filter(function(field) {
                return field.value.trim() !== ''; // Keep only non-empty fields
            });

            var finalData = $.param(formData);


            $.ajax({
                type: "POST",
                url: '{{ route("dialer_campaigns.dialer_campaign.form_data") }}',
                data: finalData, // serializes the form's elements.
                success: function(data, message, xhr) {
                    console.log(data);

                    if(data.status == 'failed'){
                        showToast('There was something wrong! please try again', false);
                    }
                }
            });
        });

        $("#contact_form :input").change(function() {
       // $("#contact_form .form-control").blur(function(){
            if($("#contact_id").val() == '') return;
            var contactData = $('#contact_form').serializeArray();
            console.log(contactData);

            contactData = contactData.filter(function(field) {
                return field.value.trim() !== ''; // Keep only non-empty fields
            });

            var finalContactData = $.param(contactData);


            $.ajax({
                type: "POST",
                url: '{{ route("dialer_campaigns.dialer_campaign.update_contact") }}',
                data: finalContactData, // serializes the form's elements.
                success: function(data, message, xhr) {
                    console.log(data);

                    if(data.status == 'failed'){
                        showToast('There was something wrong! please try again', false);
                    }
                }
            });
        })

        

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


});
</script>
@endpush