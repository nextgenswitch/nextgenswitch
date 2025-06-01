@extends('auth.layout')
@php
$title = env('APP_NAME', 'EasyPBX');
if(config('licence.brand_name')) $title = config('licence.brand_name')
@endphp

@section('title', 'Reset password - ' . $title)


@section('content')

<section class="login-content">
      <div class="login-box register-content">
        <form class="login-form" action="{{ route('password.update') }}" method="post">
            @csrf
            <div class="login-head">
                <i data-feather="user-plus"></i>
            </div>
          
            <input type="hidden" name="token" value="{{ $token }}">
          <div class="form-group">
            <label class="control-label">{{ __('Email') }}</label>
            <input class="form-control  @error('email') is-invalid @enderror" name="email" type="email" value="{{ old('email') }}" placeholder="{{ __('you@example.com') }}" autofocus>
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
          </div>

          <div class="form-group">
            <label class="control-label">{{ __('Password') }}</label>
            <input name="password" class="form-control  @error('password') is-invalid @enderror" type="password" value="{{ old('password') }}" placeholder="{{ __('password') }}">
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
          </div>
          <div class="form-group">
            <label class="control-label">{{ __('Confirm Password') }}</label>
            <input name="password_confirmation" class="form-control" type="password" placeholder="{{ __('confirm password') }}">
          </div>

          <div class="form-group btn-container">
            <button class="btn btn-primary btn-block"><i class="fa fa-sign-in fa-lg fa-fw"></i>{{ __('Reset Password') }}</button>
          </div>
          
        </form>
      </div>
    </section>


@endsection
