@extends('layouts.app')



@section('content')
@include('partials.message')



    <div class="panel panel-default">


        <div class="panel-heading clearfix">

            <div class="pull-left">
                <h4 class="tile-title">{{ __('Make A Call') }}</h4>
            </div>
        </div>


        <div class="panel-body panel-body-with-table">
            <div class="row d-block">
                <form method="post" action="{{ route('calling.call') }}" accept-charset="UTF-8" id="call_making_form"
                    name="create_form">
                    @csrf

                    <div class="col-lg-12">
                        <div class="form-group @error('to') has-error @enderror">
                            {!! Form::label('to', __('To'), ['class' => 'control-label']) !!}

                            <input type="number" name="to" id="to" class="form-control" required
                                placeholder="Enter Agent" value="{{ old('to') }}">
                            @error('to')
                                <p class="help-block  text-danger"> {{ $message }} </p>
                            @enderror
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <div class="form-group @error('from') has-error @enderror">
                            {!! Form::label('from', __('From'), ['class' => 'control-label']) !!}

                            <input type="number" name="from" id="from" class="form-control" required
                                placeholder="Enter from agent" value="{{ old('from') }}">
                            @error('from')
                                <p class="help-block  text-danger"> {{ $message }} </p>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group @error('function_id') has-error @enderror">
                                    {!! Form::label('function_id', __('Last Destination'), ['class' => 'control-label']) !!}
                                    <span class="text-required">*</span>

                                    {!! Form::select('function_id', $functions, old('function_id'), [
                                        'class' => 'form-control',
                                        'required' => true,
                                        'placeholder' => __('Select module'),
                                    ]) !!}
                                    @error('function_id')
                                        <p class="help-block  text-danger"> {{ $message }} </p>
                                    @enderror
                                </div>
                            </div>


                            <div class="col-lg-6">
                                <div class="form-group @error('destination_id') has-error @enderror">
                                    {!! Form::label('destination_id', __('&nbsp;'), ['class' => 'control-label']) !!}
                                    
                                    
                                    {!! Form::select('destination_id', $destinations, old('destination_id'), [
                                        'class' => 'form-control',
                                        'required' => true,
                                        'placeholder' => __('Select destination'),
                                    ]) !!}
                                    @error('destination_id')
                                        <p class="help-block  text-danger"> {{ $message }} </p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>


                    <input type="submit" value="{{ __('Send Call') }}" class="btn btn-block btn-primary">
                </form>

            </div>
        </div>


    </div>
@endsection

@push('script')
    <script src="{{ asset('js/index.js') }}"></script>


    <script type="text/javascript">
        $(document).ready(function() {

            $crud = $('#crud_contents').crud();

            destinations = "{{ route('calling.destinations', 0) }}"

            $(document).on('change', '#function_id', function(e) {
                e.preventDefault()

                var val = $(this).val().trim()

                if (val != undefined && val != '') {
                    route = destinations.trim().slice(0, -1) + val
                    console.log(route)

                    $.get(route, function(res) {
                        console.log(res)
                        $("#destination_id").html(res)
                    })

                } else
                    $("#destination_id").html('<option> Select destination </option>')

            })

            $("#call_making_form").submit(function(e) {
                e.preventDefault()

                var formData = $(this).serialize();

                $.ajax({
                    type: 'POST',
                    url: '{{ route('calling.call') }}',
                    data: formData,
                    success: function(response) {
                        console.log(response);
                        // response = JSON.parse(response);

                        if (response['error'] != undefined && response['error'] == true) {

                            $crud.showToast(response['error_message'], false);


                        }

                        if (response['call_id'] != undefined) {
                            $("#call_making_form").trigger('reset');
                            $crud.showToast('Call sent successfully');
                        }
                    },
                    error: function(error) {
                        console.error('Error occurred:', error);
                    }
                });
            })


        })
    </script>
@endpush
