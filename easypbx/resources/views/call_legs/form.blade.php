@if(app('request')->ajax())
<form method="{{ $method }}" action="{{ $action }}" accept-charset="UTF-8" id="create_form" name="create_form" class="form-horizontal">
@csrf
@endif

<div class="row">

<div class="col-lg-6">
<div class="form-group @error('call_id') has-error @enderror">
    {!! Form::label('call_id',__('Call'),['class' => 'control-label']) !!}
    <span class="text-required">*</span>
  
        {!! Form::select('call_id',$calls,old('call_id', optional($callLeg)->call_id), ['class' => 'form-control', 'required' => true, 'placeholder' => __('Select call'), ]) !!}
        @error('call_id') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>

<div class="col-lg-6">
<div class="form-group @error('channel') has-error @enderror">
    {!! Form::label('channel',__('Channel'),['class' => 'control-label']) !!}
    <span class="text-required">*</span>
  
{!! Form::text('channel',old('channel', optional($callLeg)->channel), ['class' => 'form-control' . ($errors->has('channel') ? ' is-invalid' : null), 'minlength' => '1', 'maxlength' => '255', 'required' => true, 'placeholder' => __('Enter channel here...'), ]) !!}
        @error('channel') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>

<div class="col-lg-6">
<div class="form-group @error('sip_user_id') has-error @enderror">
    {!! Form::label('sip_user_id',__('Sip User'),['class' => 'control-label']) !!}
    <span class="text-required">*</span>
  
        {!! Form::select('sip_user_id',$sipUsers,old('sip_user_id', optional($callLeg)->sip_user_id), ['class' => 'form-control', 'required' => true, 'placeholder' => __('Select sip user'), ]) !!}
        @error('sip_user_id') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>

<div class="col-lg-6">
<div class="form-group @error('call_status') has-error @enderror">
    {!! Form::label('call_status',__('Call Status'),['class' => 'control-label']) !!}
    <span class="text-required">*</span>
  
{!! Form::text('call_status',old('call_status', optional($callLeg)->call_status), ['class' => 'form-control' . ($errors->has('call_status') ? ' is-invalid' : null), 'minlength' => '1', 'required' => true, 'placeholder' => __('Enter call status here...'), ]) !!}
        @error('call_status') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>

<div class="col-lg-6">
<div class="form-group @error('connect_time') has-error @enderror">
    {!! Form::label('connect_time',__('Connect Time'),['class' => 'control-label']) !!}
    <span class="text-required">*</span>
  
{!! Form::text('connect_time',old('connect_time', optional($callLeg)->connect_time), ['class' => 'form-control' . ($errors->has('connect_time') ? ' is-invalid' : null), 'required' => true, 'placeholder' => __('Enter connect time here...'), ]) !!}
        @error('connect_time') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>

<div class="col-lg-6">
<div class="form-group @error('ringing_time') has-error @enderror">
    {!! Form::label('ringing_time',__('Ringing Time'),['class' => 'control-label']) !!}
    <span class="text-required">*</span>
  
{!! Form::text('ringing_time',old('ringing_time', optional($callLeg)->ringing_time), ['class' => 'form-control' . ($errors->has('ringing_time') ? ' is-invalid' : null), 'required' => true, 'placeholder' => __('Enter ringing time here...'), ]) !!}
        @error('ringing_time') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>

<div class="col-lg-6">
<div class="form-group @error('establish_time') has-error @enderror">
    {!! Form::label('establish_time',__('Establish Time'),['class' => 'control-label']) !!}
    <span class="text-required">*</span>
  
{!! Form::text('establish_time',old('establish_time', optional($callLeg)->establish_time), ['class' => 'form-control' . ($errors->has('establish_time') ? ' is-invalid' : null), 'required' => true, 'placeholder' => __('Enter establish time here...'), ]) !!}
        @error('establish_time') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>

<div class="col-lg-6">
<div class="form-group @error('disconnect_time') has-error @enderror">
    {!! Form::label('disconnect_time',__('Disconnect Time'),['class' => 'control-label']) !!}
    <span class="text-required">*</span>
  
{!! Form::text('disconnect_time',old('disconnect_time', optional($callLeg)->disconnect_time), ['class' => 'form-control' . ($errors->has('disconnect_time') ? ' is-invalid' : null), 'required' => true, 'placeholder' => __('Enter disconnect time here...'), ]) !!}
        @error('disconnect_time') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>

<div class="col-lg-6">
<div class="form-group @error('duration') has-error @enderror">
    {!! Form::label('duration',__('Duration'),['class' => 'control-label']) !!}
  
{!! Form::number('duration',old('duration', optional($callLeg)->duration), ['class' => 'form-control' . ($errors->has('duration') ? ' is-invalid' : null), 'min' => '0', 'max' => '2147483647', 'required' => true, 'placeholder' => __('Enter duration here...'), ]) !!}
        @error('duration') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>

</div>

@if(app('request')->ajax())
<input type="submit" id="btnSubmit" class="d-none">
</form>
@endif


