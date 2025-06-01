@if(app('request')->ajax())
<form method="{{ $method }}" action="{{ $action }}" accept-charset="UTF-8" id="create_form" name="create_form" class="form-horizontal">
@csrf
@endif

<div class="row">

<div class="col-lg-12">
<div class="form-group @error('title') has-error @enderror">
    {!! Form::label('title',__('Title'),['class' => 'control-label']) !!}
    <span class="text-required">*</span>
  
{!! Form::text('title',old('title', optional($api)->title), ['class' => 'form-control' . ($errors->has('title') ? ' is-invalid' : null), 'minlength' => '1', 'maxlength' => '255', 'required' => true, 'placeholder' => __('Enter title here...'), ]) !!}
        @error('title') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>


<div class="col-lg-6">
<div class="form-group @error('is_active') has-error @enderror">
    {!! Form::label('status', __('Active?'),['class' => 'control-label']) !!}

        <div class="checkbox">
            <label for='status'>
                {!! Form::checkbox('status', '1',  (old('status', optional($api)->status) == '1' ? true : null) , ['id' => 'status', 'class' => ''  . ($errors->has('status') ? ' is-invalid' : null), ]) !!}
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


