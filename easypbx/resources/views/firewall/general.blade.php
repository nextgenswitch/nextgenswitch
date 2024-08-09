@php
    $default = [
        'enable_firewall' => 1,
        'failed_attempts_allow' => 3,
        'ban_time' => 3,
        'find_time' => 300,
        'notification_email' => '',
    ];
    $settings = array_merge($default, $settings);
    //dd($settings);
@endphp

<div class="row">
    <div class="col-md-6">
        <div class="row">
            <div class="col-6 text-right">
                <label for="enable_firewall"> Enable Firewall </label>
            </div>
            <div class="col-6">
                <div class="toggle">
                    <label for="enable_firewall">
                        <input type="checkbox" name="settings[enable_firewall]" value="1"
                            @if ($settings['enable_firewall'] == 1) checked="checked" @endif id="enable_firewall">
                        <span class="button-indecator"></span>
                    </label>

                </div>

            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="row">
            <div class="col-4">
                <label for="failed_attempts_allow"> Failed attempts allow </label>
                <span class="text-required">*</span>
            </div>

            <div class="col-8">
                <div class="form-group @error('failed_attempts_allow') has-error @enderror">
                    {!! Form::number(
                        'settings[failed_attempts_allow]',
                        old('settings.failed_attempts_allow', $settings['failed_attempts_allow']),
                        [
                            'class' => 'form-control' . ($errors->has('failed_attempts_allow') ? ' is-invalid' : null),
                            'required' => true,
                            'placeholder' => __('Enter number of time failed attempt allow here...'),
                        ],
                    ) !!}
                    @error('failed_attempts_allow')
                        <p class="help-block  text-danger"> {{ $message }} </p>
                    @enderror
                </div>
            </div>

        </div>

    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="row">
            <div class="col-6 text-right">
                <label for="ban_time"> Ban Time </label>
                <span class="text-required">*</span>
            </div>

            <div class="col-6">
                <div class="form-group @error('ban_time') has-error @enderror">
                    {!! Form::number('settings[ban_time]', old('settings.ban_time', $settings['ban_time']), [
                        'class' => 'form-control' . ($errors->has('ban_time') ? ' is-invalid' : null),
                        'required' => true,
                        'placeholder' => __('Enter ban time here...'),
                    ]) !!}
                    @error('ban_time')
                        <p class="help-block  text-danger"> {{ $message }} </p>
                    @enderror
                </div>
            </div>

        </div>

    </div>

    <div class="col-md-6">
        <div class="row">
            <div class="col-4">
                <label for="find_time"> Find time (seconds) </label>
                <span class="text-required">*</span>
            </div>

            <div class="col-8">
                <div class="form-group @error('find_time') has-error @enderror">
                    {!! Form::number(
                        'settings[find_time]',
                        old('settings.find_time', $settings['find_time']),
                        [
                            'class' => 'form-control' . ($errors->has('find_time') ? ' is-invalid' : null),
                            'required' => true,
                            'placeholder' => __('Enter find time here...'),
                        ],
                    ) !!}
                    @error('find_time')
                        <p class="help-block  text-danger"> {{ $message }} </p>
                    @enderror
                </div>
            </div>

        </div>

    </div>
</div>

<div class="row">
    <div class="col-3 text-right">
        <label for="notification_email">Notification email</label>
    </div>
    <div class="col-9">
        <div class="form-group @error('notification_email') has-error @enderror">
            {!! Form::text(
                'settings[notification_email]',
                old('settings.notification_email', $settings['notification_email']),
                [
                    'class' => 'form-control' . ($errors->has('notification_email') ? ' is-invalid' : null),
                    'required' => false,
                    'placeholder' => __('Enter notification email here...'),
                ],
            ) !!}
            @error('notification_email')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>
</div>
