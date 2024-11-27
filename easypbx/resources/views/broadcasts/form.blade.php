@if (app('request')->ajax())
    <form method="{{ $method }}" action="{{ $action }}" accept-charset="UTF-8" id="create_form"
        name="create_form" class="form-horizontal">
        @csrf
@endif

<div class="row">


    <div class="col-lg-6">
        <div class="form-group @error('name') has-error @enderror">
            {!! Form::label('name', __('Broadcast Name'), ['class' => 'control-label']) !!}
            <span class="text-required">*</span>

            {!! Form::text('name', old('name', optional($campaign)->name), [
                'class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : null),
                'minlength' => '1',
                'maxlength' => '255',
                'required' => true,
                'placeholder' => __('Enter Broadcast name here...'),
            ]) !!}
            @error('name')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>

    <div class="col-lg-6">
        <div class="form-group @error('from') has-error @enderror">
            {!! Form::label('from', __('From Number'), ['class' => 'control-label']) !!}
            <span class="text-required">*</span>

            {!! Form::text('from', old('from', optional($campaign)->from), [
                'class' => 'form-control' . ($errors->has('from') ? ' is-invalid' : null),
                'minlength' => '1',
                'maxlength' => '255',
                'required' => true,
                'placeholder' => __('Enter  from here...'),
            ]) !!}
            @error('from')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>



    <div class="col-lg-12">
        <div class="form-group @error('contact_groups') has-error @enderror">
            {!! Form::label('contact_groups', __('Select Contact Groups'), ['class' => 'control-label']) !!}
            <span class="text-required">*</span>

            {!! Form::select('contact_groups', $contact_groups, old('contact_groups', optional($campaign)->contact_groups), [
                'multiple' => 'multiple',
                'name' => 'contact_groups[]',
                'class' => 'form-control selectpicker' . ($errors->has('contact_groups') ? ' is-invalid' : null),
                'maxlength' => '100',
                'data-live-search' => 'true',
            ]) !!}
            @error('contact_groups')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>



    <div class="col-lg-6">
        <div class="form-group @error('max_retry') has-error @enderror">
            {!! Form::label('max_retry', __('Max Try'), ['class' => 'control-label']) !!}
            <span class="text-required">*</span>

            {!! Form::number('max_retry', old('max_retry', optional($campaign)->max_retry), [
                'class' => 'form-control' . ($errors->has('max_retry') ? ' is-invalid' : null),
                'min' => '0',
                'max' => '10',
                'required' => true,
                'placeholder' => __('Enter max retry here...'),
            ]) !!}
            @error('max_retry')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>

    <div class="col-lg-6">
        <div class="form-group @error('call_limit') has-error @enderror">
            {!! Form::label('call_limit', __('Call Limit'), ['class' => 'control-label']) !!}
            <span class="text-required">*</span>

            {!! Form::number('call_limit', old('call_limit', optional($campaign)->call_limit), [
                'class' => 'form-control' . ($errors->has('call_limit') ? ' is-invalid' : null),
                'min' => '0',
                'max' => '1000',
                'required' => true,
                'placeholder' => __('Enter Call limit here...'),
            ]) !!}
            @error('call_limit')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>

    <div class="col-lg-12">
        <div class="form-group @error('timezone') has-error @enderror">
            {!! Form::label('timezone', __('Timezone'), ['class' => 'control-label']) !!}
            <span class="text-required">*</span>

            {!! Form::select('timezone', config('enums.timezones'), old('timezone', optional($campaign)->timezone), [
                'class' => 'form-control selectpicker' . ($errors->has('timezone') ? ' is-invalid' : null),
                'minlength' => '1',
                'maxlength' => '100',
                'required' => true,
                'data-live-search' => 'true',
                'placeholder' => __('Enter timezone here...'),
            ]) !!}
            @error('timezone')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>

    <div class="col-lg-6">
        <div class="form-group @error('start_at') has-error @enderror">
            {!! Form::label('start_at', __('Start at'), ['class' => 'control-label']) !!}
            <span class="text-required">*</span>

            {!! Form::text('start_at', old('start_at', optional($campaign)->start_at), [
                'class' => 'form-control timepick' . ($errors->has('start_at') ? ' is-invalid' : null),
                'required' => true,
                'placeholder' => __('Enter start at here...'),
            ]) !!}
            @error('start_at')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>

    <div class="col-lg-6">
        <div class="form-group @error('end_at') has-error @enderror">
            {!! Form::label('end_at', __('End at'), ['class' => 'control-label']) !!}
            <span class="text-required">*</span>

            {!! Form::text('end_at', old('end_at', optional($campaign)->end_at), [
                'class' => 'form-control timepick' . ($errors->has('end_at') ? ' is-invalid' : null),
                'required' => true,
                'placeholder' => __('Enter end at here...'),
            ]) !!}
            @error('end_at')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>

    <div class="col-lg-12">
        <div class="form-group @error('schedule_days') has-error @enderror">
            {!! Form::label('schedule_days', __('Select Days'), ['class' => 'control-label']) !!}
            <span class="text-required">*</span>

            {!! Form::select(
                'schedule_days',
                config('enums.weekdays'),
                old('schedule_days', optional($campaign)->schedule_days),
                [
                    'class' => 'form-control selectpicker' . ($errors->has('schedule_days') ? ' is-invalid' : null),
                    'minlength' => '1',
                    'maxlength' => '100',
                    'required' => true,
                    'multiple' => 'multiple',
                    'data-actions-box' => 'true',
                    'name' => 'schedule_days[]',
                ],
            ) !!}
            @error('schedule_days')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>

    <div class="col-lg-6 column">
        <div class="form-group @error('function_id') has-error @enderror">
            {!! Form::label('function_id', __('Broadcast Destination'), ['class' => 'control-label']) !!}
            <span class="text-required">*</span>
            @php
                $func = isset($campaign->func->func) ? $campaign->func->func : '';
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

            {!! Form::select('destination_id', $destinations, old('destination_id', optional($campaign)->destination_id), [
                'class' => 'form-control destination_id',
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

    <script type="text/javascript">
        $(document).ready(function() {
            $('.selectpicker').selectpicker();
        });
    </script>
@endif

@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/mdtimepicker.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/mdtimepicker-theme.css') }}">
@endpush


@push('script')
    <script src="{{ asset('js/plugins/mdtimepicker.min.js') }}"></script>
    <script src="{{ asset('js/func_destination.js') }}"></script>

    <script type="text/javascript">
        $(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var options = {
                // theme of the timepicker
                theme: 'dark',

                // determines if clear button is visible
                clearBtn: false,
                // determines if the clock will use 24-hour format in the UI; format config will be forced to `hh:mm` if not specified
                is24hour: true,

            }

            mdtimepicker('#start_at', options)
            mdtimepicker('#end_at', options)




            if ($('#timezone').val() == '') {
                //console.log(Intl.DateTimeFormat().resolvedOptions().timeZone);
                $('#timezone').selectpicker('val', (Intl.DateTimeFormat().resolvedOptions().timeZone));
            }



        });
    </script>
@endpush
