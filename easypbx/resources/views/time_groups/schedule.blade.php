@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/mdtimepicker.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/mdtimepicker-theme.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('js/jquery-ui/jquery-ui.min.css') }}">
    
    
    <link rel="stylesheet" href="{{ asset('css/selectize.bootstrap4.min.css') }}" />
@endpush

<div class="card">
    <div class="card-header"> Schedules</div>


<table class="table dynamicForm ajaxForm" id="dynamicFormTable">
    <tr>
        <td style="border-top: none;" width="15%"> {!! Form::label('start_time', __('Start Time'), ['class' => 'control-label']) !!} </td>
        <td style="border-top: none;" width="15%"> {!! Form::label('function_id', __('End Time'), ['class' => 'control-label']) !!} </td>
        <td style="border-top: none;" width="20%"> {!! Form::label('week_days', __('Week Days'), ['class' => 'control-label']) !!} </td>
        <td style="border-top: none;" width="20%"> {!! Form::label('days', __('Days'), ['class' => 'control-label']) !!} </td>
        <td style="border-top: none;" width="20%"> {!! Form::label('months', __('Months'), ['class' => 'control-label']) !!} </td>
    </tr>

</table>
</div>

@push('script')

<script src="{{ asset('js/plugins/mdtimepicker.min.js') }}"></script>


<script src="{{ asset('js/jquery-ui/jquery-ui.min.js') }}"></script>
<script src="{{ asset('js/selectize.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            //const destinationRoute = "{{ route('ivr_actions.ivr_action.destinations', 0) }}";
            $crud = $('#crud_contents').crud();


            
            var multiselect = { plugins: ["drag_drop", "remove_button"]}
            
            
            if($("#edit_time_group_form").length > 0){
                var schedules = $("#edit_time_group_form").attr('schedules');
                schedules = JSON.parse(schedules);

                if(schedules.length > 0){
                    $.each(schedules, function(index, item){
                        console.log(item)
                        var className = generateRandomString(10);
                        $("#dynamicFormTable tr:last").after(generateRow(className, 1));
                        $('.' + className + ' .multiselect').selectize(multiselect);

                        $.each(item, (idx, itm) =>{
                            console.log(idx, itm)
                            
                            if(idx == 'start_time' || idx == 'end_time'){
                                $('.' + className + ' .' + idx).val(itm)
                            }

                            else{
                                $('.' + className + ' .' + idx)[0].selectize.setValue(itm.split(','))
                            }
                        })
                        
                    })

                    resetBtn()

                }
                else{
                    $("#dynamicFormTable tr:last").after(generateRow(generateRandomString(10)))
                    $(".multiselect").selectize(multiselect)
                }
                
            }
            else{
                $("#dynamicFormTable tr:last").after(generateRow(generateRandomString(10)))
                $(".multiselect").selectize(multiselect)
            }

            
            
            $('.selectpicker').selectpicker();
            var options = {
                theme: 'dark',
                clearBtn: true,
                is24hour: true,
            }
            mdtimepicker('.timepick', options);

        
            $('.dynamicForm').on('click', '.btn-plus', function(e) {
                e.preventDefault();

                const originalRow = $(this).closest('tr');

                // const copiedRow = originalRow.clone();
                // copiedRow.find('.btn-minus').remove();

                // originalRow.find('td:last').html(
                //     '<button class="btn btn-danger btn-minus"> <i class="fa fa-minus"></i> </button>');


                // copiedRow.find('input').val('');


                // copiedRow.find('select').prop('selectedIndex', 0);
                // copiedRow.find('input[type="checkbox"]').prop('checked', false);

                // copiedRow = '<tr>' + start_time + end_time + week_days + days + months + btn_plus + '</tr>';

                var className = generateRandomString(10);
                originalRow.after(generateRow(className));            
                $('.' + className + ' .multiselect').selectize(multiselect);
                mdtimepicker('.timepick', options);

                resetBtn()
                
            });


            $('.dynamicForm').on('click', '.btn-minus', function(e) {
                e.preventDefault();
                
                if ($(".dynamicForm tr").length > 2) {
                    const selectedRow = $(this).closest('tr');
                    selectedRow.remove();
                    resetBtn()


                    // if (selectedRow.find('.btn-plus').length > 0) {
                    //     const prevRow = selectedRow.prev();
                    //     console.log(prevRow)

                    //     prevRow.find('td:last').html(
                    //         '<button class="btn btn-danger btn-minus"> <i class="fa fa-minus"></i> </button><button class="btn btn-primary btn-plus"><i class="fa fa-plus"></i></button>'
                    //     );
                    // }

                    
                }

            });

           

            function generateRandomString(length) {

                var text = "";
                var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

                for (var i = 0; i < length; i++) {
                    text += possible.charAt(Math.floor(Math.random() * possible.length));
                }

                return text;
            }

            function generateRow(className, edit = 0){
                
                var row = '<tr class="'+ className +'"> <td> <input class="form-control timepick start_time" placeholder="Enter start time here..." name="schedule['+className+'][start_time]" type="text" readonly=""></td>';
                row += '<td> <input class="form-control timepick end_time" placeholder="Enter end time here..." name="schedule['+className+'][end_time]" type="text" readonly=""></td>';
                row += '<td> <select class="form-control multiselect week_days" multiple="multiple" name="schedule['+className+'][week_days][]"><option value="sat">Saterday</option><option value="sun">Sunday</option><option value="mon">Monday</option><option value="tue">Tuesday</option><option value="wed">Wednesday</option><option value="thu">Thursday</option><option value="fri">Friday</option></select></td>';
                row += '<td> <select class="form-control multiselect days" multiple="multiple" name="schedule['+className+'][days][]"><option value="01">01</option><option value="02">02</option><option value="03">03</option><option value="04">04</option><option value="05">05</option><option value="06">06</option><option value="07">07</option><option value="08">08</option><option value="09">09</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option></select></td>';
                row += '<td> <select class="form-control multiselect months" multiple="multiple" name="schedule['+className+'][months][]"><option value="jan">January</option><option value="feb">February</option><option value="mar">March</option><option value="apr">April</option><option value="may">May</option><option value="jun">June</option><option value="jul">July</option><option value="aug">August</option><option value="sep">September</option><option value="oct">October</option><option value="nov">November</option><option value="dec">December</option></select></td>';
                
                if(edit > 0){
                    row += '<td><button class="btn btn-danger btn-minus"> <i class="fa fa-minus"></i> </button></tr>';
                }

                else{
                    row += '<td> </button><button class="btn btn-primary btn-plus"><i class="fa fa-plus"></i></button></td></tr>';
                }
                

                return row;
            }


            function resetBtn(){
                var trs = $('#dynamicFormTable tr:not(:first-child):not(:last-child)');
                
                if(trs.length > 0){
                    trs.each((index, item) => {
                        $(item).find('td:last').html(
                            '<button class="btn btn-danger btn-minus"> <i class="fa fa-minus"></i> </button>'
                        );
                    })

                }

                $('#dynamicFormTable tr:last-child').find('td:last').html('<button class="btn btn-primary btn-plus"><i class="fa fa-plus"></i></button> <button class="btn btn-danger btn-minus"> <i class="fa fa-minus"></i> </button>')
            }

        });
    </script>
@endpush
