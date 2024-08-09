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

            {!! Form::text('name', old('name', optional($survey)->name), [
                'class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : null),
                'minlength' => '1',
                'maxlength' => '191',
                'required' => true,
                'placeholder' => __('Enter name here...'),
            ]) !!}
            @error('name')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>

    {{-- <div class="col-lg-12">
<div class="form-group @error('voice_id') has-error @enderror">
    {!! Form::label('voice_id',__('Welcome Voice'),['class' => 'control-label']) !!}
  
        {!! Form::select('voice_id',$voices,old('voice_id', optional($survey)->voice_id), ['class' => 'form-control', 'required' => true, 'placeholder' => __('Select voice'), ]) !!}
        @error('voice_id') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div> --}}

    <div class="col-lg-12">
        <div class="form-group @error('voice_id') has-error @enderror">
            {!! Form::label('voice_id', __('Voice'), ['class' => 'control-label']) !!}
            <span class="text-required">*</span>
            
            <div class="input-group voice-preview">
                {!! Form::select('voice_id', $voices, old('voice_id', optional($survey)->voice_id), [
                    'class' => 'form-control',
                    'required' => true,
                    'placeholder' => __('Select voice'),
                ]) !!}

                <div class="input-group-append">
                    <button class="btn btn-outline-secondary play" type="button">
                        <i class="fa fa-play"></i>
                    </button>

                    <button class="btn btn-outline-secondary stop d-none" type="button">
                        <i class="fa fa-stop"></i>
                    </button>

                </div>

            </div>
            @error('voice_id')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>

  



    <div class="col-lg-12">
        <div class="form-group @error('type') has-error @enderror">
            {!! Form::label('type', __('Input Type'), ['class' => 'control-label']) !!}
            <span class="text-required">*</span>

            {!! Form::select('type', config('enums.survey_type'), old('type', optional($survey)->type), [
                'class' => 'form-control',
                'required' => true,
            ]) !!}

            @error('type')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div> 



    <div class="col-lg-12 mb-3">
        <div class="card">
            <div class="card-header"> DTMF Keys</div>


            <table class="table dynamicForm ajaxForm" id="dynamicFormTable">
                <tr>
                    <td style="border-top: none;"> {!! Form::label('key', __('Digit'), ['class' => 'control-label']) !!} </td>
                    <td style="border-top: none;"> {!! Form::label('text', __('Caption'), ['class' => 'control-label']) !!} </td>
                    <td style="border-top: none;"> {!! Form::label('action', __('Action'), ['class' => 'control-label']) !!} </td>
                </tr>

            </table>
        </div>
    </div>


    <div class="col-lg-12">
        <div class="form-group @error('max_retry') has-error @enderror">
            {!! Form::label('max_retry', __('Retry If No Input'), ['class' => 'control-label']) !!}
            <span class="text-required">*</span>

            {!! Form::number('max_retry', old('max_retry', optional($survey)->max_retry), [
                'class' => 'form-control' . ($errors->has('max_retry') ? ' is-invalid' : null),
                'minlength' => '0',
                'maxlength' => '10',
                'required' => true,
                'placeholder' => __('Enter max retry here...'),
            ]) !!}
            @error('max_retry')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>


    <div class="col-lg-6">
        <div class="form-group @error('email') has-error @enderror">
            {!! Form::label('email', __('Email to send  response'), ['class' => 'control-label']) !!}

            {!! Form::text('email', old('email', optional($survey)->email), [
                'class' => 'form-control' . ($errors->has('email') ? ' is-invalid' : null),
                'minlength' => '1',
                'maxlength' => '191',
                'required' => false,
                'placeholder' => __('Enter email ...'),
            ]) !!}
            @error('email')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>

    <div class="col-lg-6">
        <div class="form-group @error('phone') has-error @enderror">
            {!! Form::label('phone', __('Sms to send response'), ['class' => 'control-label']) !!}

            {!! Form::text('phone', old('phone', optional($survey)->phone), [
                'class' => 'form-control' . ($errors->has('phone') ? ' is-invalid' : null),
                'minlength' => '1',
                'maxlength' => '191',
                'required' => false,
                'placeholder' => __('Enter tel no ...'),
            ]) !!}
            @error('phone')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>


    <div class="col-lg-6">
        <div class="form-group @error('function_id') has-error @enderror">
            {!! Form::label('function_id', __('Last Destination'), ['class' => 'control-label']) !!}
            <span class="text-required">*</span>
            
            @php
                $func = isset($survey->function->func) ? $survey->function->func : '';
            @endphp

            {!! Form::select('function_id', $functions, old('function_id', $func), [
                'class' => 'form-control',
                'required' => true,
                'placeholder' => __('Select function'),
            ]) !!}
            @error('function_id')
                <p class="help-block  text-danger"> {{ $message }} </p>
            @enderror
        </div>
    </div>


    <div class="col-lg-6">
        <div class="form-group @error('destination_id') has-error @enderror">
            {!! Form::label('destination_id', __('&nbsp;'), ['class' => 'control-label']) !!}

            {!! Form::select('destination_id', $destinations, old('destination_id', optional($survey)->destination_id), [
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

@if (app('request')->ajax())
    <input type="submit" id="btnSubmit" class="d-none">
    </form>
@endif

@push('script')
    <script>
        $(document).ready(function() {
            $crud = $('#crud_contents').crud();
            destinations = "{{ route('surveys.survey.destinations', 0) }}"


            $("#type").change(function(){
                var type = $(this).val();

                var required = type == 1 ? false : true;
                $("#dynamicFormTable .function_id").each((index , item) => {
                    $(item).attr('required', required);
                    console.log(item, index);
                })

                $("#dynamicFormTable .destination_id").each((index , item) => {
                    $(item).attr('required', required);
                    console.log(item, index);
                })

            })

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

        })
    </script>

    <script>
        $(document).ready(function() {

            $crud = $('#crud_contents').crud();

            var functions = @json($functions);

            var select_funcs = '<select required class="form-control function_id" name="actions[function_id][]">'
            select_funcs += '<option selected="selected" value="">Select module</option>'

            $.each(functions, (key, value) => {
                console.log(key, value)
                select_funcs += '<option value="' + key + '">' + value + '</option>';
            })

            select_funcs += '</select>';

            console.log(select_funcs)

            $(document).on('change', '#type', function(event) {
                event.preventDefault();

                if ($(this).val() == 1) {
                    $("#dynamicFormTable").closest('.card').hide();
                } else {
                    $("#dynamicFormTable").closest('.card').show();
                }
            })

            if ($("#edit_survey_form").length > 0) {
                var keys = $("#edit_survey_form").attr('keys');
                keys = JSON.parse(keys);

                if (keys.length > 0) {
                    $.each(keys, function(index, item) {
                        console.log(item)

                        $("#dynamicFormTable tr:last").after(generateRow(1));


                        $.each(item, (idx, itm) => {
                            console.log(idx, itm)

                            $("#dynamicFormTable tr:last").find('.key').val(item.key);
                            $("#dynamicFormTable tr:last").find('.text').val(item.text);
                            $("#dynamicFormTable tr:last").find('.function_id').val(getFucID(item.function_id));
                            $("#dynamicFormTable tr:last").find('.destination_id').html(item.destinations);
                            $("#dynamicFormTable tr:last").find('.destination_id').val(item.destination_id)
                        })


                    })

                    resetBtn()

                } else {
                    $("#dynamicFormTable tr:last").after(generateRow())

                }

            } else {
                $("#dynamicFormTable tr:last").after(generateRow())

            }




            $('.dynamicForm').on('click', '.btn-plus', function(e) {
                e.preventDefault();

                const originalRow = $(this).closest('tr');
                originalRow.after(generateRow());
                resetBtn()

            });


            $('.dynamicForm').on('click', '.btn-minus', function(e) {
                e.preventDefault();

                if ($(".dynamicForm tr").length > 2) {
                    const selectedRow = $(this).closest('tr');
                    selectedRow.remove();
                    resetBtn()
                }
            });





            function generateRow(edit = 0) {

                var row =
                    '<tr> <td> <input min="0" max="9" class="form-control key" placeholder="Enter digit here..." name="keys[key][]" type="number"></td>';
                row +=
                    '<td> <input class="form-control text" placeholder="Enter Caption here..." name="keys[text][]" type="text"></td>';

                row += '<td>' + select_funcs + '</td>'
                row +=
                    '<td> <select required name="actions[destination_id][]" class="form-control destination_id"> <option> Select destination </option> </select> </td>'

                if (edit > 0) {
                    row +=
                        '<td><button class="btn btn-danger btn-minus"> <i class="fa fa-minus"></i> </button></tr>';
                } else {
                    row +=
                        '<td> </button><button class="btn btn-primary btn-plus"><i class="fa fa-plus"></i></button></td></tr>';
                }


                return row;
            }


            function resetBtn() {
                var trs = $('#dynamicFormTable tr:not(:first-child):not(:last-child)');

                if (trs.length > 0) {
                    trs.each((index, item) => {
                        $(item).find('td:last').html(
                            '<button class="btn btn-danger btn-minus"> <i class="fa fa-minus"></i> </button>'
                        );
                    })

                }

                $('#dynamicFormTable tr:last-child').find('td:last').html(
                    '<button class="btn btn-primary btn-plus"><i class="fa fa-plus"></i></button> <button class="btn btn-danger btn-minus"> <i class="fa fa-minus"></i> </button>'
                )
            }


            $(document).on('change', '.function_id', function() {
                const value = $(this).val();

                if (!value) {
                    return;
                }

                destinationRoute = "{{ route('surveys.survey.destinations', 0) }}"
                const row = $(this).closest('tr');
                const destination = row.find('.destination_id');

                const route = `${destinationRoute.slice(0, -1)}${value}`;

                console.log(route)

                $.get(route, function(res) {
                        destination.html(res);
                    })
                    .fail(function() {

                    });


                destination.html('<option> Select destination </option>');
            });


            function getFucID(function_id){
                var func_lists = @json($func_list);
                // console.log( func_lists);

                return func_lists[function_id];
            }

            $("#type").trigger('change')

        });
    </script>
@endpush
