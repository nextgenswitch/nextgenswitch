@if(app('request')->ajax())
<form method="{{ $method }}" action="{{ $action }}" accept-charset="UTF-8" id="create_form" name="create_form" class="form-horizontal">
@csrf
@endif

<div class="row">

<div class="col-lg-12">
<div class="form-group @error('name') has-error @enderror">
    {!! Form::label('name',__('Name'),['class' => 'control-label']) !!}
    <span class="text-required">*</span>

{!! Form::text('name',old('name', optional($hotdesk)->name), ['class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : null), 'minlength' => '1', 'maxlength' => '255', 'required' => true, 'placeholder' => __('Enter name here...'), ]) !!}
        @error('name') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>

</div>

<div class="card mb-2">
        <div class="card-header">{{ __('SIP User Info') }}</div>
        <div class="card-body row">

            @php($sip = isset($hotdesk->sipuser) ? $hotdesk->sipuser: null)

            @if(isset($sip->id)) <input type="hidden" name="sip_user_id" value="{{ $sip->id }}"> @endif
            <div class="col-lg-12">
                <div class="form-group @error('username') has-error @enderror">
                        {!! Form::label('username',__('Username'),['class' => 'control-label']) !!}
                        <span class="text-required">*</span>

                        {!! Form::text('username',old('username', optional($sip)->username), ['class' => 'form-control' . ($errors->has('username') ? ' is-invalid' : null), 'minlength' => '1', 'maxlength' => '255', 'required' => true, 'placeholder' => __('Enter username here...'), ]) !!}
                        @error('username') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
                </div>
            </div>

            <div class="col-lg-12">
                <div class="form-group @error('password') has-error @enderror">
                        {!! Form::label('password',__('Password'),['class' => 'control-label']) !!}
                        <span class="text-required">*</span>

                        {!! Form::text('password',old('password', optional($sip)->password), ['class' => 'form-control' . ($errors->has('password') ? ' is-invalid' : null), 'minlength' => '1', 'maxlength' => '255', 'required' => true, 'placeholder' => __('Enter password here...'), ]) !!}
                        @error('password') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
                </div>
            </div>

            <div class="col-lg-12">
                <div class="form-group">
                    <input type="hidden" name="allow_ip" id="allow_ip_hidden_field" value="{{ old('allow_ip', optional($sip)->allow_ip) }}">
                    <label for="">Allow IP <small> (Leave blank for allow any IP) </small> </label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Enter allow ip address" id="add_allow_ip">
                        <button class="btn btn-primary" type="button" id="btn_add_ip">
                            <i class="fa fa-plus"></i>
                        </button>
                    </div>
                    <div id="ip_contents"></div>
                </div>
            </div>

        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="form-group @error('call_limit') has-error @enderror">
                {!! Form::label('call_limit',__('Call Limit'),['class' => 'control-label']) !!}
    
            {!! Form::number('call_limit',old('call_limit', optional($sip)->call_limit), ['class' => 'form-control' . ($errors->has('call_limit') ? ' is-invalid' : null), 'min' => '0', 'required' => false, 'placeholder' => __('Enter call limit here...'), ]) !!}
                    @error('call_limit') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
            </div>
        </div>
    </div>
    

@if(app('request')->ajax())
<input type="submit" id="btnSubmit" class="d-none">
</form>

<script type="text/javascript">
    $( document ).ready(function() {
        $('.selectpicker').selectpicker();
        var ipv4_address = $('#add_allow_ip');
        ipv4_address.inputmask({
            alias: "ip",
            greedy: false 
        });



        render()
        
        $(document).on('click', '#btn_add_ip', function(){
            // console.log('clicked add btn');
            var old = $("#allow_ip_hidden_field").val().trim();

            old = old.length > 0 ? old.split(',') : [];
            
            var ip = $("#add_allow_ip").val().trim();
            // console.log(ip);

            if (ip.length > 0 && !old.includes(ip)) {
                old.unshift(ip);

                $("#allow_ip_hidden_field").val(old.join(','));
                render();

                $("#add_allow_ip").val('');
            }

        })

        $(document).on("click", '.btn-danger', function(){
            // console.log('click delete btn');
            
            var old = $("#allow_ip_hidden_field").val().split(',');

            var val = $(this).prev().val().trim();
            
            if(old.includes(val)){
                old = old.filter(item => item !== val);
                $("#allow_ip_hidden_field").val(old.join(','));
            }

            render();

        })

        function render(){
            var html = '';
            var old = $("#allow_ip_hidden_field").val().trim();
            if(old.length > 0 ){
                old.split(',').forEach((item, index) => {
                    html += '<div class="input-group mb-1"> <input type="text" value="'+ item +'" readonly class="form-control"> <button class="btn btn-danger" type="button"> <i class="fa fa-times"></i> </button></div>';
                })
            }
            $("#ip_contents").html(html);
            
        }
        
    });

</script>
@endif


