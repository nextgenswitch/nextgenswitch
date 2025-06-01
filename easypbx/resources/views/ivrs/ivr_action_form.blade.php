@if (app('request')->ajax())
    <form method="{{ $method }}" action="{{ $action }}" accept-charset="UTF-8" id="create_form"
        name="create_form" class="form-horizontal">
        @csrf
@endif

<table class="table dynamicForm ajaxForm">
    <tr>
        <td style="border-top: none;"> {!! Form::label('digit', __('Digit'), ['class' => 'control-label']) !!} </td>
        <td style="border-top: none;"> {!! Form::label('function_id', __('Module'), ['class' => 'control-label']) !!} </td>
        <td style="border-top: none;"> {!! Form::label('destination_id', __('Destination'), ['class' => 'control-label']) !!} </td>
        <td style="border-top: none;"> {!! Form::label('voice', __('Voice Matchable Intent/Text'), ['class' => 'control-label']) !!} </td>
    </tr>

    @php
        $total = count($ivrAction);
    @endphp


    @if ($total)

        @foreach ($ivrAction as $key => $action)
            <tr>
                <td>
                    {!! Form::number('actions[digit][]', old('digit', optional($action)->digit), [
                        'class' => 'form-control digit',
                        'required' => false,
                        'min' => 0,
                        'max' => 99999,
                        'placeholder' => __('Enter Digit here...'),
                    ]) !!}
                    @error('digit')
                        <p class="help-block  text-danger"> {{ $message }} </p>
                    @enderror
                </td>

                <td>
                    {!! Form::select('actions[function_id][]', $functions, old('function_id', $action->func->func), [
                        'class' => 'form-control function_id',
                        'required' => false,
                        'placeholder' => __('Select module'),
                    ]) !!}
                    @error('function_id')
                        <p class="help-block  text-danger"> {{ $message }} </p>
                    @enderror
                </td>

                <td>
                    {!! Form::select(
                        'actions[destination_id][]',
                        $destinations[$action->func->func],
                        old('destination_id', optional($action)->destination_id),
                        ['class' => 'form-control destination_id', 'required' => false, 'placeholder' => __('Select destination')],
                    ) !!}
                    @error('destination_id')
                        <p class="help-block  text-danger"> {{ $message }} </p>
                    @enderror
                </td>

                <td>
                    {!! Form::text('actions[voice][]', old('voice', optional($action)->voice), [
                        'class' => 'form-control voice',
                        'required' => false,
                        'min' => 0,
                        'max' => 99999,
                        'placeholder' => __('Enter voice here...'),
                    ]) !!}
                    @error('voice')
                        <p class="help-block  text-danger"> {{ $message }} </p>
                    @enderror
                </td>


                <td>
                    @if ($key == $total - 1)
                        <button class="btn btn-danger btn-minus"> <i class="fa fa-minus"></i> </button>
                        <button class="btn btn-primary btn-plus"><i class="fa fa-plus"></i></button>
                    @else
                        <button class="btn btn-danger btn-minus"> <i class="fa fa-minus"></i> </button>
                    @endif
                </td>
            </tr>
        @endforeach
    @else
        <tr>
            <td>
                {!! Form::number('actions[digit][]', old('digit'), [
                    'class' => 'form-control digit',
                    'required' => false,
                    'min' => 0,
                    'max' => 99999,
                    'placeholder' => __('Enter Digit here...'),
                ]) !!}
                @error('digit')
                    <p class="help-block  text-danger"> {{ $message }} </p>
                @enderror
            </td>

            <td>
                {!! Form::select('actions[function_id][]', $functions, old('function_id'), [
                    'class' => 'form-control function_id',
                    'required' => false,
                    'placeholder' => __('Select module'),
                ]) !!}
                @error('function_id')
                    <p class="help-block  text-danger"> {{ $message }} </p>
                @enderror
            </td>

            <td>
                {!! Form::select('actions[destination_id][]', $destinations, old('destination_id'), [
                    'class' => 'form-control destination_id',
                    'required' => false,
                    'placeholder' => __('Select destination'),
                ]) !!}
                @error('destination_id')
                    <p class="help-block  text-danger"> {{ $message }} </p>
                @enderror
            </td>

            <td>
                {!! Form::text('actions[voice][]', old('voice'), [
                    'class' => 'form-control voice',
                    'required' => false,
                    'min' => 0,
                    'max' => 99999,
                    'placeholder' => __('Enter voice here...'),
                ]) !!}
                @error('voice')
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




@if (app('request')->ajax())
    <input type="submit" id="btnSubmit" class="d-none">
    </form>
@endif


@push('script')
    <script>
        $(document).ready(function() {
            const destinationRoute = "{{ route('ivr_actions.ivr_action.destinations', 0) }}";
            $crud = $('#crud_contents').crud();

            $('.dynamicForm').on('click', '.btn-plus', function(e) {
                e.preventDefault();
                const originalRow = $(this).closest('tr');
                const copiedRow = originalRow.clone();
                // copiedRow.find('.btn-minus').remove();

                originalRow.find('td:last').html(
                    '<button class="btn btn-danger btn-minus"> <i class="fa fa-minus"></i> </button>');


                copiedRow.find('input').val('');


                copiedRow.find('select').prop('selectedIndex', 0);
                copiedRow.find('input[type="checkbox"]').prop('checked', false);


                originalRow.after(copiedRow);
            });


            $('.dynamicForm').on('click', '.btn-minus', function(e) {
                e.preventDefault();
                console.log($(".dynamicForm tr").length)

                if ($(".dynamicForm tr").length > 2) {
                    const selectedRow = $(this).closest('tr');

                    if (selectedRow.find('.btn-plus').length > 0) {
                        const prevRow = selectedRow.prev();
                        console.log(prevRow)
                        prevRow.find('td:last').html(
                            '<button class="btn btn-danger btn-minus"> <i class="fa fa-minus"></i> </button><button class="btn btn-primary btn-plus"><i class="fa fa-plus"></i></button>'
                        );
                    }

                    selectedRow.remove();
                }

            });

            $('.ajaxForm').on('change', '.function_id', function() {
                const value = $(this).val();

                if (!value) {
                    return;
                }

                const row = $(this).closest('tr');
                const destination = row.find('.destination_id');
                const ivrId = $("#ivr_id").val().trim();


                const route = `${destinationRoute.slice(0, -1)}${value}/${ivrId}`;


                $.get(route, function(res) {
                        destination.html(res);
                    })
                    .fail(function() {

                    });


                destination.html('<option> Select destination </option>');
            });

            $(".ajaxForm").on('blur', '.digit', function(e) {
                const _this = this;
                const current_item_digit = $(_this).val().trim();

                console.log(current_item_digit);

                console.log($('.digit').not(_this))

                $('.digit').not(_this).each((index, item) => {

                    if ($(item).val().trim() == current_item_digit) {
                        $(_this).val('');
                        $crud.showToast("Input number already exists");
                    }
                });

            })

        });
    </script>
@endpush
