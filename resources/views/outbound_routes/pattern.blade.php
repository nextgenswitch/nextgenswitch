<div class="card">
    <div class="card-header">Pattern</div>
    <div class="card-body">
        <table class="table dynamicForm">
            <tr>
                <td style="border-top: none;"> {!! Form::label('prefix_append', __('Prefix Append'), ['class' => 'control-label']) !!} </td>
                <td style="border-top: none;"> {!! Form::label('prefix_remove', __('Prefix Remove'), ['class' => 'control-label']) !!} </td>
                <td style="border-top: none;"> {!! Form::label('pattern', __('Pattern'), ['class' => 'control-label']) !!} </td>
                <td style="border-top: none;"> {!! Form::label('cid_pattern', __('CID Pattern'), ['class' => 'control-label']) !!} </td>
            </tr>

            @php
                $total = count($pattern);
            @endphp


            @if ($total)

                @foreach ($outboundRoute->pattern as $key => $pattern)
                
                    <tr>
                        <td>
                            {!! Form::text('pattern[prefix_append][]', old('prefix_append', optional($pattern)->prefix_append), [
                                'class' => 'form-control prefix_append',
                                'required' => false,
                                'placeholder' => __('Enter prefix append here...'),
                            ]) !!}
                            @error('prefix_append')
                                <p class="help-block  text-danger"> {{ $message }} </p>
                            @enderror
                        </td>

                        <td>
                            {!! Form::text('pattern[prefix_remove][]', old('prefix_remove', optional($pattern)->prefix_remove), [
                                'class' => 'form-control prefix_remove',
                                'required' => false,
                                'placeholder' => __('Enter prefix remove here...'),
                            ]) !!}
                            @error('prefix_remove')
                                <p class="help-block  text-danger"> {{ $message }} </p>
                            @enderror
                        </td>

                        <td>
                            {!! Form::text('pattern[pattern][]', old('pattern', optional($pattern)->pattern), [
                                'class' => 'form-control pattern',
                                'required' => false,
                                'placeholder' => __('Enter pattern here...'),
                            ]) !!}
                            @error('pattern')
                                <p class="help-block  text-danger"> {{ $message }} </p>
                            @enderror
                        </td>

                        <td>
                            {!! Form::text('pattern[cid_pattern][]', old('cid_pattern', optional($pattern)->cid_pattern), [
                                'class' => 'form-control cid_pattern',
                                'required' => false,
                                'placeholder' => __('Enter cid pattern here...'),
                            ]) !!}
                            @error('cid_pattern')
                                <p class="help-block  text-danger"> {{ $message }} </p>
                            @enderror
                        </td>


                        <td style="width: 12%">
                            @if ($key == $total - 1)
                                <button class="btn btn-sm  btn-danger btn-minus"> <i class="fa fa-minus"></i> </button>
                                <button class="btn btn-sm  btn-primary btn-plus"><i class="fa fa-plus"></i></button>
                            @else
                                <button class="btn  btn-sm btn-danger btn-minus"> <i class="fa fa-minus"></i> </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td>
                        {!! Form::text('pattern[prefix_append][]', old('prefix_append', optional($pattern)->prefix_append), [
                            'class' => 'form-control prefix_append',
                            'required' => false,
                            'placeholder' => __('Enter prefix append here...'),
                        ]) !!}
                        @error('prefix_append')
                            <p class="help-block  text-danger"> {{ $message }} </p>
                        @enderror
                    </td>

                    <td>
                        {!! Form::text('pattern[prefix_remove][]', old('prefix_remove', optional($pattern)->prefix_remove), [
                            'class' => 'form-control prefix_remove',
                            'required' => false,
                            'placeholder' => __('Enter prefix remove here...'),
                        ]) !!}
                        @error('prefix_remove')
                            <p class="help-block  text-danger"> {{ $message }} </p>
                        @enderror
                    </td>

                    <td>
                        {!! Form::text('pattern[pattern][]', old('pattern', optional($pattern)->pattern), [
                            'class' => 'form-control pattern',
                            'required' => false,
                            'placeholder' => __('Enter pattern here...'),
                        ]) !!}
                        @error('pattern')
                            <p class="help-block  text-danger"> {{ $message }} </p>
                        @enderror
                    </td>

                    <td>
                        {!! Form::text('pattern[cid_pattern][]', old('cid_pattern', optional($pattern)->cid_pattern), [
                            'class' => 'form-control cid_pattern',
                            'required' => false,
                            'placeholder' => __('Enter cid pattern here...'),
                        ]) !!}
                        @error('cid_pattern')
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
    </div>
</div>
@push('script')
    <script>
        $(document).ready(function() {
            $('.dynamicForm').on('click', '.btn-plus', function(e) {
                e.preventDefault();
                const originalRow = $(this).closest('tr');
                const copiedRow = originalRow.clone();
                // copiedRow.find('.btn-minus').remove();

                originalRow.find('td:last').html(
                    '<button class="btn btn-sm btn-danger btn-minus"> <i class="fa fa-minus"></i> </button>');


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
                            '<button class="btn btn-danger btn-sm btn-minus mr-2"> <i class="fa fa-minus"></i> </button><button class="btn btn-primary btn-sm btn-plus"><i class="fa fa-plus"></i></button>'
                        );
                    }

                    selectedRow.remove();
                }

            });
        })
    </script>
@endpush
