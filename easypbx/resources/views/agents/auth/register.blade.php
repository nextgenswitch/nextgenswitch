@extends('auth.layout')

@section('title', 'Registration - EasyPBX')

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
            <input class="form-control  @error('name') is-invalid @enderror" name="name" type="text" value="{{ old('name') }}" placeholder="{{ __('John Doe') }}" autofocus>
            @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror

          </div>
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
            <label class="control-label">{{ __('password') }}</label>
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
            <button class="btn btn-primary btn-block"><i class="fa fa-sign-in fa-lg fa-fw"></i>{{ __('SIGN UP') }}</button>
          </div>
          <p class="semibold-text mt-2"><a href="{{ route('login') }}"><i class="fa fa-angle-left fa-fw"></i> {{ __('Back to Login') }}</a></p>
        </form>
      </div>
    </section>


@endsection
