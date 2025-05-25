@if(app('request')->ajax())
<form method="{{ $method }}" action="{{ $action }}" accept-charset="UTF-8" id="create_form" name="create_form" class="form-horizontal">
@csrf
@endif

<table class="table dynamicForm ajaxForm">
    <tr>
        <td> {!! Form::label('digit',__('Digit'),['class' => 'control-label']) !!} </td>
        <td> {!! Form::label('function_id',__('Last Destination'),['class' => 'control-label']) !!} </td>
        <td> {!! Form::label('destination_id',__('Destination'),['class' => 'control-label']) !!} </td>
    </tr>

@php
    $total =count($ivrAction);
@endphp


@if($total)

    @foreach ($ivrAction as $key => $action)
        
        <tr>
            <td>
                {!! Form::number('actions[digit][]', old('digit', optional($action)->digit), ['class' => 'form-control digit', 'required' => true, 'min' => 0, 'max' => 99999, 'placeholder' => __('Enter Digit here...'), ]) !!}
                @error('digit') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
            </td>

            <td>
                {!! Form::select('actions[function_id][]', $functions ,old('function_id', $action->func->func), ['class' => 'form-control function_id', 'required' => true, 'placeholder' => __('Select module'), ]) !!}
                @error('function_id') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
            </td>

            <td>
                {!! Form::select('actions[destination_id][]',$destinations[$action->func->func], old('destination_id', optional($action)->destination_id), ['class' => 'form-control destination_id', 'required' => true, 'placeholder' => __('Select destination'), ]) !!}
                @error('destination_id') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
            </td>

            <td>
                @if($key == ($total - 1))
                <button class="btn btn-primary btn-plus">
                    <i class="fa fa-plus"></i>
                </button>
                @else
                <button class="btn btn-danger btn-minus"> <i class="fa fa-minus"></i> </button>
                @endif
            </td>
        </tr>
    @endforeach

@else

<tr>
    <td>
        {!! Form::number('actions[digit][]', old('digit'), ['class' => 'form-control digit', 'required' => true, 'min' => 0, 'max' => 99999, 'placeholder' => __('Enter Digit here...'), ]) !!}
        @error('digit') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
    </td>

    <td>
        {!! Form::select('actions[function_id][]', $functions ,old('function_id'), ['class' => 'form-control function_id', 'required' => true, 'placeholder' => __('Select module'), ]) !!}
        @error('function_id') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
    </td>

    <td>
        {!! Form::select('actions[destination_id][]',$destinations, old('destination_id'), ['class' => 'form-control destination_id', 'required' => true, 'placeholder' => __('Select destination'), ]) !!}
        @error('destination_id') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
    </td>

    <td>
        <button class="btn btn-primary btn-plus">
            <i class="fa fa-plus"></i>
        </button>
    </td>
</tr>


@endif

</table>




@if(app('request')->ajax())
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

            
                originalRow.find('td:last').html('<button class="btn btn-danger btn-minus"> <i class="fa fa-minus"></i> </button>');

            
                copiedRow.find('input').val('');

            
                copiedRow.find('select').prop('selectedIndex', 0);

            
                originalRow.after(copiedRow);
            });

            
            $('.dynamicForm').on('click', '.btn-minus', function(e) {
                e.preventDefault();
                $(this).closest('tr').remove();
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

            $(".ajaxForm").on('blur', '.digit', function(e){
                const _this = this;
                const current_item_digit = $(_this).val().trim();

                console.log(current_item_digit);

                console.log($('.digit').not(_this))

                $('.digit').not(_this).each((index, item) => {

                    if( $(item).val().trim() == current_item_digit){
                        $(_this).val('');
                        $crud.showToast("Input number already exists");
                    }
                });
                
            })  

            $(".validation").submit((e) => {
                console.log('test');

                return false;
            });

        });




    </script>
@endpush

