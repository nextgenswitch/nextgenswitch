@extends('layouts.app')

@section('title', __('Change password') )

@section('content')

<div class="row">
  <div class="col-12">
    @include('partials.message')
    <div class="card">
        <div class="card-header">
            Change password
        </div>

        <div class="card-body">
            <form action="{{ route('user.change.password') }}" method="post">

                @csrf

                <div class="form-group mb-2">
                    <label for="current-password">{{ __('Current Password') }}</label>
                    <input class="form-control @error('current_password') is-invalid @enderror" id="current-password" name="current_password" value="{{ old('current_password') }}" type="text" placeholder="{{ __('Current Password') }}">
                    @error('current_password')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group mb-2">
                    <label for="new-password">{{ __('New Password') }}</label>
                    <input class="form-control @error('password') is-invalid @enderror" type="password" id="new-password" name="password" value="{{ old('password') }}" placeholder="{{ __('New Password') }}">
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                    @enderror
    
                  </div>

                  <div class="form-group mb-2">
                    <label for="confirm-password">{{ __('Confirm Password') }}</label>
                    <input class="form-control @error('password_confirmation') is-invalid @enderror" type="password" id="confirm-password" name="password_confirmation" value="{{ old('password_confirmation') }}" placeholder="{{ __('Confirm Password') }}">
                    @error('password_confirmation')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                  </div>
                  <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i> {{ __('Save Changes') }}</button>
    
              </form>
        </div>
    </div>
  </div>
</div>



@endsection


