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
@endif

