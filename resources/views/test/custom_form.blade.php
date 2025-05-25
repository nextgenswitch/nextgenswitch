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
</style>


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
                <div class="element" field="radio">
                    <i class="fa fa-dot-circle-o"></i>
                    <span>Radio Button</span>
                </div>
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
                                
                                

                                <div class="row pb-3">
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
        </div>
    </div>
</div>


@push('script')

<script>
    $(document).ready(function(){
        var formElements = [];
    
        var element = {
            label: 'Field Name',
            name: 'field_name',
            placeholder: 'Enter field name here',
            required: false,
            readonly: false,
        };

    
        $(".custom-form-elements .element").click(function(){
            console.log('clicked');

            var currentType = $(this).attr('field');

            element.type = currentType
        
            if (currentType == 'select') {
                $('#custom_field_preview_modal .select-options').removeClass('d-none');
            } else {
                $('#custom_field_preview_modal .select-options').addClass('d-none');
            }

            $("#custom_form_modal").modal('toggle');
            $("#custom_field_preview_modal").modal('toggle');

        })

        $("#field_name").keyup(function(e){
            e.preventDefault();
            element.label = $(this).val();
            console.log(element.label);
            element.name = generateName(element.label);
            console.log(element.label);
            $("#field_preview table").html(generatePriviewField(element));
        });

        $("#placeholder").keyup(function(e){
            e.preventDefault();
            element.placeholder = $(this).val();
            console.log(element.placeholder);
            $("#field_preview table").html(generatePriviewField(element));

        });

        $("#checkRequired").change(function(e){
            element.required = $(this).is(':checked');
            console.log(element.required);
            $("#field_preview table").html(generatePriviewField(element));
        });

        $("#checkedReadonly").change(function(e){
            required = $(this).is(':checked');
            console.log(required);
            $("#field_preview table").html(generatePriviewField(element));
        });

        $("#options").change(function(e){
            e.preventDefault();

            element.options = $('#options').val().split(',').map(function(option) {
                var trimmed = option.trim();
                return { value: trimmed.toLowerCase().replace(/\s+/g, '_'), text: trimmed };
            });

            console.log(element.options);

            $("#field_preview table").html(generatePriviewField(element));
        });

        function generateName(str) {
            return str
                .toLowerCase()         
                .trim()                
                .replace(/\s+/g, '_')  
                .replace(/[^\w\-]+/g, ''); 
            }

        function generatePriviewField(element){
            var field = '';

            field = '<tr><td class="text-right" width="30%"><label for="followup">' + element.label + '</label></td><td width="70%">';

            switch(element.type) {
                case 'text':
                case 'email':
                case 'number':
                    input = $('<input>')
                        .attr('type', element.type)
                        .attr('name', element.name)
                        .attr('placeholder', element.placeholder)
                        .addClass('form-control');
                    break;
                case 'textarea':
                    input = $('<textarea></textarea>')
                        .attr('name', element.name)
                        .attr('placeholder', element.placeholder)
                        .addClass('form-control');
                    break;
                case 'select':
                    input = $('<select></select>')
                        .attr('name', element.name)
                        .addClass('form-control');
                    element.options.forEach(function(option) {
                        var opt = $('<option></option>')
                            .attr('value', option.value)
                            .text(option.text);
                        input.append(opt);
                    });
                    break;
                default:
                    console.log('Unsupported input type: ' + element.type);
            }

            

            field += input.prop('outerHTML') + '</td></tr>';

            return field;    
        }  






        var properties = {
            'text': {
                'name': '',
                'placeholder': '',
                'required': false,
                'readonly': false
            },

            'email': {
                'name': '',
                'placeholder': '',
                'required': false,
                'readonly': false
            },
            'number': {
                'name': {

                },
                'placeholder': '',
                'min': 0,
                'max': 1000,
                'required': false,
                'readonly': false
            },
            'select': {
                'name': '',
                'placeholder': '',
                'required': false,
                'readonly': false
            },
            'email': {
                'name': '',
                'placeholder': '',
                'required': false,
                'readonly': false
            },
            'email': {
                'name': '',
                'placeholder': '',
                'required': false,
                'readonly': false
            }
        }
        
            
        


        // $(".element").click(function(e){
        //     e.preventDefault();

        //     var name = $(this).attr('field');

        //     $("#custom_form_modal").modal('toggle');
        //     $("#custom_field_preview_modal").modal('toggle');

        //     console.log(properties)
        // })
    });


    $(document).ready(function() {
    var formJson = [
        {
            "type": "text",
            "label": "First Name",
            "name": "first_name",
            "placeholder": "Enter your first name"
        },
        {
            "type": "text",
            "label": "Last Name",
            "name": "last_name",
            "placeholder": "Enter your last name"
        },
        {
            "type": "email",
            "label": "Email",
            "name": "email",
            "placeholder": "Enter your email"
        },
        {
            "type": "password",
            "label": "Password",
            "name": "password",
            "placeholder": "Enter your password"
        },
        {
            "type": "textarea",
            "label": "Message",
            "name": "message",
            "placeholder": "Enter your message"
        },
        {
            "type": "select",
            "label": "Country",
            "name": "country",
            "options": [
                {"value": "us", "text": "United States"},
                {"value": "ca", "text": "Canada"},
                {"value": "uk", "text": "United Kingdom"}
            ]
        }
    ];

    function buildForm(container, data) {
        var form = $('<form></form>');

        data.forEach(function(item) {
            var formGroup = $('<div class="form-group"></div>');
            var label = $('<label></label>').attr('for', item.name).text(item.label);
            var input;

            switch(item.type) {
                case 'text':
                case 'email':
                case 'password':
                    input = $('<input>')
                        .attr('type', item.type)
                        .attr('name', item.name)
                        .attr('placeholder', item.placeholder)
                        .addClass('form-control');
                    break;
                case 'textarea':
                    input = $('<textarea></textarea>')
                        .attr('name', item.name)
                        .attr('placeholder', item.placeholder)
                        .addClass('form-control');
                    break;
                case 'select':
                    input = $('<select></select>')
                        .attr('name', item.name)
                        .addClass('form-control');
                    item.options.forEach(function(option) {
                        var opt = $('<option></option>')
                            .attr('value', option.value)
                            .text(option.text);
                        input.append(opt);
                    });
                    break;
                default:
                    console.log('Unsupported input type: ' + item.type);
            }

            formGroup.append(label).append(input);
            form.append(formGroup);
        });

        container.append(form);
    }

    var container = $('#form-container');
    buildForm(container, formJson);
});

</script>
    
@endpush