<form method="POST" action="{{ route('dialer.login') }}" class="login-form">
    
        <p>{{ __("Please login with your sip username and password.") }} <br />
            {{ __("Please ensure you allready logged in your sip device.") }}
        </p>
        @csrf

        <div class="form-group">
            <input class="form-control @error('email') is-invalid @enderror" type="text" name="username"
                id="username" value="{{ old('username') }}" placeholder="{{ __('username') }}" autofocus>
            <span class="invalid-feedback" role="alert">
                <strong></strong>
            </span>
        </div>

        <div class="form-group">
            <input class="form-control @error('key') is-invalid @enderror" type="text" name="password"
                id="password" value="{{ old('password') }}" placeholder="{{ __('password') }}" autofocus>
            <span class="invalid-feedback" role="alert">
                <strong></strong>
            </span>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Login</button>
</form>



