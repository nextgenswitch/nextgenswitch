@if(app('request')->ajax())
<form method="{{ $method }}" action="{{ $action }}" accept-charset="UTF-8" id="create_form" name="create_form" class="form-horizontal">
@csrf
@endif

<div class="row">


<div class="col-lg-12">
<div class="form-group @error('name') has-error @enderror">
    {!! Form::label('name',__('Name'),['class' => 'control-label']) !!}
    <span class="text-required">*</span>
  
{!! Form::text('name',old('name', optional($timeGroup)->name), ['class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : null), 'minlength' => '1', 'maxlength' => '191', 'required' => true, 'placeholder' => __('Enter name here...'), ]) !!}
        @error('name') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>



<div class="col-lg-12">
    <div class="form-group @error('time_zone') has-error @enderror">
        {!! Form::label('time_zone', __('Timezone'), ['class' => 'control-label']) !!}
        <span class="text-required">*</span>

        {!! Form::select('time_zone', config('enums.timezones'), old('time_zone', optional($timeGroup)->time_zone), [
            'class' => 'form-control selectpicker' . ($errors->has('time_zone') ? ' is-invalid' : null),
            'minlength' => '1',
            'maxlength' => '100',
            'required' => true,
            'data-live-search' => 'true',
            'placeholder' => __('select timezone here...'),
        ]) !!}
        @error('time_zone')
            <p class="help-block  text-danger"> {{ $message }} </p>
        @enderror
    </div>
</div>


<div class="col-lg-12 mb-3">

    @include('time_groups.schedule', ['schedules' => []])
</div>


</div>

@if(app('request')->ajax())
<input type="submit" id="btnSubmit" class="d-none">
</form>

@endif


