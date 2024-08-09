<div class="col-lg-6 column">
    <div class="form-group @error('function_id') has-error @enderror">
        {!! Form::label('function_id', __('Destination'), ['class' => 'control-label']) !!}
        <span class="text-required">*</span>
        
        {!! Form::select('function_id', $functions, old('function_id', $func), [
            'class' => 'form-control function_id',
            'required' => true,
            'placeholder' => __('Select module'),
        ]) !!}
        @error('function_id')
            <p class="help-block  text-danger"> {{ $message }} </p>
        @enderror
    </div>
</div>

<div class="col-lg-6 column">
    <div class="form-group @error('destination_id') has-error @enderror">
        {!! Form::label('destination_id', __('&nbsp;'), ['class' => 'control-label']) !!}
        
        
        {!! Form::select('destination_id', $destinations, old('destination_id', $destination_id), [
            'class' => 'form-control destination_id',
            'required' => true,
            'placeholder' => __('Select destination'),
        ]) !!}
        @error('destination_id')
            <p class="help-block  text-danger"> {{ $message }} </p>
        @enderror
    </div>
</div>

@push('script')
<script src="{{ asset('js/func_destination.js') }}"> </script>
@endpush