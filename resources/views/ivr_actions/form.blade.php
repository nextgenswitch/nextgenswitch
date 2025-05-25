@if(app('request')->ajax())
<form method="{{ $method }}" action="{{ $action }}" accept-charset="UTF-8" id="create_form" name="create_form" class="form-horizontal">
@csrf
@endif

<div class="row">


    <input type="hidden" name="ivr_id" id="ivr_id" value="{{ optional($ivrAction)->ivr_id }}">

{{-- <div class="col-lg-12">
<div class="form-group @error('ivr_id') has-error @enderror">
    {!! Form::label('ivr_id',__('Ivr'),['class' => 'control-label']) !!}
    <span class="text-required">*</span>
  
        {!! Form::select('ivr_id',$ivrs,old('ivr_id', optional($ivrAction)->ivr_id), ['class' => 'form-control', 'required' => true, 'ivr_action_id' => optional($ivrAction)->id, 'placeholder' => __('Select ivr'), ]) !!}
        @error('ivr_id') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div> --}}


<div class="col-lg-12">
<div class="form-group @error('digit') has-error @enderror">
    {!! Form::label('digit',__('Digit'),['class' => 'control-label']) !!}
    <span class="text-required">*</span>
  
        {!! Form::select('digit', $digits, old('digit', optional($ivrAction)->digit), ['class' => 'form-control', 'required' => true, 'placeholder' => __('Select ivr digit'), ]) !!}
        @error('digit') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>

@php
    $func = isset($ivrAction->func->func) ? $ivrAction->func->func : ''
@endphp

<div class="col-lg-12">
    <div class="form-group @error('function_id') has-error @enderror">
        {!! Form::label('function_id',__('Last Destination'),['class' => 'control-label']) !!}
        <span class="text-required">*</span>

            {!! Form::select('function_id', $functions ,old('function_id', $func), ['class' => 'form-control', 'required' => true, 'placeholder' => __('Select module'), ]) !!}
            @error('function_id') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
    </div>
</div>


<div class="col-lg-12">
<div class="form-group @error('destination_id') has-error @enderror">
    {!! Form::label('destination_id',__('Destination'),['class' => 'control-label']) !!}
    <span class="text-required">*</span>

        {!! Form::select('destination_id',$destinations, old('destination_id', optional($ivrAction)->destination_id), ['class' => 'form-control', 'required' => true, 'placeholder' => __('Select destination'), ]) !!}
        @error('destination_id') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>



</div>

@if(app('request')->ajax())
<input type="submit" id="btnSubmit" class="d-none">
</form>
@endif


