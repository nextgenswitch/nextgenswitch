@extends('auth.layout')

@push('css')
    <style>
        .setup-content {
            max-width: 790px;
        }
    </style>
@endpush

@php
    $title = env('APP_NAME', 'EasyPBX');
    if (config('licence.brand_name')) {
        $title = config('licence.brand_name');
    }
@endphp

@section('title', 'Setup - ' . $title)

@section('content')

    <section class="login-content">
        <div class="login-box register-content setup-content">
            <form class="login-form setup-form" action="{{ route('setup') }}" method="post">
                @csrf


                <div class="mb-4 text-center">
                    <h3>{{ __('NextGenSwitch Setup') }}</h3>
                    <p class="text-muted">
                        {{ __('Welcome! Please complete this setup form to configure your PBX and create the first Super User account. The Super User will have full administrative access to manage your PBX system.') }}
                    </p>
                </div>

                <div class="form-group">
                    <label class="control-label">{{ __('Company Name') }}</label>
                    <input class="form-control @error('org_name') is-invalid @enderror" name="org_name" type="text"
                        value="{{ old('org_name') }}" placeholder="{{ __('Enter company name (e.g., My Company)') }}">
                    @error('org_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="control-label">{{ __('Domain') }}
                        <small>{{ __('(This domain will be used to access your PBX)') }}</small></label>

                    <input class="form-control @error('domain') is-invalid @enderror" name="domain" type="text"
                        value="{{ old('domain') }}" placeholder="{{ __('pbx.mycompany.com') }}">
                    @error('domain')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="control-label">{{ __('Contact Number') }}</label>
                    <input class="form-control @error('contact') is-invalid @enderror" name="contact" type="text"
                        value="{{ old('contact') }}" placeholder="{{ __('Enter your contact number') }}">
                    @error('contact')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="control-label">{{ __('Email Address') }}
                        <small>{{ __('(The email will be used for Super User login)') }}</small> </label>
                    <input class="form-control @error('email') is-invalid @enderror" name="email" type="email"
                        value="{{ old('email') }}" placeholder="{{ __('you@example.com') }}">
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="control-label">{{ __('Password') }}</label>
                    <input name="password" class="form-control @error('password') is-invalid @enderror" type="password"
                        placeholder="{{ __('Create a strong password for Super User') }}">
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="control-label">{{ __('Company Address') }} <small>{{ __('(Optional)') }}</small></label>
                    <textarea class="form-control @error('address') is-invalid @enderror" name="address"
                        placeholder="{{ __('Enter the full address of your company') }}">{{ old('address') }}</textarea>
                    @error('address')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group btn-container">
                    <button class="btn btn-primary btn-block"><i
                            class="fa fa-cogs fa-lg fa-fw"></i>{{ __('Setup') }}</button>
                </div>
            </form>
        </div>
    </section>

@endsection
