@if(app('request')->ajax())
<form method="{{ $method }}" action="{{ $action }}" accept-charset="UTF-8" id="create_form" name="create_form" class="form-horizontal">
@csrf
@endif

<div class="row">
<div class="col-lg-12">
<div class="form-group @error('name') has-error @enderror">
    {!! Form::label('name',__('Name'),['class' => 'control-label']) !!}
    <span class="text-required">*</span>

{!! Form::text('name',old('name', optional($func)->name), ['class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : null), 'minlength' => '1', 'maxlength' => '255', 'required' => true, 'placeholder' => __('Enter name here...'), ]) !!}
        @error('name') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>


<div class="col-lg-12">
<div class="form-group @error('func') has-error @enderror">
    {!! Form::label('func',__('Func'),['class' => 'control-label']) !!}
    <span class="text-required">*</span>
  
{!! Form::text('func',old('func', optional($func)->func), ['class' => 'form-control' . ($errors->has('func') ? ' is-invalid' : null), 'minlength' => '1', 'maxlength' => '255', 'required' => true, 'placeholder' => __('Enter func here...'), ]) !!}
        @error('func') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>

<div class="col-lg-12">
<div class="form-group @error('func_type') has-error @enderror">
    {!! Form::label('func_type',__('Func Type'),['class' => 'control-label']) !!}
    <span class="text-required">*</span>
  
    {!! Form::text('func_type',old('func_type', optional($func)->func_type), ['class' => 'form-control' . ($errors->has('func_type') ? ' is-invalid' : null), 'minlength' => '1', 'required' => true, 'placeholder' => __('Enter func type here...'), ]) !!}
        @error('func_type') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>




</div>

@if(app('request')->ajax())
<input type="submit" id="btnSubmit" class="d-none">
</form>
@endif


