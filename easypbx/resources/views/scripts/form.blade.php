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

            {!! Form::text('name', old('name', optional($script)->name), [
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
        <div class="form-group @error('content') has-error @enderror">
            
            <div class="d-flex justify-content-between">
                <div> 
                    <label class="control-label" for="content">{{ __('Content') }}</label>
                    <span class="text-required">*</span>
                </div>
                <a href="#" data-toggle="modal" data-target="#instructionModal" class="btn btn-sm btn-outline-primary mb-2">Instruction</a>
            </div>
            

            {!! Form::textarea('content', old('content', optional($script)->content), [
                'class' => 'form-control' . ($errors->has('content') ? ' is-invalid' : null),
                'minlength' => '1',
                'id' => 'content',
                'maxlength' => '16777215',
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
