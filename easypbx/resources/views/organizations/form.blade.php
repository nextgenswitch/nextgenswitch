@if(app('request')->ajax())
<form method="{{ $method }}" action="{{ $action }}" accept-charset="UTF-8" id="create_form" name="create_form" class="form-horizontal">
@csrf
@endif

<div class="row">



<div class="col-lg-12">
<div class="form-group @error('name') has-error @enderror">
    {!! Form::label('name',__('Name'),['class' => 'control-label']) !!}
    <span class="text-required">*</span>
  
{!! Form::text('name',old('name', optional($organization)->name), ['class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : null), 'minlength' => '1', 'maxlength' => '255', 'required' => true, 'placeholder' => __('Enter name here...'), ]) !!}
        @error('name') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>

<div class="col-lg-12">
<div class="form-group @error('domain') has-error @enderror">
    {!! Form::label('domain',__('Domain'),['class' => 'control-label']) !!}
    <span class="text-required">*</span>
  
{!! Form::text('domain',old('domain', optional($organization)->domain), ['class' => 'form-control' . ($errors->has('domain') ? ' is-invalid' : null), 'minlength' => '1', 'maxlength' => '255', 'required' => true, 'placeholder' => __('Enter domain here...'), ]) !!}
        @error('domain') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>

<div class="col-lg-12">
<div class="form-group @error('contact_no') has-error @enderror">
    {!! Form::label('contact_no',__('Contact No'),['class' => 'control-label']) !!}
    <span class="text-required">*</span>
  
{!! Form::text('contact_no',old('contact_no', optional($organization)->contact_no), ['class' => 'form-control' . ($errors->has('contact_no') ? ' is-invalid' : null), 'minlength' => '1', 'maxlength' => '255', 'required' => true, 'placeholder' => __('Enter contact no here...'), ]) !!}
        @error('contact_no') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>


<div class="col-lg-12">
    <div class="form-group @error('address') has-error @enderror">
        {!! Form::label('address',__('Address'),['class' => 'control-label']) !!}
        <span class="text-required">*</span>
      
            {!! Form::textarea('address', old('address', optional($organization)->address), ['class' => 'form-control', 'rows' => 2, 'required' => true, 'placeholder' => __('Enter address here...'), ]) !!}
            @error('address') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
    </div>
    </div>

<div class="col-lg-12">
<div class="form-group @error('plan_id') has-error @enderror">
    {!! Form::label('plan_id',__('Plan'),['class' => 'control-label']) !!}
  
        {!! Form::select('plan_id',$plans,old('plan_id', optional($organization)->plan_id), ['class' => 'form-control', 'required' => false, 'placeholder' => __('Select plan'), ]) !!}
        @error('plan_id') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>
    
<div class="col-lg-12">
<div class="form-group @error('email') has-error @enderror">
    {!! Form::label('email',__('Email'),['class' => 'control-label']) !!}
    <span class="text-required">*</span>
  
{!! Form::text('email',old('email', optional($organization)->email), ['class' => 'form-control' . ($errors->has('email') ? ' is-invalid' : null), 'minlength' => '1', 'maxlength' => '255', 'required' => true, 'placeholder' => __('Enter email here...'), ]) !!}
        @error('email') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>



@if(!$organization)
<div class="col-lg-12">
<div class="form-group @error('password') has-error @enderror">
    {!! Form::label('password',__('password'),['class' => 'control-label']) !!}
    <span class="text-required">*</span>
  
    {!! Form::text('password',old('password', optional($organization)->password), ['class' => 'form-control' . ($errors->has('password') ? ' is-invalid' : null), 'required' => true, 'placeholder' => __('Enter password here...') ]) !!}
        @error('password') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>
@endif

</div>

@if(app('request')->ajax())
<input type="submit" id="btnSubmit" class="d-none">
</form>
@endif


