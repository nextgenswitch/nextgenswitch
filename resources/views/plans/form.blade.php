@if(app('request')->ajax())
<form method="{{ $method }}" action="{{ $action }}" accept-charset="UTF-8" id="create_form" name="create_form" class="form-horizontal">
@csrf
@endif

<div class="row">

<div class="col-lg-12">
<div class="form-group @error('name') has-error @enderror">
    {!! Form::label('name',__('Name'),['class' => 'control-label']) !!}
  
{!! Form::text('name',old('name', optional($plan)->name), ['class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : null), 'minlength' => '1', 'maxlength' => '191', 'required' => true, 'placeholder' => __('Enter name here...'), ]) !!}
        @error('name') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>

<div class="col-lg-12">
<div class="form-group @error('duration') has-error @enderror">
    {!! Form::label('duration',__('Duration(days)'),['class' => 'control-label']) !!}
  
{!! Form::number('duration',old('duration', optional($plan)->duration), ['class' => 'form-control' . ($errors->has('duration') ? ' is-invalid' : null), 'min' => '-2147483648', 'max' => '2147483647', 'required' => true, 'placeholder' => __('Enter duration here...'), ]) !!}
        @error('duration') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>

<div class="col-lg-12">
<div class="form-group @error('price') has-error @enderror">
    {!! Form::label('price',__('Price'),['class' => 'control-label']) !!}
  
{!! Form::number('price',old('price', optional($plan)->price), ['class' => 'form-control' . ($errors->has('price') ? ' is-invalid' : null), 'min' => '-999', 'max' => '999', 'required' => true, 'placeholder' => __('Enter price here...'),'step' => "any", ]) !!}
        @error('price') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>

<!-- <div class="col-lg-12">
<div class="form-group @error('credit') has-error @enderror">
    {!! Form::label('credit',__('Credit'),['class' => 'control-label']) !!}
  
{!! Form::number('credit',old('credit', optional($plan)->credit), ['class' => 'form-control' . ($errors->has('credit') ? ' is-invalid' : null), 'min' => '-999', 'max' => '999', 'required' => true, 'placeholder' => __('Enter credit here...'),'step' => "any", ]) !!}
        @error('credit') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div> -->

</div>

@if(app('request')->ajax())
<input type="submit" id="btnSubmit" class="d-none">
</form>
@endif


