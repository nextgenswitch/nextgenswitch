@csrf

<div class="form-group @error('from') has-error @enderror">
    {!! Form::label('from', __('From'), ['class' => 'control-label']) !!}

    <input type="number" name="from" id="from" class="form-control" required placeholder="Enter from agent"
        value="{{ old('from') }}">
    @error('from')
        <p class="help-block  text-danger"> {{ $message }} </p>
    @enderror
</div>

<div class="form-group @error('function_id') has-error @enderror">
    {!! Form::label('function_id', __('Last Destination'), ['class' => 'control-label']) !!}
    <span class="text-required">*</span>

    {!! Form::select('function_id', $functions, old('function_id'), [
        'class' => 'form-control',
        'required' => true,
        'placeholder' => __('Select module'),
    ]) !!}
    @error('function_id')
        <p class="help-block  text-danger"> {{ $message }} </p>
    @enderror
</div>

<div class="form-group @error('destination_id') has-error @enderror">
    {!! Form::label('destination_id', __('Destination'), ['class' => 'control-label']) !!}
    <span class="text-required">*</span>

    {!! Form::select('destination_id', $destinations, old('destination_id'), [
        'class' => 'form-control',
        'required' => true,
        'placeholder' => __('Select destination'),
    ]) !!}
    @error('destination_id')
        <p class="help-block  text-danger"> {{ $message }} </p>
    @enderror
</div>
