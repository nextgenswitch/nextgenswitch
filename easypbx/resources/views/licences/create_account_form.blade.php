<form method="POST" action="{{ route('licence') }}" class="active-lc-form @if (config('licence.call_limit') > 0) d-none @endif">
    <div class="modal-body">

    @php
        $title = env('APP_NAME', 'EasyPBX');
        if(config('licence.brand_name')) $title = config('licence.brand_name')
    @endphp
    
        <p> {{ __('Please activate your license to use {$title} . If you allready have any previous license info please') }}
            <a class="create-account licence-toggle-form" href="javascript:void(0)">{{ __('Click here') }}</a>.
        </p>
        @csrf
        <div class="row">
            <div class="col-lg-12">
                <div class="form-group">
                    <label class="control-label">{{ __('Name') }}</label>
                    <input class="form-control @error('name') is-invalid @enderror" type="text" name="name"
                        id="name" value="{{ old('name') }}" placeholder="{{ __('Name') }}" autofocus>
                    
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>
                    
                </div>
            </div>
            <div class="col-lg-12">

                <div class="form-group">
                    <label class="control-label">{{ __('Email') }}</label>
                    <input class="form-control @error('email') is-invalid @enderror" type="text" name="email"
                        id="email" value="{{ old('email') }}" placeholder="{{ __('Email') }}" autofocus>
                    
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>
                    
                </div>
            </div>

            {{-- <div class="col-lg-12">
                <label class="control-label">{{ __('Phone') }}</label>
                <span class="text-required">*</span>
                <div class="input-group">
                    <div class="input-group-prepend">
                        {!! Form::select('cc',config('enums.tel_codes'),old('call_id'), ['class' => 'form-control selectpicker', 'id' => 'cc', 'required' => true,'data-live-search'=>"true", 'placeholder' => 'country code']) !!}
                  </div>
                    
                    <input class="form-control @error('phone') is-invalid @enderror" type="text" id="phone"
                        name="phone" value="{{ old('phone') }}" placeholder="{{ __('Phone') }}">
                    
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>
                    
                </div>
            </div> --}}


            <div class="col-lg-12">
                
                <div class="from-group @error('country') has-error @enderror">
                    {!! Form::label('country', __('Country'), ['class' => 'control-label']) !!}    
                    <span class="text-required">*</span>
                    
                    {!! Form::select('country', config('enums.countries'), old('country'), [
                        'class' => 'form-control selectpicker',
                        'data-live-search' => 'true',
                        'required' => true,
                        'placeholder' => __('Country'),
                    ]) !!}

                    
                    <p class="help-block invalid-feedback"> <strong></strong> </p>
                    
                </div>
            </div>

        </div>

    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary licence-form-submit-btn">Activate</button>
    </div>

</form>
