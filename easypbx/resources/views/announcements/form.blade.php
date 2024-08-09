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

            {!! Form::text('name', old('name', optional($announcement)->name), [
                'class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : null),
                'minlength' => '1',
                'maxlength' => '191',
                'required' => true,
                'placeholder' => __('Enter name here...'),
            ]) !!}
            @error('name')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>

    <div class="col-lg-12">
        <div class="form-group @error('voice_id') has-error @enderror">
            {!! Form::label('voice_id', __('Voice'), ['class' => 'control-label']) !!}
            <span class="text-required">*</span>
            <div class="input-group voice-preview">
                {!! Form::select('voice_id', $voices, old('voice_id', optional($announcement)->voice_id), [
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
            @error('voice_id')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>

    <div class="col-lg-6">
        <div class="form-group @error('function_id') has-error @enderror">
            {!! Form::label('function_id', __('Destination after playing announcement'), ['class' => 'control-label']) !!}
            <span class="text-required">*</span>
            @php
                $func = isset($announcement->function->func) ? $announcement->function->func : '';
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
                old('destination_id', optional($announcement)->destination_id),
                ['class' => 'form-control', 'required' => true, 'placeholder' => __('Select destination')],
            ) !!}
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