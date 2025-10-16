<form method="POST" action="{{ route('licence') }}" class="active-lc-form @if (config('licence.call_limit') > 0) d-none @endif">
    <div class="modal-body">

    @php
        $title = env('APP_NAME', 'EasyPBX');
        if(config('licence.brand_name')) $title = config('licence.brand_name')
    @endphp
    
        <h3 class="alert alert-primary"> {{ __("Please activate your installation to be able to use NextGenSwitch . ") }}</h3>
        @csrf
        <div class="row">
            <div class="col-lg-12">
                <div class="form-group">
                   
                    <input class="form-control @error('name') is-invalid @enderror" type="text" name="name"
                        id="name" value="{{ old('name') }}" placeholder="{{ __('Your Full Name') }}" autofocus>
                    
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>
                    
                </div>
            </div>
            <div class="col-lg-12">

                <div class="form-group">
                    
                    <input class="form-control @error('email') is-invalid @enderror" type="text" name="email"
                        id="email" value="{{ old('email') }}" placeholder="{{ __('Your Email Address') }}" autofocus>
                    
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>
                    
                </div>
            </div>

           


            <div class="col-lg-12">
                
                <div class="from-group @error('country') has-error @enderror">
                  
                    {!! Form::select('country', config('enums.countries'), old('country'), [
                        'class' => 'form-control selectpicker',
                        'data-live-search' => 'true',
                        'required' => true,
                        'placeholder' => __('Your Country'),
                    ]) !!}

                    
                    <p class="help-block invalid-feedback"> <strong></strong> </p>
                    
                </div>
            </div>

        </div>

    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-primary create-account licence-toggle-form" >Allready have a Key</button>
        <button type="submit" class="btn btn-primary licence-form-submit-btn">Activate</button>
    </div>

</form>
