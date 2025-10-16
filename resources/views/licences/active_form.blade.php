<form method="POST" action="{{ route('licence.active') }}" class="d-none @if (config('licence.call_limit') > 0) d-none @endif">
    <div class="modal-body">
    @php
        $title = env('APP_NAME', 'EasyPBX');
       // if(config('licence.brand_name')) $title = config('licence.brand_name')
    @endphp

        <h3 class="alert alert-primary">{{__("Please activate your installation to be able to use NextGenSwitch. ") }}
</h3>
<blockquote class="blockquote">
<p class="card-title">{{ __("If you already have an account, please enter your email and licence key below to activate your installation. Otherwise you can create a new account to active your license.") }}</p>
</blockquote>
@csrf
        <div class="row">
            <div class="col-lg-12">

                <div class="form-group">
                 
                    <input class="form-control @error('email') is-invalid @enderror" type="email" name="email"
                        id="email" value="{{ old('email') }}" placeholder="{{ __('Please Enter Email') }}" autofocus>
                    
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>
                    
                </div>
            </div>

            <div class="col-lg-12">
                <div class="form-group">
                 
                    <input class="form-control @error('key') is-invalid @enderror" type="text" name="key"
                        id="key" value="{{ old('key') }}" placeholder="{{ __('Paste your License Key') }}" autofocus>
                    
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>
                    
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
       <button type="button" class="btn btn-primary active-licence licence-toggle-form" >Create a New Account</button>
        
        <button type="submit"
            class="btn btn-primary licence-form-submit-btn">Activate</button>
    </div>
</form>
