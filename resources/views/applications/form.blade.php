@if(app('request')->ajax())
<form method="{{ $method }}" action="{{ $action }}" accept-charset="UTF-8" id="create_form" name="create_form" class="form-horizontal">
@csrf
@endif

<div class="row">

<div class="col-lg-12">
<div class="form-group @error('name') has-error @enderror">
    {!! Form::label('name',__('Name'),['class' => 'control-label']) !!}
    <span class="text-required">*</span>

{!! Form::text('name',old('name', optional($application)->name), ['class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : null), 'minlength' => '1', 'maxlength' => '255', 'required' => true, 'placeholder' => __('Enter name here...'), ]) !!}
        @error('name') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>

<div class="col-lg-12">
<div class="form-group @error('code') has-error @enderror">
    {!! Form::label('code',__('Code'),['class' => 'control-label']) !!}
    <span class="text-required">*</span>
  
{!! Form::number('code',old('code', optional($application)->code), ['class' => 'form-control' . ($errors->has('code') ? ' is-invalid' : null), 'min' => '0', 'max' => '2147483647', 'required' => true, 'placeholder' => __('Enter code here...'), ]) !!}
        @error('code') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>



<div class="col-lg-6">
<div class="form-group @error('function_id') has-error @enderror">
    
    {!! Form::label('function_id',__('Last Destination'),['class' => 'control-label']) !!}
    <span class="text-required">*</span>
  

@php
    $func = isset($application->func->func) ? $application->func->func : ''
@endphp

        {!! Form::select('function_id',$functions,old('function_id', $func), ['class' => 'form-control', 'required' => true, 'placeholder' => __('Select Module'), ]) !!}
        @error('function_id') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>

<div class="col-lg-6">
<div class="form-group @error('destination_id') has-error @enderror">
    {!! Form::label('destination_id',__('&nbsp;'),['class' => 'control-label']) !!}
    

        {!! Form::select('destination_id',$destinations, old('destination_id', optional($application)->destination_id), ['class' => 'form-control', 'required' => true, 'placeholder' => __('Select destination'), ]) !!}
        @error('destination_id') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>




<div class="col-lg-6">
<div class="form-group @error('status') has-error @enderror">
    {!! Form::label('status', __('Active?'),['class' => 'control-label']) !!}

        <div class="checkbox">
            <label for='status'>
                {!! Form::checkbox('status', '1',  (old('status', optional($application)->status) == '1' ? true : null) , ['id' => 'status', 'class' => ''  . ($errors->has('status') ? ' is-invalid' : null), ]) !!}
                {{ __('Yes') }}
            </label>
        </div>

        @error('status') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>


</div>


@if(app('request')->ajax())
<input type="submit" id="btnSubmit" class="d-none">
</form>
@endif




