@if(app('request')->ajax())
<form method="{{ $method }}" action="{{ $action }}" accept-charset="UTF-8" id="create_form" name="create_form" class="form-horizontal">
@csrf
@endif

<div class="row">

    <div class="col-lg-12">
        <div class="form-group @error('title') has-error @enderror">
            {!! Form::label('title',__('Title'),['class' => 'control-label']) !!}
            <span class="text-required">*</span>
          
        {!! Form::text('title',old('title', optional($flowAction)->title), ['class' => 'form-control' . ($errors->has('title') ? ' is-invalid' : null), 'minlength' => '1', 'maxlength' => '191', 'required' => true, 'placeholder' => __('Enter title here...'), ]) !!}
                @error('title') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
        </div>
        </div>


<div class="col-lg-12">
<div class="form-group @error('action_type') has-error @enderror">
    {!! Form::label('action_type',__('Action Type'),['class' => 'control-label']) !!}
    <span class="text-required">*</span>

    {!! Form::select('action_type',config('enums.flow_action_type'),old('action_type', optional($flowAction)->action_type), ['class' => 'form-control', 'required' => true ]) !!}
        @error('action_type') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>


<div class="col-lg-12">
<div class="form-group @error('action_value') has-error @enderror">
    {!! Form::label('action_value',__('Action Value'),['class' => 'control-label']) !!}
    <span class="text-required">*</span>

    {{-- {!! Form::select('voice_file',$voices,old('voice_file', optional($flow)->voice_file), ['class' => 'form-control', 'placeholder' => __('Select select voice file'), ]) !!} --}}
    
{!! Form::text('action_value',old('action_value', optional($flowAction)->action_value), ['class' => 'form-control' . ($errors->has('action_value') ? ' is-invalid' : null), 'minlength' => '1', 'maxlength' => '191', 'required' => true, 'placeholder' => __('Enter action value here...'), ]) !!}



        @error('action_value') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>


</div>

@if(app('request')->ajax())
<input type="submit" id="btnSubmit" class="d-none">
</form>
@endif


