@if(app('request')->ajax())
<form method="{{ $method }}" action="{{ $action }}" accept-charset="UTF-8" id="create_form" name="create_form" class="form-horizontal">
@csrf
@endif

<div class="row">
<div class="col-lg-12">
<div class="form-group @error('name') has-error @enderror">
    {!! Form::label('name',__('Name'),['class' => 'control-label']) !!}
    <span class="text-required">*</span>
  
{!! Form::text('name',old('name', optional($pinList)->name), ['class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : null), 'minlength' => '1', 'maxlength' => '191', 'required' => true, 'placeholder' => __('Enter name here...'), ]) !!}
        @error('name') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>

<div class="col-lg-12">
<div class="form-group @error('pin_list') has-error @enderror">
    {!! Form::label('pin_list',__('Pins'),['class' => 'control-label']) !!}
    <span class="text-required">*</span>
  
        {!! Form::textarea('pin_list', old('pin_list', optional($pinList)->pin_list), ['class' => 'form-control', 'required' => true, 'placeholder' => __('Enter pin list here...'), ]) !!}
        @error('pin_list') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>

</div>

@if(app('request')->ajax())
<input type="submit" id="btnSubmit" class="d-none">
</form>
@endif


