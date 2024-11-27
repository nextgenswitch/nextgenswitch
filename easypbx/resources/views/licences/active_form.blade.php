<form method="POST" action="{{ route('licence.active') }}" class="d-none @if (config('licence.call_limit') > 0) d-none @endif">
    <div class="modal-body">
    @php
        $title = env('APP_NAME', 'EasyPBX');
       // if(config('licence.brand_name')) $title = config('licence.brand_name')
    @endphp

        <p>{{__("Please activate your license to use {$title} . If you don't have any license info please")}}
            <a class="active-licence licence-toggle-form" href="javascript:void(0)">{{ __('Click here') }}</a>.
        </p>
        @csrf
        <div class="row">
            <div class="col-lg-12">

                <div class="form-group">
                    <label class="control-label">{{ __('Email') }}</label>
                    <input class="form-control @error('email') is-invalid @enderror" type="email" name="email"
                        id="email" value="{{ old('email') }}" placeholder="{{ __('Email') }}" autofocus>
                    
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>
                    
                </div>
            </div>

            <div class="col-lg-12">
                <div class="form-group">
                    <label class="control-label">{{ __('Licence Key') }}</label>
                    <input class="form-control @error('key') is-invalid @enderror" type="text" name="key"
                        id="key" value="{{ old('key') }}" placeholder="{{ __('Key') }}" autofocus>
                    
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>
                    
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit"
            class="btn btn-primary licence-form-submit-btn">Activate</button>
    </div>
</form>
