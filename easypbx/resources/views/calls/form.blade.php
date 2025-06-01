@if(app('request')->ajax())
<form method="{{ $method }}" action="{{ $action }}" accept-charset="UTF-8" id="create_form" name="create_form" class="form-horizontal">
@csrf
@endif

<div class="row">

<div class="col-lg-6">
<div class="form-group @error('organization_id') has-error @enderror">
    {!! Form::label('organization_id',__('Organization'),['class' => 'control-label']) !!}
    <span class="text-required">*</span>
  
        {!! Form::select('organization_id',$organizations,old('organization_id', optional($call)->organization_id), ['class' => 'form-control', 'required' => true, 'placeholder' => __('Select organization'), ]) !!}
        @error('organization_id') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>

<div class="col-lg-6">
<div class="form-group @error('channel') has-error @enderror">
    {!! Form::label('channel',__('Channel'),['class' => 'control-label']) !!}
    <span class="text-required">*</span>
  
{!! Form::text('channel',old('channel', optional($call)->channel), ['class' => 'form-control' . ($errors->has('channel') ? ' is-invalid' : null), 'minlength' => '1', 'maxlength' => '255', 'required' => true, 'placeholder' => __('Enter channel here...'), ]) !!}
        @error('channel') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>

<div class="col-lg-6">
<div class="form-group @error('sip_user_id') has-error @enderror">
    {!! Form::label('sip_user_id',__('Sip User'),['class' => 'control-label']) !!}
    <span class="text-required">*</span>
  
        {!! Form::select('sip_user_id',$sipUsers,old('sip_user_id', optional($call)->sip_user_id), ['class' => 'form-control', 'required' => true, 'placeholder' => __('Select sip user'), ]) !!}
        @error('sip_user_id') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>

<div class="col-lg-6">
<div class="form-group @error('call_status') has-error @enderror">
    {!! Form::label('call_status',__('Call Status'),['class' => 'control-label']) !!}
    <span class="text-required">*</span>
  
{!! Form::text('call_status',old('call_status', optional($call)->call_status), ['class' => 'form-control' . ($errors->has('call_status') ? ' is-invalid' : null), 'minlength' => '1', 'required' => true, 'placeholder' => __('Enter call status here...'), ]) !!}
        @error('call_status') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>

<div class="col-lg-6">
<div class="form-group @error('connect_time') has-error @enderror">
    {!! Form::label('connect_time',__('Connect Time'),['class' => 'control-label']) !!}
    <span class="text-required">*</span>
  
{!! Form::text('connect_time',old('connect_time', optional($call)->connect_time), ['class' => 'form-control' . ($errors->has('connect_time') ? ' is-invalid' : null), 'required' => true, 'placeholder' => __('Enter connect time here...'), ]) !!}
        @error('connect_time') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>

<div class="col-lg-6">
<div class="form-group @error('ringing_time') has-error @enderror">
    {!! Form::label('ringing_time',__('Ringing Time'),['class' => 'control-label']) !!}
    <span class="text-required">*</span>
  
{!! Form::text('ringing_time',old('ringing_time', optional($call)->ringing_time), ['class' => 'form-control' . ($errors->has('ringing_time') ? ' is-invalid' : null), 'required' => true, 'placeholder' => __('Enter ringing time here...'), ]) !!}
        @error('ringing_time') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>

<div class="col-lg-6">
<div class="form-group @error('establish_time') has-error @enderror">
    {!! Form::label('establish_time',__('Establish Time'),['class' => 'control-label']) !!}
    <span class="text-required">*</span>
  
{!! Form::text('establish_time',old('establish_time', optional($call)->establish_time), ['class' => 'form-control' . ($errors->has('establish_time') ? ' is-invalid' : null), 'required' => true, 'placeholder' => __('Enter establish time here...'), ]) !!}
        @error('establish_time') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>

<div class="col-lg-6">
<div class="form-group @error('disconnect_time') has-error @enderror">
    {!! Form::label('disconnect_time',__('Disconnect Time'),['class' => 'control-label']) !!}
    <span class="text-required">*</span>
  
{!! Form::text('disconnect_time',old('disconnect_time', optional($call)->disconnect_time), ['class' => 'form-control' . ($errors->has('disconnect_time') ? ' is-invalid' : null), 'required' => true, 'placeholder' => __('Enter disconnect time here...'), ]) !!}
        @error('disconnect_time') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>

<div class="col-lg-6">
<div class="form-group @error('duration') has-error @enderror">
    {!! Form::label('duration',__('Duration'),['class' => 'control-label']) !!}
    <span class="text-required">*</span>
  
{!! Form::number('duration',old('duration', optional($call)->duration), ['class' => 'form-control' . ($errors->has('duration') ? ' is-invalid' : null), 'min' => '0', 'max' => '2147483647', 'required' => true, 'placeholder' => __('Enter duration here...'), ]) !!}
        @error('duration') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>

<div class="col-lg-6">
<div class="form-group @error('user_agent') has-error @enderror">
    {!! Form::label('user_agent',__('User Agent'),['class' => 'control-label']) !!}
    <span class="text-required">*</span>
  
{!! Form::number('user_agent',old('user_agent', optional($call)->user_agent), ['class' => 'form-control' . ($errors->has('user_agent') ? ' is-invalid' : null), 'min' => '0', 'max' => '2147483647', 'required' => true, 'placeholder' => __('Enter user agent here...'), ]) !!}
        @error('user_agent') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>

<div class="col-lg-6">
<div class="form-group @error('uas') has-error @enderror">
    {!! Form::label('uas',__('Uas'),['class' => 'control-label']) !!}
  
        <div class="checkbox">
            <label for='uas_1'>
                {!! Form::checkbox('uas', '1',  (old('uas', optional($call)->uas) == '1' ? true : null) , ['id' => 'uas_1', 'class' => ''  . ($errors->has('uas') ? ' is-invalid' : null), ]) !!}
                {{ __('Yes') }}
            </label>
        </div>

        @error('uas') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>

</div>

@if(app('request')->ajax())
<input type="submit" id="btnSubmit" class="d-none">
</form>
@endif


