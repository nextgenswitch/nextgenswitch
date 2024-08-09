@if (app('request')->ajax())
    <form method="{{ $method }}" action="{{ $action }}" accept-charset="UTF-8" id="create_form"
        name="create_form" class="form-horizontal">
        @csrf
@endif

<table class="table">
    <tr>
        <td> {!! Form::label('extension_id',__('Extension'),['class' => 'control-label']) !!} </td>
        <td> {!! Form::label('member_type',__('Member Type'),['class' => 'control-label']) !!} </td>
        <td> {!! Form::label('priority',__('Priority'),['class' => 'control-label']) !!} </td>
        <td> {!! Form::label('allow_diversion',__('Allow Diversion'),['class' => 'control-label']) !!} </td>
        <td></td>
    </tr>

    @php $total = count($callQueueExtension); @endphp


@if($total > 0)

    @foreach ($callQueueExtension as $key =>  $ext)
    
    
        <tr>
            <td>
                {!! Form::select('extensions[extension_id][]', $extensions, old('extension_id', optional($ext)->extension_id), [
                    'class' => 'form-control extension_id',
                    'required' => false,
                    'placeholder' => __('Select extension'),
                ]) !!}
                @error('extension_id')
                    <p class="help-block  text-danger"> {{ $message }} </p>
                @enderror
            </td>

            <td>
                {!! Form::select('extensions[member_type][]', config('enums.member_type'), old('member_type', optional($ext)->member_type), [
                    'class' => 'form-control' . ($errors->has('member_type') ? ' is-invalid' : null),
                    'minlength' => '1',
                    'required' => false
                ]) !!}

                @error('member_type')
                    <p class="help-block  text-danger"> {{ $message }} </p>
                @enderror
            </td>

            <td>
                {!! Form::number('extensions[priority][]', old('priority', optional($ext)->priority), [
                        'class' => 'form-control' . ($errors->has('priority') ? ' is-invalid' : null),
                        'min' => '0',
                        'max' => '2147483647',
                        'required' => false,
                        'placeholder' => __('Enter priority here...'),
                    ]) !!}
                    @error('priority')
                        <p class="help-block  text-danger"> {{ $message }} </p>
                    @enderror
            </td>

            <td>
                <div class="checkbox">
                    <label for='allow_diversion'>
                        {!! Form::checkbox(
                            'extensions[allow_diversion][]',
                            '1',
                            old('allow_diversion', optional($ext)->allow_diversion) == '1' ? true : null,
                            [ 'class' => '' . ($errors->has('allow_diversion') ? ' is-invalid' : null)],
                        ) !!}
                        {{ __('True') }}
                    </label>
                </div>

                @error('allow_diversion')
                    <p class="help-block  text-danger"> {{ $message }} </p>
                @enderror
            </td>

            <td>
                <td>
                    @if($key == ($total - 1))
                    <button class="btn btn-danger btn-minus"> <i class="fa fa-minus"></i> </button>
                    <button class="btn btn-primary btn-plus"><i class="fa fa-plus"></i></button>
                    
                    @else
                    <button class="btn btn-danger btn-minus"> <i class="fa fa-minus"></i> </button>
                    @endif
                </td>
            </td>

        </tr>

    @endforeach

@else

<tr>
    <td>
        {!! Form::select('extensions[extension_id][]', $extensions, old('extension_id', optional($callQueueExtension)->extension_id), [
            'class' => 'form-control extension_id',
            'required' => false,
            'placeholder' => __('Select extension'),
        ]) !!}
        @error('extension_id')
            <p class="help-block  text-danger"> {{ $message }} </p>
        @enderror
    </td>

    <td>
        {!! Form::select('extensions[member_type][]', config('enums.member_type'), old('member_type', optional($callQueueExtension)->member_type), [
            'class' => 'form-control' . ($errors->has('member_type') ? ' is-invalid' : null),
            'minlength' => '1',
            'required' => false
        ]) !!}

        @error('member_type')
            <p class="help-block  text-danger"> {{ $message }} </p>
        @enderror
    </td>

    <td>
        {!! Form::number('extensions[priority][]', old('priority', optional($callQueueExtension)->priority), [
                'class' => 'form-control' . ($errors->has('priority') ? ' is-invalid' : null),
                'min' => '0',
                'max' => '2147483647',
                'required' => false,
                'placeholder' => __('Enter priority here...'),
            ]) !!}
            @error('priority')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
    </td>

    <td>
        <div class="checkbox">
            <label>
                {!! Form::checkbox(
                    'extensions[allow_diversion][]',
                    '1',
                    old('allow_diversion', optional($callQueueExtension)->allow_diversion) == '1' ? true : null,
                    ['class' => '' . ($errors->has('allow_diversion') ? ' is-invalid' : null)],
                ) !!}
                {{ __('True') }}
            </label>
        </div>

        @error('allow_diversion')
            <p class="help-block  text-danger"> {{ $message }} </p>
        @enderror
    </td>

    <td>
        <button class="btn btn-primary btn-plus">
            <i class="fa fa-plus"></i>
        </button>
    </td>

</tr>

@endif


</table>




@push('script')
    <script src="{{  asset('js/dynamic_form.js') }}"> </script>
@endpush