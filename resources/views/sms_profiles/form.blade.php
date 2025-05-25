@if(app('request')->ajax())
<form method="{{ $method }}" action="{{ $action }}" accept-charset="UTF-8" id="create_form" name="create_form" class="form-horizontal">
@csrf
@endif

<div class="row">

<div class="col-lg-12">
<div class="form-group @error('name') has-error @enderror">
    {!! Form::label('name',__('Name'),['class' => 'control-label']) !!}
    <span class="text-required">*</span>
  
{!! Form::text('name',old('name', optional($smsProfile)->name), ['class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : null), 'minlength' => '1', 'maxlength' => '191', 'required' => true, 'placeholder' => __('Enter name here...'), ]) !!}
        @error('name') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div>

<div class="col-lg-12">
    <div class="form-group @error('provider') has-error @enderror">
        {!! Form::label('provider', __('Provider'), ['class' => 'control-label']) !!}
        <span class="text-required">*</span>
        <div class="input-group">    

            <select name="provider" id="provider" name="provider" class="form-control">
                
                @foreach (config('enums.sms_providers') as $key => $provider)
                    <option @selected($key == old('provider', optional($smsProfile)->provider)) value="{{ $key }}"> {{ $provider['name'] }} </option>    
                @endforeach

            </select>
        </div>
        @error('provider')
            <p class="help-block  text-danger"> {{ $message }} </p>
        @enderror
    </div>
</div>


{{-- <div class="col-lg-12">
    <div class="form-group @error('options') has-error @enderror">
        {!! Form::label('options',__('Options'),['class' => 'control-label']) !!}
        <span class="text-required">*</span>

            {!! Form::textarea('options', old('options', optional($smsProfile)->options), ['class' => 'form-control', 'required' => true, 'placeholder' => __('Enter options here...'), ]) !!}
            @error('options') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
    </div>
    </div> --}}

{{-- <div class="col-lg-12">
<div class="form-group @error('organization_id') has-error @enderror">
    {!! Form::label('organization_id',__('Organization'),['class' => 'control-label']) !!}
  
        {!! Form::select('organization_id',$organizations,old('organization_id', optional($smsProfile)->organization_id), ['class' => 'form-control', 'required' => true, 'placeholder' => __('Select organization'), ]) !!}
        @error('organization_id') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
</div>
</div> --}}
<div class="col-12 mb-3">
<div class="card">
    <div class="card-header">
        Provider Configuration 
    </div>

    <div class="card-body">
        <table class="table dynamicForm" id="provider_options">
            <tr>
                <th style="border-top: none;">Name</th>
                <th style="border-top: none;">Value</th>
            </tr>
        </table>
    </div>
</div>
</div>

<div class="col-lg-6">
    <div class="form-group @error('status') has-error @enderror">
        {!! Form::label('status', __('Active?'),['class' => 'control-label']) !!}
    
            <div class="checkbox">
                <label for='status'>
                    {!! Form::checkbox('status', '1',  (old('status', optional($smsProfile)->status) == '1' ? true : null) , ['id' => 'status', 'class' => ''  . ($errors->has('status') ? ' is-invalid' : null), ]) !!}
                    {{ __('Yes') }}
                </label>
            </div>
    
            @error('status') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
    </div>
    </div>
    
    <div class="col-lg-12">
        <div class="form-group @error('default') has-error @enderror">
            {!! Form::label('default', __('Default ?'),['class' => 'control-label']) !!}
    
                <div class="checkbox">
                    <label for='default'>
                        {!! Form::checkbox('default', '1',  (old('default', (isset($smsProfile->default) && $smsProfile->default == 1) ? true : null)) , ['id' => 'default', 'class' => ''  . ($errors->has('default') ? ' is-invalid' : null), ]) !!}
                        {{ __('Yes') }}
                    </label>
                </div>
    
                @error('default') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
        </div>
    </div>

</div>

@if(app('request')->ajax())
<input type="submit" id="btnSubmit" class="d-none">
</form>
@endif


@push('script')

<script>
    $(document).ready(function() {
        var placeholder = false;
        var options = JSON.parse(@json(optional($smsProfile)->options));
        var providers = @json(config('enums.sms_providers'));
        console.log(providers.FastSms);


        if(options === null){
            options = @json(config('enums.sms_providers.FastSms.options'));
            placeholder = true;
        }
        console.log(options)
        renderProvider(options);
        

        $("#provider").change(function(){
            $('#provider_options tr:not(:first)').remove();
            
            var selectedProvider = $(this).val()
            renderProvider(providers[selectedProvider]['options']);
        })

        function renderProvider(options){
            if(options != null && Object.keys(options).length > 0){
                $.each(options, function(name, value){
                    console.log(value)
                    $("#provider_options tr:last").after(generateRow(1));
                    $("#provider_options tr:last").find('.name').val(name)

                    if(placeholder){
                        $("#provider_options tr:last").find('.value').attr('placeholder', value)
                    }
                    else{
                        $("#provider_options tr:last").find('.value').val(value)
                    }
                    
                });
                
                resetBtn()
            }
            else{
                $("#provider_options tr:last").after(generateRow())
            }
        }
        

        function generateRow(edit = 0){
                
            var row = '<tr> <td> <input class="form-control name" placeholder="Enter name here..." name="options[name][]" type="text"></td>';
            row += '<td> <input class="form-control value" placeholder="Enter value here..." name="options[value][]" type="text"></td>';
            
            
            if(edit > 0){
                row += '<td><button class="btn btn-danger btn-minus"> <i class="fa fa-minus"></i> </button></tr>';
            }

            else{
                row += '<td> </button><button class="btn btn-primary btn-plus"><i class="fa fa-plus"></i></button></td></tr>';
            }
            

            return row;
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


            function resetBtn(){
                var trs = $('#provider_options tr:not(:first-child):not(:last-child)');
                
                if(trs.length > 0){
                    trs.each((index, item) => {
                        $(item).find('td:last').html(
                            '<button class="btn btn-danger btn-minus"> <i class="fa fa-minus"></i> </button>'
                        );
                    })

                }

                $('#provider_options tr:last-child').find('td:last').html('<button class="btn btn-primary btn-plus"><i class="fa fa-plus"></i></button> <button class="btn btn-danger btn-minus"> <i class="fa fa-minus"></i> </button>')
            }
    })

</script>

@endpush