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

            {!! Form::text('name', old('name', optional($timeCondition)->name), [
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
        <div class="form-group @error('time_group_id') has-error @enderror">
            {!! Form::label('time_group_id', __('Time Group'), ['class' => 'control-label']) !!}
            <span class="text-required">*</span>

            {!! Form::select('time_group_id', $timeGroups, old('time_group_id', optional($timeCondition)->time_group_id), [
                'class' => 'form-control',
                'required' => true,
                'placeholder' => __('Select time group'),
            ]) !!}

            @error('time_group_id')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>



    <div class="col-lg-6 column">
        <div class="form-group @error('matched_function_id') has-error @enderror">
            {!! Form::label('matched_function_id', __('Destination if matched'), ['class' => 'control-label']) !!}
            <span class="text-required">*</span>
            @php
                $funcName = isset($timeCondition->matchedFunc->func) ? $timeCondition->matchedFunc->func : '';
            @endphp
            {!! Form::select('matched_function_id', $functions, old('matched_function_id', $funcName), [
                'class' => 'form-control function_id',
                'required' => true,
                'placeholder' => __('Select Module'),
            ]) !!}
            @error('matched_function_id')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>


    <div class="col-lg-6 column">
        <div class="form-group @error('matched_destination_id') has-error @enderror">
            <label for="" class="control-label">&nbsp;</label>
            

            {!! Form::select(
                'matched_destination_id',
                $matched_destinations,
                old('matched_destination_id', optional($timeCondition)->matched_destination_id),
                [
                    'class' => 'form-control destination_id',
                    'required' => true,
                    'placeholder' => __('Select destination'),
                ],
            ) !!}
            @error('matched_destination_id')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>




    <div class="col-lg-6 column">
        <div class="form-group @error('function_id') has-error @enderror">
            {!! Form::label('function_id', __('Destination if not match'), ['class' => 'control-label']) !!}
            <span class="text-required">*</span>
            @php
                $func = isset($timeCondition->func->func) ? $timeCondition->func->func : '';
            @endphp
            {!! Form::select('function_id', $functions, old('function_id', $func), [
                'class' => 'form-control function_id',
                'required' => true,
                'placeholder' => __('Select Module'),
            ]) !!}
            @error('function_id')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>


    <div class="col-lg-6 column">
        <div class="form-group @error('destination_id') has-error @enderror">
            <label for="" class="control-label">&nbsp;</label>

            {!! Form::select(
                'destination_id',
                $destinations,
                old('destination_id', optional($timeCondition)->destination_id),
                [
                    'class' => 'form-control destination_id',
                    'required' => true,
                    'placeholder' => __('Select destination'),
                ],
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



@push('script')
    <script src="{{ asset('js/func_destination.js') }}"></script>
@endpush