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

            {!! Form::text('name', old('name', optional($ivr)->name), [
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
        <div class="form-group @error('welcome_voice') has-error @enderror">
            {!! Form::label('welcome_voice', __('welcome voice'), ['class' => 'control-label']) !!}

                            {!! Form::select('welcome_voice', $voiceFiles, old('welcome_voice', optional($ivr)->welcome_voice), [
                                'class' => 'form-control selectpicker',
                                'data-live-search' => true,
                                'placeholder' => __('Nothing selected'),
                            ]) !!}
            @error('welcome_voice')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>
    

    <div class="col-lg-12">
        <div class="form-group @error('instruction_voice') has-error @enderror">
            {!! Form::label('instruction_voice', __('Instruction voice'), ['class' => 'control-label']) !!}

                            {!! Form::select('instruction_voice', $voiceFiles, old('instruction_voice', optional($ivr)->instruction_voice), [
                                'class' => 'form-control selectpicker',
                                'data-live-search' => true,
                                'placeholder' => __('Nothing selected'),
                            ]) !!}
            @error('instruction_voice')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>

    <div class="col-lg-12">
        <div class="form-group @error('invalid_voice') has-error @enderror">
            {!! Form::label('invalid_voice', __('Invalid voice'), ['class' => 'control-label']) !!}

                            {!! Form::select('invalid_voice', $voiceFiles, old('invalid_voice', optional($ivr)->invalid_voice), [
                                'class' => 'form-control selectpicker',
                                'data-live-search' => true,
                                'placeholder' => __('Nothing selected'),
                            ]) !!}
            @error('invalid_voice')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>

    <div class="col-lg-12">
        <div class="form-group @error('timeout_voice') has-error @enderror">
            {!! Form::label('timeout_voice', __('Timeout voice'), ['class' => 'control-label']) !!}

                            {!! Form::select('timeout_voice', $voiceFiles, old('timeout_voice', optional($ivr)->timeout_voice), [
                                'class' => 'form-control selectpicker',
                                'data-live-search' => true,
                                'placeholder' => __('Nothing selected'),
                            ]) !!}
            @error('timeout_voice')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>

    
    <div class="col-lg-12">
        <div class="form-group @error('timeout') has-error @enderror">
            {!! Form::label('timeout', __('Timeout'), ['class' => 'control-label']) !!}

            {!! Form::number('timeout',old('timeout', optional($ivr)->timeout  ), ['class' => 'form-control' . ($errors->has('timeout') ? ' is-invalid' : null), 'min' => '0', 'max' => '2147483647', 'required' => true, 'placeholder' => __('Enter timeout seconds here...'), ]) !!}

            @error('timeout')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>



</div>

@if (app('request')->ajax())
    <input type="submit" id="btnSubmit" class="d-none">
    </form>

    <script type="text/javascript">
        $(document).ready(function() {
            $('.selectpicker').selectpicker();

            $(document).on('change', '#welcome_voice', function() {
                var val = $(this).val();

                $(".tts").each((index, item) => {

                    if (val > 0) {
                        $(item).addClass('d-none');
                    } else {
                        $(item).removeClass('d-none');
                    }

                })


            })
        });
    </script>
@endif
