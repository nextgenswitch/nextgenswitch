@if(app('request')->ajax())
<form method="{{ $method }}" action="{{ $action }}" accept-charset="UTF-8" id="create_form" name="create_form" class="form-horizontal">
@csrf
@endif

<div class="row">



<div class="col-lg-12">
<div class="form-group @error('name') has-error @enderror">
    {!! Form::label('name',__('Customer Name'),['class' => 'control-label']) !!}
  
    {!! Form::text('name',old('name', optional($ticket)->name), ['class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : null), 'maxlength' => '191', 'placeholder' => __('Enter name here...'), ]) !!}
    @error('name') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>

<div class="col-lg-12">
<div class="form-group @error('phone') has-error @enderror">
    {!! Form::label('phone',__('Phone'),['class' => 'control-label']) !!}
    <span class="text-required">*</span>
  
    {!! Form::text('phone',old('phone', optional($ticket)->phone), ['class' => 'form-control' . ($errors->has('phone') ? ' is-invalid' : null), 'minlength' => '1', 'maxlength' => '191', 'required' => true, 'placeholder' => __('Enter phone here...'), ]) !!}
    @error('phone') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>

<div class="col-lg-12">
<div class="form-group @error('subject') has-error @enderror">
    {!! Form::label('subject',__('Subject'),['class' => 'control-label']) !!}
    <span class="text-required">*</span>
  
    {!! Form::text('subject',old('subject', optional($ticket)->subject), ['class' => 'form-control' . ($errors->has('subject') ? ' is-invalid' : null), 'minlength' => '1', 'maxlength' => '191', 'required' => true, 'placeholder' => __('Enter subject here...'), ]) !!}
    @error('subject') <p class="help-block  text-danger"> {{ $message }} </p> @enderror

</div>
</div>

<div class="col-lg-12">
<div class="form-group @error('description') has-error @enderror">
    {!! Form::label('description',__('Description'),['class' => 'control-label']) !!}
    <span class="text-required">*</span>
  
        {!! Form::textarea('description', old('description', optional($ticket)->description), ['class' => 'form-control', 'required' => true, ]) !!}
        @error('description') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>


<div class="col-lg-12">
<div class="form-group @error('user_id') has-error @enderror">
    {!! Form::label('user_id',__('Assign User'),['class' => 'control-label']) !!}
  
        {!! Form::select('user_id',$users, old('user_id', optional($ticket)->user_id ? optional($ticket)->user_id : auth()->id()), ['class' => 'form-control', 'placeholder' => __('Select user'), ]) !!}
        @error('user_id') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>



<div class="col-lg-12">
<div class="form-group @error('status') has-error @enderror">
    {!! Form::label('status',__('Status'),['class' => 'control-label']) !!}
  
        {!! Form::select('status',config('enums.ticket_status'),old('status', optional($ticket)->status ? optional($ticket)->status : '1' ), ['class' => 'form-control', 'placeholder' => __('Select status'), ]) !!}
        @error('status') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>


</div>

@if(app('request')->ajax())
<input type="submit" id="btnSubmit" class="d-none">
</form>
@endif


