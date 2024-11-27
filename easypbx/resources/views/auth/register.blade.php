@extends('auth.layout')
@php
$title = env('APP_NAME', 'EasyPBX');
if(config('licence.brand_name')) $title = config('licence.brand_name')
@endphp

@section('title', 'Registration - '.$title)

@section('content')

<section class="login-content">
      <div class="login-box register-content">
        <form class="login-form" action="{{ route('register') }}" method="post">
            @csrf
            <div class="login-head">
                <i data-feather="user-plus"></i>
            </div>

          <div class="form-group">
            <label class="control-label">{{ __('Name') }}</label>
            <input class="form-control  @error('name') is-invalid @enderror" name="name" type="text" value="{{ old('name') }}" placeholder="{{ __('Please enter your name') }}" >
            @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror

          </div>

            <div class="form-group">
            <label class="control-label">{{ __('Phone') }}</label>
            <input class="form-control  @error('contact_no') is-invalid @enderror" name="contact_no" type="text" value="{{ old('contact_no') }}" placeholder="{{ __('Please enter your contact no') }}" >
            @error('contact_no')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
          </div>


          <div class="form-group">
            <label class="control-label">{{ __('Email ( will be used as username)') }}</label>
            <input class="form-control  @error('email') is-invalid @enderror" name="email" type="email" value="{{ old('email') }}" placeholder="{{ __('Please enter your email') }}" >
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
          </div>

          <div class="form-group">
            <label class="control-label">{{ __('password') }}</label>
            <input name="password" class="form-control  @error('password') is-invalid @enderror" type="password" value="{{ old('password') }}" placeholder="{{ __('Please enter your password') }}">
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
          </div>
          
          <div class="form-group">
            <label class="control-label">{{ __('Domain') }}</label>
            <div class="input-group mb-3">
              <input class="form-control  @error('domain') is-invalid @enderror" name="domain" type="domain" value="{{ old('domain') }}" placeholder="{{ __('Will be used as subdomain') }}" >
              <div class="input-group-append">
                <span class="input-group-text">.nextgenswitch.com</span>
              </div>
            </div>
            @error('domain')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
          </div>


      


          <div class="form-group btn-container">
            <button class="btn btn-primary btn-block"><i class="fa fa-sign-in fa-lg fa-fw"></i>{{ __('SIGN UP') }}</button>
          </div>
          <p class="semibold-text mt-2"><a href="{{ route('login') }}"><i class="fa fa-angle-left fa-fw"></i> {{ __('Back to Login') }}</a></p>
        </form>
      </div>
    </section>


@endsection
