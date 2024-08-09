@extends('auth.layout')


@section('title', 'Login - EasyPBX')


@section('content')

<section class="login-content">
  
  @include('partials.message')

      <div class="login-box mt-2">
        <form class="login-form" action="{{ route('agent.login') }}" method="POST">
            @csrf
            <div class="login-head">
                <i data-feather="user"></i>
            </div>

          <div class="form-group">
            <label class="control-label">{{ __('Username') }}</label>
            <input class="form-control @error('username') is-invalid @enderror" type="text" name="username" id="username" value="{{ old('username') }}" placeholder="{{ __('Email') }}" autofocus>
            @error('username')
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
              
            </div>
          </div>
          <div class="form-group btn-container">
            <button class="btn btn-primary btn-block"><i class="fa fa-sign-in fa-lg fa-fw"></i>{{ __('SIGN IN') }} </button>
          </div>

        </form>
      </div>
    </section>




@endsection

