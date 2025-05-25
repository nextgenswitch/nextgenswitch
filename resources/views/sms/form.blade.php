@if (app('request')->ajax())
    <form method="{{ $method }}" action="{{ $action }}" accept-charset="UTF-8" id="create_form"
        name="create_form" class="form-horizontal">
        @csrf
@endif

<div class="row">

    <div class="col-lg-12">
        <div class="form-group @error('title') has-error @enderror">
            {!! Form::label('title', __('Title'), ['class' => 'control-label']) !!}
            <span class="text-required">*</span>

            {!! Form::text('title', old('title', optional($sms)->title), [
                'class' => 'form-control' . ($errors->has('title') ? ' is-invalid' : null),
                'required' => true,
                'placeholder' => __('Enter sms title here...'),
            ]) !!}
            @error('title')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>

    <div class="col-lg-12">
        <div class="form-group @error('content') has-error @enderror">
            {!! Form::label('content', __('Content'), ['class' => 'control-label']) !!}
            <span class="text-required">*</span>

            {!! Form::textarea('content', old('content', optional($sms)->content), [
                'class' => 'form-control',
                'required' => true,
                'placeholder' => __('Enter content here...'),
            ]) !!}
            @error('content')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>


</div>

@if (app('request')->ajax())
    <input type="submit" id="btnSubmit" class="d-none">
    </form>
@endif
