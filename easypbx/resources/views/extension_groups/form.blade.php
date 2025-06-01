@if(app('request')->ajax())
<form method="{{ $method }}" action="{{ $action }}" accept-charset="UTF-8" id="create_form" name="create_form" class="form-horizontal">
@csrf
@endif

<div class="row">


<div class="col-lg-12">
    <div class="form-group @error('name') has-error @enderror">
        {!! Form::label('name',__('Name'),['class' => 'control-label']) !!}
        <span class="text-required">*</span>

    {!! Form::text('name',old('name', optional($extensionGroup)->name), ['class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : null), 'minlength' => '1', 'maxlength' => '255', 'required' => true, 'placeholder' => __('Enter name here...'), ]) !!}
            @error('name') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
    </div>
</div>


<div class="col-lg-12">
<div class="form-group @error('extension_id') has-error @enderror">
    {!! Form::label('extension_id',__('Extension'),['class' => 'control-label']) !!}
  
        {!! Form::select('extension_id',$extensions,old('extension_id', optional($extensionGroup)->extension_id), ['multiple'=>'multiple','name'=>'extension_id[]','class' => 'form-control selectpicker' . ($errors->has('extension_id') ? ' is-invalid' : null), 'maxlength' => '100','data-actions-box'=>"true" ]) !!}
        @error('extension_id') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>

<div class="col-lg-12">
<div class="form-group @error('algorithm') has-error @enderror">
    {!! Form::label('algorithm',__('Algorithm'),['class' => 'control-label']) !!}
    <span class="text-required">*</span>
  
{!! Form::select('algorithm', config('enums.algorithm'), old('algorithm', optional($extensionGroup)->algorithm), ['class' => 'form-control', 'required' => true, 'placeholder' => __('Select algorithm'), ]) !!}
        @error('algorithm') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>

</div>

@if(app('request')->ajax())
<input type="submit" id="btnSubmit" class="d-none">
</form>

<script type="text/javascript">
    $( document ).ready(function() {
        $('.selectpicker').selectpicker();
    });

</script>

@endif


