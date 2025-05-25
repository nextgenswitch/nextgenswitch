@if(app('request')->ajax())
<form method="{{ $method }}" action="{{ $action }}" accept-charset="UTF-8" id="create_form" name="create_form" class="form-horizontal">
@csrf
@endif

<div class="row">


<div class="col-lg-12">
    <div class="form-group @error('title') has-error @enderror">
        {!! Form::label('title',__('Title'),['class' => 'control-label']) !!}
        
      
        {!! Form::text('title',old('title', optional($flow)->title), ['class' => 'form-control' . ($errors->has('title') ? ' is-invalid' : null), 'maxlength' => '191', 'placeholder' => __('Enter title here...'), ]) !!}
            @error('title') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
    </div>
</div>
    
<div class="col-lg-12">
    <div class="form-group @error('voice_file') has-error @enderror">
        {!! Form::label('voice_file',__('Voice File'),['class' => 'control-label']) !!}
        
        {!! Form::select('voice_file',$voices,old('voice_file', optional($flow)->voice_file), ['class' => 'form-control', 'placeholder' => __('Select select voice file'), ]) !!}
    
            @error('voice_file') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
    </div>
</div>


<div class="col-lg-12">
<div class="form-group @error('match_type') has-error @enderror">
    {!! Form::label('match_type',__('Match Type'),['class' => 'control-label']) !!}
  
        {!! Form::select('match_type',config('enums.flow_match_type'),old('match_type', optional($flow)->match_type), ['class' => 'form-control', 'placeholder' => __('Select match type'), ]) !!}
        @error('match_type') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>


<div class="col-lg-12">
<div class="form-group @error('matched_value') has-error @enderror">
    {!! Form::label('matched_value',__('Match Value'),['class' => 'control-label']) !!}
    <span class="text-required">*</span>
  
{!! Form::text('matched_value',old('matched_value', optional($flow)->matched_value), ['class' => 'form-control' . ($errors->has('matched_value') ? ' is-invalid' : null), 'minlength' => '1', 'maxlength' => '191', 'required' => true, 'placeholder' => __('Enter matched value here...'), ]) !!}
        @error('matched_value') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>



<div class="col-lg-12">
    <div class="form-group @error('matched_action') has-error @enderror">
        {!! Form::label('matched_action',__('Matched Action'),['class' => 'control-label']) !!}
      
            {!! Form::select('matched_action',$flowActions,old('matched_action', optional($flow)->matched_action), ['class' => 'form-control', 'placeholder' => __('Select matched action'), ]) !!}
            @error('matched_action') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
    </div>
    </div>

    
<div class="col-lg-12">
    <div class="form-group @error('unmatched_action') has-error @enderror">
        {!! Form::label('unmatched_action',__('Unmatched Action'),['class' => 'control-label']) !!}
      
            {!! Form::select('unmatched_action',$flowActions,old('unmatched_action', optional($flow)->unmatched_action), ['class' => 'form-control', 'placeholder' => __('Select unmatched action'), ]) !!}
            @error('unmatched_action') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
    </div>
    </div>
</div>

@if(app('request')->ajax())
<input type="submit" id="btnSubmit" class="d-none">
</form>
@endif


