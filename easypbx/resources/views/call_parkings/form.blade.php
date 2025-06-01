@if(app('request')->ajax())
<form method="{{ $method }}" action="{{ $action }}" accept-charset="UTF-8" id="create_form" name="create_form" class="form-horizontal">
@csrf
@endif

<div class="row">

<div class="col-lg-12">
<div class="form-group @error('name') has-error @enderror">
    {!! Form::label('name',__('Name'),['class' => 'control-label']) !!}
  
{!! Form::text('name',old('name', optional($callParking)->name), ['class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : null), 'minlength' => '1', 'maxlength' => '191', 'required' => true, 'placeholder' => __('Enter name here...'), ]) !!}
        @error('name') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>

<div class="col-lg-12">
<div class="form-group @error('extension_no') has-error @enderror">
    {!! Form::label('extension_no',__('Extension No'),['class' => 'control-label']) !!}
  
{!! Form::number('extension_no',old('extension_no', optional($callParking)->extension_no ), ['class' => 'form-control' . ($errors->has('extension_no') ? ' is-invalid' : null), 'min' => '100', 'max' => '2147483647', 'required' => true, 'placeholder' => __('Enter extension no here...'), ]) !!}
        @error('extension_no') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>

<div class="col-lg-12">
<div class="form-group @error('no_of_slot') has-error @enderror">
    {!! Form::label('no_of_slot',__('No Of Slot'),['class' => 'control-label']) !!}
  
{!! Form::number('no_of_slot',old('no_of_slot', optional($callParking)->no_of_slot ? $callParking->no_of_slot : '10'), ['class' => 'form-control' . ($errors->has('no_of_slot') ? ' is-invalid' : null), 'min' => '-2147483648', 'max' => '2147483647', 'required' => true, 'placeholder' => __('Enter no of slot here...'), ]) !!}
        @error('no_of_slot') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>

<div class="col-lg-12">
    <div class="form-group @error('music_on_hold') has-error @enderror">
        {!! Form::label('music_on_hold', __('Music On Hold'), ['class' => 'control-label']) !!}
        <span class="text-required">*</span>
        <div class="input-group voice-preview">
            {!! Form::select('music_on_hold', $voices, old('music_on_hold', optional($callParking)->music_on_hold), [
                'class' => 'form-control',
                'required' => true,
                'placeholder' => __('Select voice'),
            ]) !!}

            <div class="input-group-append">
                <button class="btn btn-outline-secondary play" type="button">
                    <i class="fa fa-play"></i>
                </button>

                <button class="btn btn-outline-secondary stop d-none" type="button">
                    <i class="fa fa-stop"></i>
                </button>

            </div>

        </div>
        @error('music_on_hold')
            <p class="help-block  text-danger"> {{ $message }} </p>
        @enderror
    </div>
</div>

<div class="col-lg-6">
<div class="form-group @error('timeout') has-error @enderror">
    {!! Form::label('timeout',__('Timeout'),['class' => 'control-label']) !!}
  
        {!! Form::number('timeout',old('timeout', optional($callParking)->timeout ? $callParking->timeout : '60'), ['class' => 'form-control' . ($errors->has('timeout') ? ' is-invalid' : null), 'min' => '0', 'max' => '2147483647', 'required' => true, 'placeholder' => __('Enter timeout here...'), ]) !!}
        @error('timeout') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>

<div class="col-lg-6">
    <div class="form-group @error('record') has-error @enderror">
        {!! Form::label('record',__('Record'),['class' => 'control-label']) !!}
    
            <div class="checkbox">
                <label for='record'>
                    {!! Form::checkbox('record', '1',  (old('record', optional($callParking)->record) == '1' ? true : null) , ['id' => 'record', 'class' => ''  . ($errors->has('record') ? ' is-invalid' : null), ]) !!}
                    {{ __('Yes') }}
                </label>
            </div>

            @error('record') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
    </div>
</div>

<div class="col-lg-6">
        <div class="form-group @error('function_id') has-error @enderror">
            {!! Form::label('function_id', __('Last Destination'), ['class' => 'control-label']) !!}
            <span class="text-required">*</span>
            @php
                $func = isset($callParking->function->func) ? $callParking->function->func : '';
            @endphp

            {!! Form::select('function_id', $functions, old('function_id', $func), [
                'class' => 'form-control',
                'required' => true,
                'placeholder' => __('Select Module'),
            ]) !!}
            @error('function_id')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>

    <div class="col-lg-6">
        <div class="form-group @error('destination_id') has-error @enderror">
            {!! Form::label('destination_id', '&nbsp;', ['class' => 'control-label']) !!}
            
            {!! Form::select(
                'destination_id',
                $destinations,
                old('destination_id', optional($callParking)->destination_id),
                ['class' => 'form-control', 'required' => true, 'placeholder' => __('Select destination')],
            ) !!}
            @error('destination_id')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>

</div>

@if(app('request')->ajax())
<input type="submit" id="btnSubmit" class="d-none">
</form>
@endif


