@push('css')
<link rel="stylesheet" href="{{ asset('js/jquery-ui/jquery-ui.min.css') }}">

<link rel="stylesheet" href="{{ asset('js/flatpickr/flatpickr.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/mdtimepicker.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/mdtimepicker-theme.css') }}">

<style>
    ..custom-form-elements{
        
    }

    .custom-form-elements .element{
        display: inline-block;
        font-size: 15px;
        width: 28%;
        height: 50px;
        border: 1px solid #151535;
        border-radius: 3px;
        margin: 10px;
        padding: 5px;
        color: #1c1d22;
        cursor: pointer;
        text-align: center;
        background: #fbfbfb;
    }
    .custom-form-elements .element i,
    .custom-form-elements .element span{
        display: block;
    }
    .custom-form-elements .element i{
        font-size: 17px;
    }

    .custom-form-elements .element:hover{
        
            color: var(--brand-color);
            background-color: var(--primary-bg-color);
            border-color: var(--primary-bg-color);
        
    }

    #field_preview table tr td:first-child{
        vertical-align: bottom;
    }
</style>
@endpush
@if(app('request')->ajax())
<form method="{{ $method }}" action="{{ $action }}" accept-charset="UTF-8" id="create_form" name="create_form" class="form-horizontal">
@csrf
@endif

<div class="row">

    <div class="col-lg-12">
        <div class="form-group @error('name') has-error @enderror">
            {!! Form::label('name',__('Name'),['class' => 'control-label']) !!}
        
            {!! Form::text('name',old('name', optional($customForm)->name), ['class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : null), 'minlength' => '1', 'maxlength' => '191', 'required' => true, 'placeholder' => __('Enter name here...'), ]) !!}
                @error('name') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
        </div>
    </div>

    <input type="hidden" name="fields" id="fields">
</div>
</form>

<div id="customFormPreview">
    <table class="table table-borderless" id="sortable">
        
    </table>
</div>


<div class="modal fade" id="custom_form_modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="custom_form_modal_label">Custom Form Elements</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body custom-form-elements">
                <div class="element" field="text">
                    <i class="fa fa-font"></i>
                    <span>Text</span>
                </div>
                <div class="element" field="email">
                    <i class="fa fa-envelope-o" aria-hidden="true"></i>
                    <span>Email</span>
                </div>
                <div class="element" field="number">
                    <i class="fa fa-sort-numeric-asc" aria-hidden="true"></i>
                    <span>Number</span>
                </div>
                <div class="element" field="select">
                    <i class="fa fa-hand-pointer-o"></i>
                    <span>Select</span>
                </div>
                <div class="element" field="checkbox">
                    <i class="fa fa-check-square-o" aria-hidden="true"></i>
                    <span>Checkbox</span>
                </div>
                {{-- <div class="element" field="radio">
                    <i class="fa fa-dot-circle-o"></i>
                    <span>Radio Button</span>
                </div> --}}
                <div class="element" field="textarea">
                    <i class="fa fa-text-height"></i>
                    <span>Textarea</span>
                </div>
                <div class="element" field="date">
                    <i class="fa fa-calendar-o" aria-hidden="true"></i>
                    <span>Date</span>
                </div>

                <div class="element" field="time">
                    <i class="fa fa-clock-o" aria-hidden="true"></i>
                    <span>Time</span>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="custom_field_preview_modal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Input Field Preview</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body input-field-preview">
                <div  class="row">
                    <div class="col-6">
                        <div id="field_properties">
                            <form id="config-form">
                                <div class="form-group">
                                    <label for="field_name">Field Name</label>
                                    <input type="text" class="form-control" id="field_name" placeholder="Field Name">
                                </div>
                                <div class="form-group">
                                    <label for="placeholder">Placeholder</label>
                                    <input type="text" class="form-control" id="placeholder" placeholder="Enter your first name">
                                </div>

                                <div class="form-group select-options d-none">
                                    <label for="options">Options (comma separated)</label>
                                    <input type="text" class="form-control" id="options" placeholder="Option A, B, C, D, E">
                                </div>
                                
                                

                                <div class="row pb-3" id="requireReadonly">
                                    <div class="col-lg-6">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="checkRequired">
                                            <label class="custom-control-label" for="checkRequired">Required</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="checkedReadonly">
                                            <label class="custom-control-label" for="checkedReadonly">Readonly</label>
                                        </div>
                                    </div>
                                    
                                </div>

                                
                            </form>
                        </div>
                    </div>
                    <div class="col-6">
                        <div id="field_preview">
                            <table class="table table-borderless">
                                <tr>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button class="btn btn-primary pull-right" id="addFieldButton">Add Field</button>
            </div>
        </div>
    </div>
</div>

@push('script')
<script src="{{ asset('js/jquery-ui/jquery-ui.min.js') }}"></script>
<script src="{{ asset('js/flatpickr/flatpickr.js') }}"></script>

<script src="{{ asset('js/plugins/mdtimepicker.min.js') }}"></script>

<script>
    
    


    $(document).ready(function(){
        $("#sortable").sortable();
        $("#sortable").disableSelection();

        var formElements = [];
        
            data = @json(old('fields', optional($customForm)->fields));
            console.log(data)

            if(data !== null){
                formElements = JSON.parse(data)
                console.log(formElements);
                buildForm();
                loadPlugins();
            }
            

            

        // if ($("#edit_custom_form_form").length > 0) {
        //     data = @json(optional($customForm)->fields);
        //     formElements = JSON.parse(data)
        //     console.log(formElements);
        //     buildForm();
        // }

        var element = {
            label: 'Field Name',
            name: 'field_name',
            placeholder: 'Enter field name here',
            required: false,
            readonly: false,
            options: [],
        };

        
        $("#sortable").on( "sortstop", function( event, ui ) {
            var temp = [];

            $("#sortable tr").each((index, item) => {
                console.log(item)

                var trIndex = $(item).attr('index');
                if(trIndex != undefined && trIndex >= 0){
                    temp.push(Object.assign({}, formElements[trIndex])); 
                }
                
            })

            console.log(temp);

            formElements = temp;

            console.log(formElements);

            buildForm();
            loadPlugins();

        } );

        $(".submit-custom-form").click(function(e){
            e.preventDefault();
            $(".custom-form").submit();

        })

        $(".custom-form").submit(function(e){
            e.preventDefault();         

            data = JSON.stringify(formElements);
            console.log(data);
            $("#fields").val(data);

            this.submit();
        });

        $(".custom-form-elements .element").click(function(){
            console.log('clicked');
            
            $("#addFieldButton").removeAttr('index');

            var currentType = $(this).attr('field');

            element.type = currentType
        
            if (currentType == 'select') {
                $('#custom_field_preview_modal .select-options').removeClass('d-none');
            } else {
                $('#custom_field_preview_modal .select-options').addClass('d-none');
            }

            if(currentType == 'checkbox' || currentType == 'radio'){
                $("#requireReadonly").addClass('d-none');
            }
            else{
                $("#requireReadonly").removeClass('d-none');
            }

            $("#field_preview table").html(generatePriviewField(element));

            $("#custom_form_modal").modal('toggle');
            $("#custom_field_preview_modal").modal('toggle');

            loadPlugins();
        })

        $("#field_name").keyup(function(e){
            e.preventDefault();
            element.label = $(this).val();
            console.log(element.label);
            element.name = generateName(element.label);
            console.log(element.label);
            $("#field_preview table").html(generatePriviewField(element));
            loadPlugins();
        });

        $("#placeholder").keyup(function(e){
            e.preventDefault();
            element.placeholder = $(this).val();
            console.log(element.placeholder);
            $("#field_preview table").html(generatePriviewField(element));
            loadPlugins();

        });

        $("#checkRequired").change(function(e){
            element.required = $(this).is(':checked');
            console.log(element.required);
            $("#field_preview table").html(generatePriviewField(element));
            loadPlugins();
        });

        $("#checkedReadonly").change(function(e){
            element.readonly = $(this).is(':checked');
            console.log(element.readonly);

            $("#field_preview table").html(generatePriviewField(element));
            loadPlugins();
        });

        $(document).on("click", ".field-edit", function(e){
            e.preventDefault();
            var index = $(this).attr('index');

            selectedElement = Object.assign({}, formElements[index]);
            $("#field_name").val(selectedElement.label);
            $("#placeholder").val(selectedElement.placeholder);

            if(selectedElement.options){
                var optns = [];

                $.each(selectedElement.options, (idx, item) => {
                    optns.push(item.text);
                })

                $("#options").val(optns.join());
            }

            if (selectedElement.type == 'select') {
                    $('#custom_field_preview_modal .select-options').removeClass('d-none');
            } else {
                $('#custom_field_preview_modal .select-options').addClass('d-none');
            }

            
            $('#checkRequired').prop('checked', selectedElement.required);
            $('#checkedReadonly').prop('checked', selectedElement.readonly);

            $("#field_preview table").html(generatePriviewField(selectedElement));
            $("#addFieldButton").attr('index', index);

            $("#custom_field_preview_modal").modal('toggle');

            element = Object.assign({}, selectedElement);

            loadPlugins();

        })

        $(document).on("click", '.field-delete', function(e){
            e.preventDefault();
            index = $(this).attr('index');
            
            let isDelete = confirm("Are you want to delete?");

            if(isDelete){
                formElements.splice(index, 1);
                buildForm();
                loadPlugins();
            }

            
        })

        $("#options").change(function(e){
            e.preventDefault();

            element.options = $('#options').val().split(',').map(function(option) {
                var trimmed = option.trim();
                return { value: trimmed.toLowerCase().replace(/\s+/g, '_'), text: trimmed };
            });

            console.log(element.options);

            $("#field_preview table").html(generatePriviewField(element));

        });


        $("#addFieldButton").click(function(){
            
            ElIndex = elementExists(element.name)


            if($(this).attr('index') !== undefined || $(this).attr('index') >= 0){
                ElIndex = $(this).attr('index');
            }

            console.log(ElIndex);


            if( ElIndex >= 0 ){
                formElements[ElIndex] = Object.assign({}, element);
            }
            else{
                formElements.push(Object.assign({}, element));
            }
            

            resetElement();
            $("#custom_field_preview_modal").modal('toggle');

            buildForm();
            loadPlugins();
        })

        function resetElement(){
           element.label = 'Field Name';
           element.name = 'field_name';
           element.placeholder = 'Enter field name here';
           element.required = false;
           element.readonly = false;

           $("#field_name").val('');
           $("#placeholder").val('');
           $("#options").val('');
           $('#checkRequired').prop('checked', false);
           $('#checkedReadonly').prop('checked', false);
        }

        $('#custom_field_preview_modal').on('hidden.bs.modal', function (e) {
            resetElement();
        })

        function buildForm(){
            console.log('generating form');
            var form  = '';
            console.log(formElements);


            
            $.each(formElements, function(index, item){
                tr = generatePriviewField(item);
                tr = $(tr).attr('index', index);
                tr = $(tr).append('<td> <button class="btn btn-sm btn-primary field-edit" index="'+ index +'"> <i class="fa fa-edit"></i> </button> <button class="btn btn-sm btn-danger field-delete" index="'+ index +'"> <i class="fa fa-trash"></i> </button> </td>');
                // console.log(tr.prop('outerHTML'))
                form += tr.prop('outerHTML');
                
            });

            console.log(form);

            $("#customFormPreview table").html(form);
        }

        function elementExists(field_name){
            var foundIndex = -1;

            $.each(formElements, (index, item) => {
                // console.log(index, item);
                if(item.name == field_name) foundIndex = index;
            });


            return foundIndex;
        }  

        function generateName(str) {
            return str
                .toLowerCase()         
                .trim()                
                .replace(/\s+/g, '_')  
                .replace(/[^\w\-]+/g, ''); 
            }

        function generatePriviewField(item){
            console.log(item)

            var field = '';

            field = '<tr><td class="text-right" width="30%"><label for="followup">' + item.label;
            
            if(item.required){
                field += '<span class="text-required">*</span>';
            }
                
            field += '</label></td><td>';

            switch(item.type) {
                case 'text':
                case 'email':
                case 'number':
                    input = $('<input>')
                        .attr('type', item.type)
                        .attr('name', item.name)
                        .attr('required', item.required)
                        .attr('readonly', item.readonly)
                        .attr('placeholder', item.placeholder)
                        .addClass('form-control');
                    break;
                case 'textarea':
                    input = $('<textarea></textarea>')
                        .attr('name', item.name)
                        .attr('required', item.required)
                        .attr('readonly', item.readonly)
                        .attr('placeholder', item.placeholder)
                        .addClass('form-control');
                    break;
                case 'select':
                    input = $('<select></select>')
                        .attr('name', item.name)
                        .attr('required', item.required)
                        .attr('readonly', item.readonly)
                        .addClass('form-control');
                    
                    var plholder = $('<option></option>')
                        .attr('value', "")
                        .text(item.placeholder);
                    input.append(plholder);
                    item.options.forEach(function(option) {
                        var opt = $('<option></option>')
                            .attr('value', option.value)
                            .text(option.text);
                        input.append(opt);
                    });
                    break;

                case 'checkbox':
                    input = $('<div></div>').addClass('custom-control custom-switch');
                    var ccinput = $('<input>')
                        .attr('type', item.type)
                        .attr('name', item.name)
                        .attr('id', item.name)
                        // .attr('required', item.required)
                        // .attr('readonly', item.readonly)
                        // .attr('placeholder', item.placeholder)
                        .addClass('custom-control-input');
                    input.append(ccinput);
                    var label = '<label class="custom-control-label" for="'+ item.name +'">'+ item.placeholder+'</label>';
                    input.append(label);
                    break;

                case 'date':
                    input = $('<input>')
                        .attr('type', 'text')
                        .attr('name', item.name)
                        .attr('required', item.required)
                        .attr('readonly', item.readonly)
                        .attr('placeholder', item.placeholder)
                        .addClass('form-control date');
                    break

                case 'time':
                    input = $('<input>')
                        .attr('type', 'text')
                        .attr('name', item.name)
                        .attr('required', item.required)
                        .attr('readonly', item.readonly)
                        .attr('placeholder', item.placeholder)
                        .addClass('form-control time');
                    break
                    
                default:
                    console.log('Unsupported input type: ' + item.type);
            }

            
            field += input.prop('outerHTML') + '</td></tr>';

            return field;    
        }  

        function loadPlugins(){
            $(".date").flatpickr({
                dateFormat: "Y-m-d",
            });
            mdtimepicker('.time', {
                theme: 'dark', 
                clearBtn: false, 
                is24hour: true,
            });
        }
        
    });
</script>
    
@endpush


