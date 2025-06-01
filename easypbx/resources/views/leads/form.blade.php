@if(app('request')->ajax())
    <form method="{{ $method }}" action="{{ $action }}" accept-charset="UTF-8" id="create_form" name="create_form" class="form-horizontal">
    @csrf
@endif

<div class="row">



<div class="col-lg-6">
<div class="form-group @error('name') has-error @enderror">
    {!! Form::label('name',__('Name'),['class' => 'control-label']) !!}
    <span class="text-required">*</span>
  
    {!! Form::text('name',old('name', optional($lead)->name), ['class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : null), 'minlength' => '1', 'maxlength' => '191', 'required' => true, 'placeholder' => __('Enter name here...'), ]) !!}
    @error('name') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>

<div class="col-lg-6">
<div class="form-group @error('designation') has-error @enderror">
    {!! Form::label('designation',__('Designation'),['class' => 'control-label']) !!}
  
{!! Form::text('designation',old('designation', optional($lead)->designation), ['class' => 'form-control' . ($errors->has('designation') ? ' is-invalid' : null), 'maxlength' => '191', 'placeholder' => __('Enter designation here...'), ]) !!}
        @error('designation') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>

<div class="col-lg-6">
<div class="form-group @error('phone') has-error @enderror">
    {!! Form::label('phone',__('Phone'),['class' => 'control-label']) !!}
    <span class="text-required">*</span>
  
{!! Form::text('phone',old('phone', optional($lead)->phone), ['class' => 'form-control' . ($errors->has('phone') ? ' is-invalid' : null), 'minlength' => '1', 'maxlength' => '191', 'required' => true, 'placeholder' => __('Enter phone here...'), ]) !!}
        @error('phone') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>

<div class="col-lg-6">
<div class="form-group @error('email') has-error @enderror">
    {!! Form::label('email',__('Email'),['class' => 'control-label']) !!}
  
{!! Form::text('email',old('email', optional($lead)->email), ['class' => 'form-control' . ($errors->has('email') ? ' is-invalid' : null), 'maxlength' => '191', 'placeholder' => __('Enter email here...'), ]) !!}
        @error('email') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>

<div class="col-lg-6">
<div class="form-group @error('website') has-error @enderror">
    {!! Form::label('website',__('Website'),['class' => 'control-label']) !!}
  
{!! Form::text('website',old('website', optional($lead)->website), ['class' => 'form-control' . ($errors->has('website') ? ' is-invalid' : null), 'maxlength' => '191', 'placeholder' => __('Enter website here...'), ]) !!}
        @error('website') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>

<div class="col-lg-6">
<div class="form-group @error('company') has-error @enderror">
    {!! Form::label('company',__('Company'),['class' => 'control-label']) !!}
  
{!! Form::text('company',old('company', optional($lead)->company), ['class' => 'form-control' . ($errors->has('company') ? ' is-invalid' : null), 'maxlength' => '191', 'placeholder' => __('Enter company here...'), ]) !!}
        @error('company') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>

<div class="col-lg-6">
<div class="form-group @error('address') has-error @enderror">
    {!! Form::label('address',__('Address'),['class' => 'control-label']) !!}
  
        {!! Form::textarea('address', old('address', optional($lead)->address), ['class' => 'form-control', 'rows' =>'5', 'placeholder' => __('Enter address here...'), ]) !!}
        @error('address') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>



<div class="col-lg-6">
<div class="form-group @error('notes') has-error @enderror">
    {!! Form::label('notes',__('Notes'),['class' => 'control-label']) !!}
  
        {!! Form::textarea('notes', old('notes', optional($lead)->notes), ['class' => 'form-control', 'rows' =>'5', ]) !!}
        @error('notes') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>

<div class="col-lg-6">
<div class="form-group @error('source') has-error @enderror">
    {!! Form::label('source',__('Source'),['class' => 'control-label']) !!}
    <!-- {!! Form::select('source', config('enums.lead_source'), old('source', optional($lead)->source), ['class' => 'form-control', 'placeholder' => __('Select Lead source')]) !!} -->

    {!! Form::select('source', $sources, old('source', optional($lead)->source), [
        'multiple' => false,
        'name' => 'source',
        'class' => 'form-control ' . ($errors->has('source') ? ' is-invalid' : null),
        'maxlength' => '100',
        'required' => true,
        'placeholder' => 'Select Source here',
        'data-actions-box' => 'true',
    ]) !!}


    @error('source') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>




<div class="col-lg-6">
<div class="form-group @error('status') has-error @enderror">
    {!! Form::label('status',__('Status'),['class' => 'control-label']) !!}
    <span class="text-required">*</span>

    <!-- {!! Form::select('status', config('enums.lead_status'), old('status', optional($lead)->status), ['class' => 'form-control', 'required' => true,  'placeholder' => __('Select Lead Status')]) !!} -->
  
    {!! Form::select('status', $statuses, old('status', optional($lead)->status), [
        'multiple' => false,
        'name' => 'status',
        'class' => 'form-control ' . ($errors->has('status') ? ' is-invalid' : null),
        'maxlength' => '100',
        'required' => true,
        'placeholder' => 'Select status here',
        'data-actions-box' => 'true',
    ]) !!}

        @error('status') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>

</div>

@if(app('request')->ajax())
<input type="submit" id="btnSubmit" class="d-none">
</form>
@endif


