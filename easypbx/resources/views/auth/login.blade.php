@extends('auth.layout')
@php
$title = env('APP_NAME', 'EasyPBX');
if(config('licence.brand_name')) $title = config('licence.brand_name')
@endphp

@section('title', __('Login - ' . $title))


@section('content')

<section class="login-content">
  
  @include('partials.message')

      <div class="login-box mt-2">
        <form class="login-form" action="{{ route('login') }}" method="POST">
            @csrf
            <div class="login-head">
                <i data-feather="user"></i>
            </div>

          <div class="form-group">
            <label class="control-label">{{ __('Username') }}</label>
            <input class="form-control @error('email') is-invalid @enderror" type="text" name="email" id="email" value="{{ old('email') }}" placeholder="{{ __('Email') }}" autofocus>
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
          </div>
          <div class="form-group">
            <label class="control-label">{{ __('Password') }}</label>
            <input class="form-control @error('password') is-invalid @enderror" type="password" id="password" name="password" value="{{ old('password') }}" placeholder="{{ __('Password') }}">
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
          </div>
          <div class="form-group">
            <div class="utility">
              <div class="animated-checkbox">
                <label>
                  <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                  <span class="label-text">{{ __('Stay Signed in') }}</span>
                </label>
              </div>
              <p class="semibold-text mb-2"><a href="#" data-toggle="flip">{{ __('Forgot Password ?') }}</a></p>
            </div>
          </div>
          <div class="form-group btn-container">
            <button class="btn btn-primary btn-block"><i class="fa fa-sign-in fa-lg fa-fw"></i>{{ __('SIGN IN') }} </button>
          </div>
          @if(env('TRIAL_ENABLE'))
            <!-- <p class="semibold-text mt-2"><a href="{{ route('register') }}"> {{ __('Register') }}</a></p> -->
          @endif
        </form>

        <form class="forget-form" action="{{ route('password.email') }}" method="post">
          @csrf
          <h3 class="login-head">
            <i data-feather="lock"></i>
          </h3>
          <div class="form-group">
            <label class="control-label">{{ __('Email') }}</label>
            <input class="form-control  @error('email') is-invalid @enderror" name="email" type="email" value="{{ old('email') }}" placeholder="{{ __('you@example.com') }}" autofocus>
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
          </div>
          <div class="form-group btn-container">
            <button class="btn btn-primary btn-block"><i class="fa fa-unlock fa-lg fa-fw"></i>{{ __('Reset') }}</button>
          </div>
          <div class="form-group mt-3">
            <p class="semibold-text mb-0"><a href="#" data-toggle="flip"><i class="fa fa-angle-left fa-fw"></i> {{ __('Back to Login') }}</a></p>
          </div>


        </form>
      </div>


    </section>




@endsection

