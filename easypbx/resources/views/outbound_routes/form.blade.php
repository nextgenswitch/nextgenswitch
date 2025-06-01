@push('css')
    
    <link rel="stylesheet" href="{{ asset('js/jquery-ui/jquery-ui.css') }}">
    <link rel="stylesheet" href="{{ asset('css/selectize.bootstrap4.min.css') }}">
@endpush

@if (app('request')->ajax())
    <form method="{{ $method }}" action="{{ $action }}" accept-charset="UTF-8" id="create_form"
        name="create_form" class="form-horizontal">
        @csrf
@endif

<div class="row">

    <div class="col-lg-12">
        <div class="form-group @error('name') has-error @enderror">
            {!! Form::label('name', __('Name'), ['class' => 'control-label']) !!}
            <span class="text-required">*</span>

            {!! Form::text('name', old('name', optional($outboundRoute)->name), [
                'class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : null),
                'minlength' => '1',
                'maxlength' => '255',
                'required' => true,
                'placeholder' => __('Enter name here...'),
            ]) !!}
            @error('name')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>

    <div class="col-lg-12">
        <div class="form-group @error('trunk_id') has-error @enderror">
            {!! Form::label('trunk_id', __('Trunk'), ['class' => 'control-label']) !!}
            <span class="text-required">*</span>

            {!! Form::select('trunk_id', $trunks, old('trunk_id', optional($outboundRoute)->trunk_id), [
                'name' => 'trunk_id[]',
                'multiple' => 'multiple',
                'class' => 'form-control',
                'required' => true,
                'id' => 'multivalto',
                'size' => '8',
                'placeholder' => __('Select trunk'),
            ]) !!}
            @error('trunk_id')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>

    <div class="col-lg-12">
        <div class="form-group @error('priority') has-error @enderror">
            {!! Form::label('priority', __('Priority'), ['class' => 'control-label']) !!}
            <span class="text-required">*</span>

            {!! Form::number('priority', old('priority', optional($outboundRoute)->priority), [
                'class' => 'form-control' . ($errors->has('priority') ? ' is-invalid' : null),
                'min' => '1',
                'required' => true,
                'placeholder' => __('Enter priority here...'),
            ]) !!}
            @error('priority')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>

    <!-- <div class="col-lg-12">
        <div class="form-group @error('pin_list_id') has-error @enderror">
            {!! Form::label('pin_list_id', __('Pin'), ['class' => 'control-label']) !!}

            {!! Form::select('pin_list_id', $pinList, old('pin_list_id', optional($outboundRoute)->pin_list_id), [
                'name' => 'pin_list_id',
                'class' => 'form-control',
                'required' => false,
                'placeholder' => __(''),
            ]) !!}
            @error('pin_list_id')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div> -->

    <div class="col-lg-6">
        <div class="form-group @error('is_active') has-error @enderror">
            {!! Form::label('is_active', __('Active?'), ['class' => 'control-label']) !!}
            {!! Form::hidden('is_active',0) !!}
            <div class="checkbox">
                <label for='is_active'>
                    {!! Form::checkbox(
                        'is_active',
                        '1',
                        old('is_active', optional($outboundRoute)->is_active) == '1' ? true : null,
                        ['id' => 'is_active', 'class' => '' . ($errors->has('is_active') ? ' is-invalid' : null)],
                    ) !!}
                    {{ __('Yes') }}
                </label>
            </div>

            @error('is_active')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group @error('is_active') has-error @enderror">
            {!! Form::label('record', __('Record?'), ['class' => 'control-label']) !!}
            {!! Form::hidden('record',0) !!}
            <div class="checkbox">
                <label for='is_active'>
                    {!! Form::checkbox(
                        'record',
                        '1',
                        old('record', optional($outboundRoute)->record) == '1' ? true : null,
                        ['id' => 'record', 'class' => '' . ($errors->has('record') ? ' is-invalid' : null)],
                    ) !!}
                    {{ __('Yes') }}
                </label>
            </div>

            @error('record')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>
   


    
    
    <div class="row">
        <div class="col-lg-12" style="margin-left: 15px;">
            @include('outbound_routes.pattern', [
                'pattern' => optional($outboundRoute)->pattern ? $outboundRoute->pattern : [],
            ])
        </div>
    </div>

    
    @include('partials.last_destination', [
        'functions' => $functions,
        'func' => isset($outboundRoute->func->func) ? $outboundRoute->func->func : '',
        'destinations' => $destinations,
        'destination_id' => optional($outboundRoute)->destination_id,
        'label'=>__('Failover Destination')
    ])

</div>

@if (app('request')->ajax())
    <input type="submit" id="btnSubmit" class="d-none">
    </form>
@endif

@push('script')
    <script src="{{ asset('js/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/selectize.min.js') }}"></script>

    <script>
        $(document).ready(function(){
            $("#multivalto").selectize({
                plugins: ["drag_drop", "remove_button"],
            })
        })
    </script>
@endpush
