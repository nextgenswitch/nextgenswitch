@if (app('request')->ajax())
    <form method="{{ $method }}" action="{{ $action }}" accept-charset="UTF-8" id="create_form"
        name="create_form" class="form-horizontal">
        @csrf
@endif

<div class="row">

    <div class="col-lg-12">
        <div class="form-group @error('code') has-error @enderror">
            {!! Form::label('code', __('Code*'), ['class' => 'control-label']) !!}

            {!! Form::number('code', old('code', optional($callQueue)->code), [
                'class' => 'form-control' . ($errors->has('code') ? ' is-invalid' : null),
                'min' => '1',
                'max' => '2147483647',
                'placeholder' => __('Enter code here...'),
            ]) !!}
            @error('code')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>

    <div class="col-lg-12">
        <div class="form-group @error('name') has-error @enderror">
            {!! Form::label('name', __('name*'), ['class' => 'control-label']) !!}
            <span class="text-required">*</span>

            {!! Form::text('name', old('name', optional($callQueue)->name), [
                'class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : null),
                'minlength' => '1',
                'maxlength' => '191',
                'required' => true,
                'placeholder' => 'Enter name here..',
            ]) !!}
            @error('name')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>


    <div class="col-lg-12">
        <div class="form-group @error('description') has-error @enderror">
            {!! Form::label('description', __('Description*'), ['class' => 'control-label']) !!}
            <span class="text-required">*</span>

            {!! Form::text('description', old('description', optional($callQueue)->description), [
                'class' => 'form-control' . ($errors->has('description') ? ' is-invalid' : null),
                'minlength' => '1',
                'maxlength' => '191',
                'required' => true,
                'placeholder' => 'Enter description here..',
            ]) !!}
            @error('description')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>


    <div class="col-lg-12">
        <div class="form-group @error('strategy') has-error @enderror">
            {!! Form::label('strategy', __('Strategy'), ['class' => 'control-label']) !!}
            <span class="text-required">*</span>

            {!! Form::select('strategy', config('enums.ring_strategy'), old('strategy', optional($callQueue)->strategy), [
                'class' => 'form-control' . ($errors->has('strategy') ? ' is-invalid' : null),
                'minlength' => '1',
                'required' => true
            ]) !!}

            @error('strategy')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>

    <div class="col-lg-12">
        <div class="form-group @error('cid_name_prefix') has-error @enderror">
            {!! Form::label('cid_name_prefix', __('Cid Name Prefix'), ['class' => 'control-label']) !!}

            {!! Form::text('cid_name_prefix', old('cid_name_prefix', optional($callQueue)->cid_name_prefix), [
                'class' => 'form-control' . ($errors->has('cid_name_prefix') ? ' is-invalid' : null),
                'minlength' => '1',
                'maxlength' => '191',
                'required' => false,
                'placeholder' => __('Enter cid name prefix here...'),
            ]) !!}
            @error('cid_name_prefix')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>


    <div class="col-lg-12">
        <div class="form-group @error('agent_announcemnet') has-error @enderror">
            {!! Form::label('agent_announcemnet', __('Agent Announcemnet'), ['class' => 'control-label']) !!}

            {!! Form::select(
                'agent_announcemnet',
                $voice_files,
                old('agent_announcemnet', optional($callQueue)->agent_announcemnet),
                [
                    'class' => 'form-control' . ($errors->has('agent_announcemnet') ? ' is-invalid' : null),
                    'minlength' => '1',
                    'required' => false,
                    'placeholder' => __('Select voice file'),
                ],
            ) !!}
            @error('agent_announcemnet')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>


    <div class="col-lg-12">
        <div class="form-group @error('join_announcement') has-error @enderror">
            {!! Form::label('join_announcement', __('Join Announcement'), ['class' => 'control-label']) !!}

            {!! Form::select(
                'join_announcement',
                $voice_files,
                old('join_announcement', optional($callQueue)->join_announcement),
                [
                    'class' => 'form-control' . ($errors->has('join_announcement') ? ' is-invalid' : null),
                    'minlength' => '1',
                    'required' => false,
                    'placeholder' => __('Select voice file'),
                ],
            ) !!}
            @error('join_announcement')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>

    <div class="col-lg-12">
        <div class="form-group @error('join_empty') has-error @enderror">
            {!! Form::label('join_empty', __('Join Empty'), ['class' => 'control-label']) !!}
            <div class="checkbox">
                <label for='join_empty'>
                    {!! Form::checkbox('join_empty', '1', old('join_empty', optional($callQueue)->join_empty) == '0' ? null : true, [
                        'id' => 'join_empty',
                        'class' => '' . ($errors->has('join_empty') ? ' is-invalid' : null),
                    ]) !!}
                    {{ __('Yes') }}
                </label>
            </div>

            @error('join_empty')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>




    <div class="col-lg-12">
        <div class="form-group @error('leave_when_empty') has-error @enderror">
            {!! Form::label('leave_when_empty', __('Leave When Empty'), ['class' => 'control-label']) !!}

            <div class="checkbox">
                <label for='leave_when_empty'>
                    {!! Form::checkbox(
                        'leave_when_empty',
                        '1',
                        old('leave_when_empty', optional($callQueue)->leave_when_empty) == '1' ? true : null,
                        ['id' => 'leave_when_empty', 'class' => '' . ($errors->has('leave_when_empty') ? ' is-invalid' : null)],
                    ) !!}
                    {{ __('Yes') }}
                </label>
            </div>

            @error('leave_when_empty')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>

    <div class="col-lg-12">
        <div class="form-group @error('member_timeout') has-error @enderror">
            {!! Form::label('member_timeout', __('Member Timeout'), ['class' => 'control-label']) !!}

            {!! Form::number('member_timeout', old('member_timeout', optional($callQueue)->member_timeout ? optional($callQueue)->member_timeout : 15), [
                'class' => 'form-control' . ($errors->has('member_timeout') ? ' is-invalid' : null),
                'min' => '0',
                'max' => '2147483647',
                'required' => false,
                'placeholder' => __('Enter member timeout here...'),
            ]) !!}
            @error('member_timeout')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>

    <div class="col-lg-12">
        <div class="form-group @error('music_on_hold') has-error @enderror">
            {!! Form::label('music_on_hold', __('Music On Hold'), ['class' => 'control-label']) !!}

            {!! Form::select('music_on_hold', $voice_files, old('music_on_hold', optional($callQueue)->music_on_hold), [
                'class' => 'form-control' . ($errors->has('music_on_hold') ? ' is-invalid' : null),
                'minlength' => '1',
                'required' => false,
                'placeholder' => __('Select voice file'),
            ]) !!}
            @error('music_on_hold')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>

{{--  
    <div class="col-lg-12">
        <div class="form-group @error('queue_callback') has-error @enderror">
            {!! Form::label('queue_callback', __('Queue Callback'), ['class' => 'control-label']) !!}

            <div class="checkbox">
                <label for='queue_callback'>
                    {!! Form::checkbox(
                        'queue_callback',
                        '1',
                        old('queue_callback', optional($callQueue)->queue_callback) == '1' ? true : null,
                        ['id' => 'queue_callback', 'class' => '' . ($errors->has('queue_callback') ? ' is-invalid' : null)],
                    ) !!}
                    {{ __('Active') }}
                </label>
            </div>

            @error('queue_callback')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>

--}}


    <div class="col-lg-12">
        <div class="form-group @error('queue_timeout') has-error @enderror">
            {!! Form::label('queue_timeout', __('Queue Timeout'), ['class' => 'control-label']) !!}

            {!! Form::number('queue_timeout', old('queue_timeout', optional($callQueue)->queue_timeout ? optional($callQueue)->queue_timeout : 30), [
                'class' => 'form-control' . ($errors->has('queue_timeout') ? ' is-invalid' : null),
                'min' => '0',
                'max' => '2147483647',
                'required' => false,
                'placeholder' => __('Enter queue timeout here...'),
            ]) !!}
            @error('queue_timeout')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>

    <div class="col-lg-12">
        <div class="form-group @error('record') has-error @enderror">
            {!! Form::label('record', __('Record'), ['class' => 'control-label']) !!}

            <div class="checkbox">
                <label for='record'>
                    {!! Form::checkbox('record', '1', old('record', optional($callQueue)->record) == '1' ? true : null, [
                        'id' => 'record',
                        'class' => '' . ($errors->has('record') ? ' is-invalid' : null),
                    ]) !!}
                    {{ __('Yes') }}
                </label>
            </div>
            @error('record')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>

    <div class="col-lg-12">
        <div class="form-group @error('retry') has-error @enderror">
            {!! Form::label('retry', __('Retry'), ['class' => 'control-label']) !!}

            {!! Form::number('retry', old('retry', optional($callQueue)->retry ? optional($callQueue)->retry : 5), [
                'class' => 'form-control' . ($errors->has('retry') ? ' is-invalid' : null),
                'min' => '0',
                'max' => '2147483647',
                'required' => false,
                'placeholder' => __('Enter retry here...'),
            ]) !!}
            @error('retry')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>

    <div class="col-lg-12">
        <div class="form-group @error('ring_busy_agent') has-error @enderror">
            {!! Form::label('ring_busy_agent', __('Ring Busy Agent'), ['class' => 'control-label']) !!}

            <div class="checkbox">
                <label for='ring_busy_agent'>
                    {!! Form::checkbox(
                        'ring_busy_agent',
                        '1',
                        old('ring_busy_agent', optional($callQueue)->ring_busy_agent) == '1' ? true : null,
                        ['id' => 'ring_busy_agent', 'class' => '' . ($errors->has('ring_busy_agent') ? ' is-invalid' : null)],
                    ) !!}
                    {{ __('Yes') }}
                </label>
            </div>

            @error('ring_busy_agent')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>

    <div class="col-lg-12">
        <div class="form-group @error('service_level') has-error @enderror">
            {!! Form::label('service_level', __('Service Level'), ['class' => 'control-label']) !!}

            {!! Form::text('service_level', old('service_level', optional($callQueue)->service_level), [
                'class' => 'form-control' . ($errors->has('service_level') ? ' is-invalid' : null),
                'minlength' => '1',
                'maxlength' => '191',
                'required' => false,
                'placeholder' => __('Enter service level here...'),
            ]) !!}
            @error('service_level')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>



    <div class="col-lg-12">
        <div class="form-group @error('timeout_priority') has-error @enderror">
            {!! Form::label('timeout_priority', __('Timeout Priority'), ['class' => 'control-label']) !!}

            {!! Form::text('timeout_priority', old('timeout_priority', optional($callQueue)->timeout_priority), [
                'class' => 'form-control' . ($errors->has('timeout_priority') ? ' is-invalid' : null),
                'minlength' => '1',
                'maxlength' => '191',
                'required' => false,
                'placeholder' => __('Enter timeout priority here...'),
            ]) !!}
            @error('timeout_priority')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>

    <div class="col-lg-12">
        <div class="form-group @error('wrap_up_time') has-error @enderror">
            {!! Form::label('wrap_up_time', __('Wrap Up Time'), ['class' => 'control-label']) !!}

            {!! Form::number('wrap_up_time', old('wrap_up_time', optional($callQueue)->wrap_up_time ? optional($callQueue)->wrap_up_time : 0), [
                'class' => 'form-control' . ($errors->has('wrap_up_time') ? ' is-invalid' : null),
                'min' => '0',
                'max' => '2147483647',
                'required' => false,
                'placeholder' => __('Enter wrap up time here...'),
            ]) !!}
            @error('wrap_up_time')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>

    <div class="col-lg-12">
        <div class="form-group @error('function_id') has-error @enderror">
            {!! Form::label('function_id', __('Last Destination*'), ['class' => 'control-label']) !!}
            <span class="text-required">*</span>
            @php
                $func = isset($callQueue->func->func) ? $callQueue->func->func : '';
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


    <div class="col-lg-12">
        <div class="form-group @error('destination_id') has-error @enderror">
            {!! Form::label('destination_id', __('Destination*'), ['class' => 'control-label']) !!}
            <span class="text-required">*</span>

            {!! Form::select('destination_id', $destinations, old('destination_id', optional($callQueue)->destination_id), [
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
