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

            {!! Form::text('name', old('name', optional($trunk)->name), [
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

    @php
        $sip = isset($trunk->sipuser) ? $trunk->sipuser : null;
    @endphp

    <div class="col-lg-12">
        <div class="form-group @error('peer') has-error @enderror">
            {!! Form::label('peer', __('Peer ?'), ['class' => 'control-label']) !!}
            <span class="text-required">*</span>

            <div class="checkbox">
                <label for='peer'>
                    {!! Form::checkbox('peer', '1', old('peer', isset($sip->peer) && $sip->peer == 1 ? true : null), [
                        'id' => 'peer',
                        'checked' => isset($sip->peer) && $sip->peer == 0 ? false : true,
                        'class' => '' . ($errors->has('peer') ? ' is-invalid' : null),
                    ]) !!}
                    {{ __('Yes') }}
                </label>
            </div>

            @error('status')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>

    <div class="col-lg-12">
        <div class="form-group @error('record') has-error @enderror">
            {!! Form::label('record', __('record ?'), ['class' => 'control-label']) !!}

            <div class="checkbox">
                <label for='record'>
                    {!! Form::checkbox('record', '1', old('record', isset($sip->record) && $sip->record == 1 ? true : null), [
                        'id' => 'record',
                        'class' => '' . ($errors->has('record') ? ' is-invalid' : null),
                    ]) !!}
                    {{ __('Yes') }}
                </label>
            </div>

            @error('status')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>

</div>

<div class="card mb-2">
    <div class="card-header">{{ __('SIP User Info') }}</div>
    <div class="card-body row">


        @if (isset($sip->id))
            <input type="hidden" name="sip_user_id" value="{{ $sip->id }}">
        @endif
        <div class="col-lg-12">
            <div class="form-group @error('username') has-error @enderror">
                {!! Form::label('username', __('Username'), ['class' => 'control-label']) !!}
                <span class="text-required">*</span>

                {!! Form::text('username', old('username', optional($sip)->username), [
                    'class' => 'form-control' . ($errors->has('username') ? ' is-invalid' : null),
                    'minlength' => '1',
                    'maxlength' => '255',
                    'required' => true,
                    'placeholder' => __('Enter username here...'),
                ]) !!}
                @error('username')
                    <p class="help-block  text-danger"> {{ $message }} </p>
                @enderror
            </div>
        </div>

        <div class="col-lg-12">
            <div class="form-group @error('password') has-error @enderror">
                {!! Form::label('password', __('Password'), ['class' => 'control-label']) !!}
                @if(!isset($sip)) <span class="text-required">*</span> @endif


                {!! Form::password('password', [
                    'class' => 'form-control' . ($errors->has('password') ? ' is-invalid' : null),
                    'required' => isset($sip) ? false : true,
                    'placeholder' => isset($sip) ? __('Please input password to update existing password.') : __('Enter password here...'),
                ]) !!}
                @error('password')
                    <p class="help-block  text-danger"> {{ $message }} </p>
                @enderror
            </div>
        </div>





        <div class="col-lg-12 @if (isset($sip) && $sip->peer == 0) d-none @endif" id="peerDiv">
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group @error('host') has-error @enderror">
                        {!! Form::label('host', __('Host'), ['class' => 'control-label']) !!}

                        {!! Form::text('host', old('host', optional($sip)->host), [
                            'class' => 'form-control' . ($errors->has('host') ? ' is-invalid' : null),
                            'minlength' => '1',
                            'maxlength' => '255',
                            'required' => false,
                            'placeholder' => __('Enter host here...'),
                        ]) !!}
                        @error('host')
                            <p class="help-block  text-danger"> {{ $message }} </p>
                        @enderror
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="form-group @error('port') has-error @enderror">
                        {!! Form::label('port', __('Port'), ['class' => 'control-label']) !!}

                        {!! Form::text('port', old('port', optional($sip)->port), [
                            'class' => 'form-control' . ($errors->has('port') ? ' is-invalid' : null),
                            'minlength' => '1',
                            'maxlength' => '255',
                            'required' => false,
                            'placeholder' => __('Enter port here...'),
                        ]) !!}
                        @error('port')
                            <p class="help-block  text-danger"> {{ $message }} </p>
                        @enderror
                    </div>
                </div>



                <div class="col-lg-12">
                    <div class="form-group @error('transport') has-error @enderror">
                        {!! Form::label('transport', __('Transport'), ['class' => 'control-label']) !!}

                        {!! Form::select('transport', config('enums.transport'), old('transport', optional($sip)->transport), [
                            'class' => 'form-control',
                            'required' => false,
                        ]) !!}
                        @error('transport')
                            <p class="help-block  text-danger"> {{ $message }} </p>
                        @enderror
                    </div>
                </div>

            </div>
        </div>


    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="form-group @error('call_limit') has-error @enderror">
            {!! Form::label('call_limit',__('Call Limit'),['class' => 'control-label']) !!}

        {!! Form::number('call_limit',old('call_limit', optional($sip)->call_limit), ['class' => 'form-control' . ($errors->has('call_limit') ? ' is-invalid' : null), 'min' => '0', 'required' => false, 'placeholder' => __('Enter call limit here...'), ]) !!}
                @error('call_limit') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
        </div>
    </div>
</div>


@if (app('request')->ajax())
    <input type="submit" id="btnSubmit" class="d-none">
    </form>
@endif
