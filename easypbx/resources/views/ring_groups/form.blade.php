@if (app('request')->ajax())
    <form method="{{ $method }}" action="{{ $action }}" accept-charset="UTF-8" id="create_form"
        name="create_form" class="form-horizontal">
        @csrf
@endif

<div class="row">

    <div class="col-lg-6">
        <div class="form-group @error('code') has-error @enderror">
            {!! Form::label('code', __('Code'), ['class' => 'control-label']) !!}
            <span class="text-required">*</span>

            {!! Form::number('code', old('code', optional($ringGroup)->code), [
                'class' => 'form-control' . ($errors->has('code') ? ' is-invalid' : null),
                'min' => '0',
                'max' => '2147483647',
                'required' => true,
                'placeholder' => __('Enter code here...'),
            ]) !!}
            @error('code')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group @error('extensions') has-error @enderror">
            {!! Form::label('extensions', __('Extensions'), ['class' => 'control-label']) !!}

            {!! Form::select('extensions', $extensions, old('extensions', optional($ringGroup)->extensions), [
                'multiple' => 'multiple',
                'name' => 'extensions[]',
                'class' => 'form-control selectpicker' . ($errors->has('extensions') ? ' is-invalid' : null),
                'maxlength' => '100',
                'data-actions-box' => 'true',
            ]) !!}
            @error('extensions')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>
    <div class="col-lg-12">
        <div class="form-group @error('description') has-error @enderror">
            {!! Form::label('description', __('Description'), ['class' => 'control-label']) !!}
            <span class="text-required">*</span>

            {!! Form::text('description', old('description', optional($ringGroup)->description), [
                'class' => 'form-control' . ($errors->has('description') ? ' is-invalid' : null),
                'placeholder' => __('Enter description here...'),
                'required' => true,
            ]) !!}
            @error('description')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>



    <div class="col-lg-6">
        <div class="form-group @error('ring_strategy') has-error @enderror">
            {!! Form::label('ring_strategy', __('Ring Strategy'), ['class' => 'control-label']) !!}

            {!! Form::select(
                'ring_strategy',
                config('enums.ring_strategy'),
                old('ring_strategy', optional($ringGroup)->ring_strategy),
                ['class' => 'form-control', 'required' => true],
            ) !!}

            @error('ring_strategy')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>

    <div class="col-lg-6">
        <div class="form-group @error('ring_time') has-error @enderror">
            {!! Form::label('ring_time', __('Ring Time'), ['class' => 'control-label']) !!}
            <span class="text-required">*</span>

            {!! Form::select('ring_time', config('enums.ring_time'), old('ring_time', optional($ringGroup)->ring_time), [
                'class' => 'form-control',
                'required' => true,
            ]) !!}

            @error('ring_time')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>

    <div class="col-lg-6">
        <div class="form-group @error('answer_channel') has-error @enderror">
            {!! Form::label('answer_channel', __('Answer Channel'), ['class' => 'control-label']) !!}

            <div class="checkbox">
                <label for='answer_channel'>
                    {!! Form::checkbox(
                        'answer_channel',
                        '1',
                        old('answer_channel', optional($ringGroup)->answer_channel) == '1' ? true : null,
                        ['id' => 'answer_channel', 'class' => '' . ($errors->has('answer_channel') ? ' is-invalid' : null)],
                    ) !!}
                    {{ __('Yes') }}
                </label>
            </div>

            @error('answer_channel')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>


    <div class="col-lg-6">
        <div class="form-group @error('skip_busy_extension') has-error @enderror">
            {!! Form::label('skip_busy_extension', __('Skip Busy Extension'), ['class' => 'control-label']) !!}

            <div class="checkbox">
                <label for='skip_busy_extension'>
                    {!! Form::checkbox(
                        'skip_busy_extension',
                        '1',
                        old('skip_busy_extension', optional($ringGroup)->skip_busy_extension) == '1' ? true : null,
                        ['id' => 'skip_busy_extension', 'class' => '' . ($errors->has('skip_busy_extension') ? ' is-invalid' : null)],
                    ) !!}
                    {{ __('Yes') }}
                </label>
            </div>

            @error('skip_busy_extension')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>


    <div class="col-lg-6">
        <div class="form-group @error('allow_diversions') has-error @enderror">
            {!! Form::label('allow_diversions', __('Allow Diversions'), ['class' => 'control-label']) !!}

            <div class="checkbox">
                <label for='allow_diversions'>
                    {!! Form::checkbox(
                        'allow_diversions',
                        '1',
                        old('allow_diversions', optional($ringGroup)->allow_diversions) == '1' ? true : null,
                        ['id' => 'allow_diversions', 'class' => '' . ($errors->has('allow_diversions') ? ' is-invalid' : null)],
                    ) !!}
                    {{ __('Yes') }}
                </label>
            </div>

            @error('allow_diversions')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>


    <div class="col-lg-6">
        <div class="form-group @error('ringback_tone') has-error @enderror">
            {!! Form::label('ringback_tone', __('Ringback Tone'), ['class' => 'control-label']) !!}

            <div class="checkbox">
                <label for='ringback_tone'>
                    {!! Form::checkbox(
                        'ringback_tone',
                        '1',
                        old('ringback_tone', optional($ringGroup)->ringback_tone) == '1' ? true : null,
                        ['id' => 'ringback_tone', 'class' => '' . ($errors->has('ringback_tone') ? ' is-invalid' : null)],
                    ) !!}
                    {{ __('Yes') }}
                </label>
            </div>

            @error('ringback_tone')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>



    <div class="col-lg-6">
        <div class="form-group @error('function_id') has-error @enderror">
            {!! Form::label('function_id', __('Failed Destination'), ['class' => 'control-label']) !!}
            <span class="text-required">*</span>
            @php
                $func = isset($ringGroup->func->func) ? $ringGroup->func->func : '';
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
            

            {!! Form::select('destination_id', $destinations, old('destination_id', optional($ringGroup)->destination_id), [
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
</div>




@if (app('request')->ajax())
    <input type="submit" id="btnSubmit" class="d-none">
    </form>

    <script type="text/javascript">
        $(document).ready(function() {
            $('.selectpicker').selectpicker();
        });
    </script>
@endif
