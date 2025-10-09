@if (app('request')->ajax())
    <form method="{{ $method }}" action="{{ $action }}" accept-charset="UTF-8" id="create_form"
        name="create_form" class="form-horizontal">
        @csrf
@endif



<div class="form-group @error('name') has-error @enderror">
    {!! Form::label('name', 'Name', ['class' => 'control-label']) !!}
    {!! Form::text('name', old('name', optional($stream)->name), [
        'class' => 'form-control',
        'required' => true,
        'placeholder' => __('Enter stream name here...'),
    ]) !!}
    @error('name')
        <p class="help-block text-danger">{{ $message }}</p>
    @enderror
</div>
<div class="form-group @error('ws_url') has-error @enderror">
    {!! Form::label('ws_url', 'Web Socket URL', ['class' => 'control-label']) !!}
    {!! Form::text('ws_url', old('ws_url', optional($stream)->ws_url), [
        'class' => 'form-control',
        'required' => true,
        'placeholder' => __('ws://127.0.0.1:8765/ws'),
    ]) !!}
    @error('ws_url')
        <p class="help-block text-danger">{{ $message }}</p>
    @enderror
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="form-group @error('prompt') has-error @enderror">
            {!! Form::label('prompt',__('Prompt'),['class' => 'control-label']) !!}
            {!! Form::textarea('prompt', old('prompt', optional($stream)->prompt), [
                'class' => 'form-control' . ($errors->has('prompt') ? ' is-invalid' : null),
                'placeholder' => __('Enter prompt or instructions here...'),
            ]) !!}
            @error('prompt') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="form-group @error('prompt') has-error @enderror">
            {!! Form::label('greetings',__('Greetings'),['class' => 'control-label']) !!}
            {!! Form::text('greetings', old('greetings', optional($stream)->greetings), [
                'class' => 'form-control' . ($errors->has('greetings') ? ' is-invalid' : null),
                'placeholder' => __('Enter greetings message here...'),
            ]) !!}
           
            @error('greetings') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-lg-12">
        <div class="form-group @error('extra_parameters') has-error @enderror">
            {!! Form::label('extra_parameters', __('Extra Parameters'), ['class' => 'control-label']) !!}
            {!! Form::textarea('extra_parameters', old('extra_parameters', optional($stream)->extra_parameters), [
                'class' => 'form-control' . ($errors->has('extra_parameters') ? ' is-invalid' : null),
                'placeholder' => __('Enter key=value parameters here...'),
                'rows' => 4
            ]) !!}
            @error('extra_parameters') <p class="help-block text-danger"> {{ $message }} </p> @enderror
        </div>
    </div>
</div>
<div class="form-group @error('max_call_duration') has-error @enderror">
    {!! Form::label('max_call_duration', 'Max Call Duration (seconds)', ['class' => 'control-label']) !!}
    {!! Form::number('max_call_duration', old('max_call_duration', optional($stream)->max_call_duration), [
        'class' => 'form-control',
        'placeholder' => __('Enter max call duration in seconds...'),
        'min' => 0,
    ]) !!}
    @error('max_call_duration')
        <p class="help-block text-danger">{{ $message }}</p>
    @enderror
</div>
<div class="form-group @error('record') has-error @enderror">
    <div class="form-check">
        {!! Form::checkbox('record', '1', old('record', optional($stream)->record), [
            'class' => 'form-check-input',
            'id' => 'record',
        ]) !!}
        {!! Form::label('record', 'Record Call', ['class' => 'form-check-label']) !!}
    </div>
    @error('record')
        <p class="help-block text-danger">{{ $message }}</p>
    @enderror
</div>


<div class="form-group @error('forwarding_number') has-error @enderror">
    {!! Form::label('forwarding_number', 'Forwarding Number', ['class' => 'control-label']) !!}
    {!! Form::text('forwarding_number', old('forwarding_number', optional($stream)->forwarding_number), [
        'class' => 'form-control',
        'placeholder' => __('Enter forwarding number here...'),
    ]) !!}
    @error('forwarding_number')
        <p class="help-block text-danger">{{ $message }}</p>
    @enderror
</div>
<!-- <div class="row">
    <div class="col-lg-12">
        <div class="form-group @error('email') has-error @enderror">
            {!! Form::label('email',__('Email'),['class' => 'control-label']) !!}
            {!! Form::email('email', old('email', optional($stream)->email), [
                'class' => 'form-control' . ($errors->has('email') ? ' is-invalid' : null),
                'placeholder' => __('Enter email for sending transcript...'),
            ]) !!}
          
            @error('email') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
        </div>
    </div>
</div> -->

<div class="row mt-3">
<div class="col-lg-6">
    <div class="form-group @error('function_id') has-error @enderror">
        {!! Form::label('function_id', __('Failed Destination'), ['class' => 'control-label']) !!}
        <span class="text-required">*</span>
        @php
            $func = isset($stream->function->func) ? $stream->function->func : '';
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
        {!! Form::label('destination_id', __('&nbsp;'), ['class' => 'control-label']) !!}
        

        {!! Form::select('destination_id', $destinations, old('destination_id', optional($stream)->destination_id), [
            'class' => 'form-control',
            'required' => true,
            'placeholder' => __('Select destination'),
        ]) !!}
        @error('destination_id')
            <p class="help-block  text-danger"> {{ $message }} </p>
        @enderror
    </div>
</div>
</div>

@if (app('request')->ajax())
    <input type="submit" id="btnSubmit" class="d-none">
    </form>
@endif

