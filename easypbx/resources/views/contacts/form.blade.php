@if (app('request')->ajax())
    <form method="{{ $method }}" action="{{ $action }}" accept-charset="UTF-8" id="create_form"
        first_name="create_form" class="form-horizontal">
        @csrf
@endif

<div class="row">

    <div class="col-lg-6">
        <div class="form-group @error('first_name') has-error @enderror">
            {!! Form::label('first_name', __('First Name'), ['class' => 'control-label']) !!}

            {!! Form::text('first_name', old('first_name', optional($contact)->first_name), [
                'class' => 'form-control' . ($errors->has('first_name') ? ' is-invalid' : null),
                'minlength' => '1',
                'maxlength' => '100',
                'required' => false,
                'placeholder' => __('First name'),
            ]) !!}
            @error('first_name')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group @error('last_name') has-error @enderror">
            {!! Form::label('last_name', __('Last Name'), ['class' => 'control-label']) !!}

            {!! Form::text('last_name', old('last_name', optional($contact)->last_name), [
                'class' => 'form-control' . ($errors->has('last_name') ? ' is-invalid' : null),
                'minlength' => '1',
                'maxlength' => '100',
                'required' => false,
                'placeholder' => __('Last name'),
            ]) !!}
            @error('last_name')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>


    <div class="col-lg-6">
        <div class="form-group @error('gender') has-error @enderror">
            {!! Form::label('gender', __('Gender'), ['class' => 'control-label']) !!}
            {!! Form::select('gender', config('enums.genders'), old('gender', optional($contact)->gender), [
                'class' => 'form-control',
                'required' => false,
                'placeholder' => __('Select gender'),
            ]) !!}
            @error('gender')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror

        </div>
    </div>

    <div class="col-lg-6">
        <div class="form-group @error('email') has-error @enderror">
            {!! Form::label('email', __('Email address'), ['class' => 'control-label']) !!}

            {!! Form::text('email', old('email', optional($contact)->email), [
                'class' => 'form-control' . ($errors->has('email') ? ' is-invalid' : null),
                'minlength' => '1',
                'maxlength' => '100',
                'required' => false,
                'placeholder' => __('Email address'),
            ]) !!}
            @error('email')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>

    <div class="col-lg-12 mb-2">
        <label class="control-label">{{ __('Phone') }}</label>
        <span class="text-required">*</span>
        <div class="input-group">
            
            {{-- <div class="input-group-prepend">
                {!! Form::select('cc',config('enums.tel_codes'),old('cc', optional($contact)->cc), ['class' => 'form-control selectpicker', 'id' => 'cc', 'required' => true,'data-live-search'=>"true", 'placeholder' => 'country code']) !!}
            </div> --}}
            
            {!! Form::text('tel_no', old('tel_no', optional($contact)->tel_no), [
                'class' => 'form-control' . ($errors->has('tel_no') ? ' is-invalid' : null),
                'minlength' => '1',
                'maxlength' => '100',
                'required' => true,
                'placeholder' => __('Enter tel no here...'),
            ]) !!}
            
            @error('tel_no')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror

                {{-- @error('cc')
                    <p class="help-block  text-danger"> {{ $message }} </p>
                @enderror  --}}

        </div>
    </div>

    <div class="col-lg-12">
        <div class="form-group @error('contact_groups') has-error @enderror">
            {!! Form::label('contact_groups', __('Contact Group'), ['class' => 'control-label']) !!}

            <!-- {!! Form::select('contact_groups', $contact_groups, old('contact_groups', optional($contact)->contact_groups), [
                'multiple' => 'multiple',
                'name' => 'contact_groups[]',
                'class' => 'form-control selectpicker' . ($errors->has('contact_groups') ? ' is-invalid' : null),
                'maxlength' => '100',
                'data-actions-box' => 'true',
            ]) !!} -->

            {!! Form::select('contact_groups', $contact_groups, old('contact_groups', optional($contact)->contact_groups), [
                'multiple' => 'multiple',
                'name' => 'contact_groups[]',
                'class' => 'form-control ' . ($errors->has('contact_groups') ? ' is-invalid' : null),
                'maxlength' => '100',
                'required' => true,
                'data-actions-box' => 'true',
            ]) !!}


            @error('contact_groups')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>

    <div class="col-lg-12">
        <div class="form-group @error('address') has-error @enderror">
            {!! Form::label('address', __('Address'), ['class' => 'control-label']) !!}

            {!! Form::textarea('address', old('address', optional($contact)->address), [
                'class' => 'form-control' . ($errors->has('address') ? ' is-invalid' : null),
                'minlength' => '1',
                'maxlength' => '100',
                'required' => false,
                'rows' => 3,
                'placeholder' => __('Address'),
            ]) !!}
            @error('address')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>

    <div class="col-lg-6">
        <div class="form-group @error('city') has-error @enderror">
            {!! Form::label('city', __('City'), ['class' => 'control-label']) !!}

            {!! Form::text('city', old('city', optional($contact)->city), [
                'class' => 'form-control' . ($errors->has('city') ? ' is-invalid' : null),
                'minlength' => '1',
                'maxlength' => '100',
                'required' => false,
                'placeholder' => __('City'),
            ]) !!}
            @error('city')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>

    <div class="col-lg-6">
        <div class="form-group @error('state') has-error @enderror">
            {!! Form::label('state', __('State'), ['class' => 'control-label']) !!}

            {!! Form::text('state', old('state', optional($contact)->state), [
                'class' => 'form-control' . ($errors->has('state') ? ' is-invalid' : null),
                'minlength' => '1',
                'maxlength' => '100',
                'required' => false,
                'placeholder' => __('State'),
            ]) !!}
            @error('state')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>

    <div class="col-lg-6">
        <div class="form-group @error('post_code') has-error @enderror">
            {!! Form::label('post_code', __('Post code'), ['class' => 'control-label']) !!}

            {!! Form::text('post_code', old('post_code', optional($contact)->post_code), [
                'class' => 'form-control' . ($errors->has('post_code') ? ' is-invalid' : null),
                'minlength' => '1',
                'maxlength' => '100',
                'required' => false,
                'placeholder' => __('Post code'),
            ]) !!}
            @error('post_code')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>

    <div class="col-lg-6">
                
        <div class="from-group @error('country') has-error @enderror">
            {!! Form::label('country', __('Country'), ['class' => 'control-label']) !!}
            {!! Form::select('country', config('enums.countries'), old('country', optional($contact)->country), [
                'class' => 'form-control selectpicker',
                'data-live-search' => 'true',
                'required' => false,
                'placeholder' => __('Country'),
            ]) !!}

            
            <p class="help-block invalid-feedback"> <strong></strong> </p>
            
        </div>
    </div>

    <div class="col-lg-12">
        <div class="form-group @error('notes') has-error @enderror">
            {!! Form::label('notes', __('Notes'), ['class' => 'control-label']) !!}

            {!! Form::textarea('notes', old('notes', optional($contact)->notes), [
                'class' => 'form-control' . ($errors->has('notes') ? ' is-invalid' : null),
                'minlength' => '1',
                'maxlength' => '100',
                'required' => false,
                'rows' => 3,
                'placeholder' => __('Notes'),
            ]) !!}
            @error('notes')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>

</div>

@if (app('request')->ajax())
    <input type="submit" id="btnSubmit" class="d-none">
    </form>

    <script type="text/javascript">
        $(document).ready(function() {
            $('.selectpicker').selectpicker();

            $("#contact_groups").selectize({
                delimiter: ",",
                persist: false,
                maxItems: null,
                create: function (input) {
                    return {
                        value: input,
                        text: input,
                        };

                    
                }
            });

            $("#contact_group").selectize({
                delimiter: ",",
                persist: false,
                create: function (input) {
                    return {
                        value: input,
                        text: input,
                    };
                },
            });
        
        });
    </script>
@endif
