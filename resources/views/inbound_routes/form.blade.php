@if (app('request')->ajax())
    <form method="{{ $method }}" action="{{ $action }}" accept-charset="UTF-8" id="create_form"
        name="create_form" class="form-horizontal">
        @csrf
@endif

<div class="row">
    <div class="col-lg-12">
        <div class="form-group @error('name') has-error @enderror">
            {!! Form::label('name', __('Name'), ['class' => 'control-label']) !!}
            <span class="text-required">*</span>

            {!! Form::text('name', old('name', optional($inboundRoute)->name), [
                'class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : null),
                'minlength' => '1',
                'maxlength' => '255',
                'required' => true,
                'placeholder' => __('Enter name here...'),
            ]) !!}
            @error('name')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>

    <div class="col-lg-12">
        <div class="form-group @error('did_pattern') has-error @enderror">
            {!! Form::label('did_pattern', __('Did Pattern'), ['class' => 'control-label']) !!}
            <span class="text-required">*</span>

            {!! Form::text('did_pattern', old('did_pattern', optional($inboundRoute)->did_pattern), [
                'class' => 'form-control' . ($errors->has('did_pattern') ? ' is-invalid' : null),
                'minlength' => '1',
                'maxlength' => '255',
                'required' => true,
                'placeholder' => __('Enter did pattern here...'),
            ]) !!}
            @error('did_pattern')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>

    <div class="col-lg-12">
        <div class="form-group @error('cid_pattern') has-error @enderror">
            {!! Form::label('cid_pattern', __('Cid Pattern'), ['class' => 'control-label']) !!}

            {!! Form::text('cid_pattern', old('cid_pattern', optional($inboundRoute)->cid_pattern), [
                'class' => 'form-control' . ($errors->has('cid_pattern') ? ' is-invalid' : null),
                'minlength' => '1',
                'maxlength' => '255',
                'placeholder' => __('Enter cid pattern here...'),
            ]) !!}
            @error('cid_pattern')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>

    <div class="col-lg-6">
        <div class="form-group @error('function_id') has-error @enderror">
            {!! Form::label('function_id', __('Inbound Destination'), ['class' => 'control-label']) !!}
            <span class="text-required">*</span>
            @php
                $func = isset($inboundRoute->func->func) ? $inboundRoute->func->func : '';
            @endphp
            {!! Form::select('function_id', $functions, old('function_id', $func), [
                'class' => 'form-control',
                'required' => true,
                'placeholder' => __('Select module'),
            ]) !!}
            @error('function_id')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>

    <div class="col-lg-6">
        <div class="form-group @error('destination_id') has-error @enderror">
            {!! Form::label('destination_id', __('&nbsp;'), ['class' => 'control-label']) !!}
            

            {!! Form::select('destination_id', $destinations, old('destination_id', optional($inboundRoute)->destination_id), [
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
