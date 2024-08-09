@if(app('request')->ajax())
<form method="{{ $method }}" action="{{ $action }}" accept-charset="UTF-8" id="create_form" name="create_form" class="form-horizontal">
@csrf
@endif

<div class="row">

    <div class="col-lg-12">
        <div class="form-group @error('title') has-error @enderror">
            {!! Form::label('title',__('Title'),['class' => 'control-label']) !!}
            <span class="text-required">*</span>
          
        {!! Form::text('title',old('title', optional($ipBlackList)->title), ['class' => 'form-control' . ($errors->has('title') ? ' is-invalid' : null), 'minlength' => '1', 'maxlength' => '191', 'required' => true, 'placeholder' => __('Enter title here...'), ]) !!}
                @error('title') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
        </div>
        </div>

<div class="col-lg-12">
<div class="form-group @error('ip') has-error @enderror">
    {!! Form::label('ip',__('Ip'),['class' => 'control-label']) !!}
  
    {!! Form::text('ip',old('ip', optional($ipBlackList)->ip), ['class' => 'form-control' . ($errors->has('ip') ? ' is-invalid' : null), 'required' => true, 'placeholder' => __('Enter ip here...'), ]) !!}
        @error('ip') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>

<div class="col-lg-12">
<div class="form-group @error('subnet') has-error @enderror">
    {!! Form::label('subnet',__('Subnet'),['class' => 'control-label']) !!}
    <span class="text-required">*</span>
  
    {!! Form::select('subnet', config('enums.subnet'), old('subnet', optional($ipBlackList)->subnet), [
        'class' => 'form-control',
        'required' => true,
        'placeholder' => __('subnet'),
    ]) !!}

        @error('subnet') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>

</div>

@if(app('request')->ajax())
<input type="submit" id="btnSubmit" class="d-none">
</form>
<script>
    $(document).ready(function() {
    
        var ipv4_address = $('#ip');
        ipv4_address.inputmask({
            alias: "ip",
            greedy: false 
        });
    })
</script>
@endif


@push('script')
<script>
    $(document).ready(function() {
    
        var ipv4_address = $('#ip');
        ipv4_address.inputmask({
            alias: "ip",
            greedy: false 
        });
    })
</script>
@endpush