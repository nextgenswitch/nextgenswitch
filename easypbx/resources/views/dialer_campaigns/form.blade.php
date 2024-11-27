@push('css')
    
    <link rel="stylesheet" type="text/css" href="{{ asset('js/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/mdtimepicker.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/mdtimepicker-theme.css') }}">
@endpush

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

            {!! Form::text('name', old('name', optional($dialerCampaign)->name), [
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
        <div class="form-group @error('description') has-error @enderror">
            {!! Form::label('description', __('Description'), ['class' => 'control-label']) !!}

            {!! Form::text('description', old('description', optional($dialerCampaign)->description), [
                'class' => 'form-control' . ($errors->has('description') ? ' is-invalid' : null),
                'placeholder' => __('Enter description here (optional) ...'),
                'maxlength' => '191',
            ]) !!}
            @error('description')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>

    <!-- <div class="col-lg-12">
        <div class="form-group @error('agents') has-error @enderror">
            {!! Form::label('agents', __('Agents'), ['class' => 'control-label']) !!}
            <span class="text-required">*</span>

            {!! Form::select('agents', $agents, old('agents', optional($dialerCampaign)->agents), [
                'multiple' => 'multiple',
                'name' => 'agents[]',
                'class' => 'form-control selectpicker' . ($errors->has('agents') ? ' is-invalid' : null),
                'maxlength' => '100',
                'data-live-search' => 'true',
            ]) !!}
            @error('agents')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div> -->

    <div class="col-lg-12">
        <div class="form-group @error('contact_groups') has-error @enderror">
            {!! Form::label('contact_groups', __('Contact Group'), ['class' => 'control-label']) !!} 
            <span class="text-required">*</span>

            {!! Form::select('contact_groups', $contact_groups, old('contact_groups', optional($dialerCampaign)->contact_groups), [
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

    

    <div class="col-lg-12">
        <div class="form-group @error('schedule_days') has-error @enderror">
            {!! Form::label('schedule_days', __('Days'), ['class' => 'control-label']) !!}
            <span class="text-required">*</span>

            {!! Form::select(
                'schedule_days',
                config('enums.weekdays'),
                old('schedule_days', optional($dialerCampaign)->schedule_days),
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

    <div class="col-lg-12">
        <div class="form-group @error('timezone') has-error @enderror">
            {!! Form::label('timezone', __('Timezone'), ['class' => 'control-label']) !!}
            <span class="text-required">*</span>

            {!! Form::select('timezone', config('enums.timezones'), old('timezone', optional($dialerCampaign)->timezone), [
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

            {!! Form::text('start_at', old('start_at', optional($dialerCampaign)->start_at), [
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

            {!! Form::text('end_at', old('end_at', optional($dialerCampaign)->end_at), [
                'class' => 'form-control timepick' . ($errors->has('end_at') ? ' is-invalid' : null),
                'required' => true,
                'placeholder' => __('Enter end at here...'),
            ]) !!}
            @error('end_at')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>

    
    <div class="col-lg-6">
        <div class="form-group @error('end_date') has-error @enderror">
            {!! Form::label('end_date', __('End Date'), ['class' => 'control-label']) !!}
            

            {!! Form::text('end_date', old('end_date', optional($dialerCampaign)->end_date), [
                'class' => 'form-control' . ($errors->has('end_date') ? ' is-invalid' : null),
                'required' => true,
                'placeholder' => __('Enter end date here...'),
            ]) !!}
            @error('end_date')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group @error('call_interval') has-error @enderror">
            {!! Form::label('call_interval', __('Call Interval (sec)'), ['class' => 'control-label']) !!}
            <span class="text-required">*</span>

            {!! Form::number('call_interval', old('call_interval', optional($dialerCampaign)->call_interval ?  optional($dialerCampaign)->call_interval: 10), [
                'class' => 'form-control' . ($errors->has('call_interval') ? ' is-invalid' : null),
                'min' => '0',
                'max' => '100',
                'required' => true,
                'placeholder' => __('Enter call interval here...'),
            ]) !!}
            @error('call_interval')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group @error('script_id') has-error @enderror">
            {!! Form::label('script_id', __('Script'), ['class' => 'control-label']) !!}
            <span class="text-required">*</span>

            {!! Form::select('script_id', $scripts, old('script_id', optional($dialerCampaign)->script_id), [
                'class' => 'form-control' . ($errors->has('script_id') ? ' is-invalid' : null),
                'minlength' => '1',
                'maxlength' => '100',
                'required' => true,
                'data-live-search' => 'true',
                'placeholder' => __('Please select script'),
            ]) !!}
            @error('script_id')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>

    <div class="col-lg-6">
        <div class="form-group @error('form_id') has-error @enderror">
            {!! Form::label('form_id', __('Form'), ['class' => 'control-label']) !!}
            <span class="text-required">*</span>

            {!! Form::select('form_id', $forms, old('form_id', optional($dialerCampaign)->form_id), [
                'class' => 'form-control' . ($errors->has('form_id') ? ' is-invalid' : null),
                'minlength' => '1',
                'maxlength' => '100',
                'required' => true,
                'data-live-search' => 'true',
                'placeholder' => __('Please select custom form'),
            ]) !!}
            @error('form_id')
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


@push('script')
    <script src="{{ asset('js/plugins/mdtimepicker.min.js') }}"></script>
    <script src="{{ asset('js/func_destination.js') }}"></script>
    <script src="{{ asset('js/flatpickr/flatpickr.js') }}"></script>
    


    <script type="text/javascript">
        $(function() {
            
            $("#end_date").flatpickr({
                dateFormat: "Y-m-d",
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
                console.log(Intl.DateTimeFormat().resolvedOptions().timeZone);
                $('#timezone').selectpicker('val', (Intl.DateTimeFormat().resolvedOptions().timeZone));
            }
        });
    </script>
@endpush
