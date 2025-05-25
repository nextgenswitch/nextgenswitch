@if (app('request')->ajax())
    <form method="{{ $method }}" action="{{ $action }}" accept-charset="UTF-8" id="create_form"
        name="create_form" class="form-horizontal">
        @csrf
@endif

<div class="row">
    <input type="hidden" name="call_queue_id" value="{{ $call_queue->id }}">

    <div class="col-lg-12">
        <div class="form-group @error('extension_id') has-error @enderror">
            {!! Form::label('extension_id', __('Extension'), ['class' => 'control-label']) !!}
            <span class="text-required">*</span>

            {!! Form::select('extension_id', $extensions, old('extension_id', optional($callQueueExtension)->extension_id), [
                'class' => 'form-control',
                'required' => true,
                'placeholder' => __('Select extension'),
            ]) !!}
            @error('extension_id')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>

    <div class="col-lg-12">
        <div class="form-group @error('member_type') has-error @enderror">
            {!! Form::label('member_type', __('Member Type'), ['class' => 'control-label']) !!}
            <span class="text-required">*</span>

            {!! Form::number('member_type', old('member_type', optional($callQueueExtension)->member_type), [
                'class' => 'form-control' . ($errors->has('member_type') ? ' is-invalid' : null),
                'min' => '0',
                'max' => '2147483647',
                'required' => true,
                'placeholder' => __('Enter member type here...'),
            ]) !!}
            @error('member_type')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>

    <div class="col-lg-12">
        <div class="form-group @error('priority') has-error @enderror">
            {!! Form::label('priority', __('Priority'), ['class' => 'control-label']) !!}
            <span class="text-required">*</span>

            {!! Form::number('priority', old('priority', optional($callQueueExtension)->priority), [
                'class' => 'form-control' . ($errors->has('priority') ? ' is-invalid' : null),
                'min' => '0',
                'max' => '2147483647',
                'required' => true,
                'placeholder' => __('Enter priority here...'),
            ]) !!}
            @error('priority')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>

    <div class="col-lg-12">
        <div class="form-group @error('allow_diversion') has-error @enderror">
            {!! Form::label('allow_diversion', __('Allow Diversion'), ['class' => 'control-label']) !!}

            <div class="checkbox">
                <label for='allow_diversion'>
                    {!! Form::checkbox(
                        'allow_diversion',
                        '1',
                        old('allow_diversion', optional($callQueueExtension)->allow_diversion) == '1' ? true : null,
                        ['id' => 'allow_diversion', 'class' => '' . ($errors->has('allow_diversion') ? ' is-invalid' : null)],
                    ) !!}
                    {{ __('True') }}
                </label>
            </div>

            @error('allow_diversion')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>

</div>

@if (app('request')->ajax())
    <input type="submit" id="btnSubmit" class="d-none">
    </form>
@endif
