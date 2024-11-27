<form action="" id="searchForm">

    <div class="input-group">
        <input type="text" name="date" id="date" value="{{ app('request')->input('date') }}"
            class="form-control app-search__input form-control-sm" placeholder="{{ __('date') }}">


        <div class="input-group-append">
            <button class="btn btn-sm btn-secondary" type="submit">
                <i class="fa fa-search"></i>
            </button>
        </div>

    </div>

</form>

@push('script')
    <script>
        $(document).ready(function() {
            $crud = $('#crud_contents').crud();

            const date = new Date();
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are 0-based
            const year = date.getFullYear();
            const hours = String(date.getHours()).padStart(2, '0');
            const minutes = String(date.getMinutes()).padStart(2, '0');


            default_date_time = `${year}-${month}-${day} 00:00 to ${year}-${month}-${day} ${hours}:${minutes}`;

            console.log(default_date_time);
            

            // $("#date").flatpickr({
            //     mode: 'range',
            //     maxDate: new Date(),
            //     dateFormat: "Y-m-d H:i",
            //     enableTime: true,
            //     time_24hr: true,
            //     defaultDate: ['today', 'today'],
            // });


            $("#date").flatpickr({
                mode: 'range',
                // maxDate: 'today',
                dateFormat: "Y-m-d H:i",
                enableTime: true,
                time_24hr: true,
                defaultDate: default_date_time,
            });

            // 2024-11-11 00:00 to 2024-11-11 23:59

            $("#searchForm").submit((e) => {
                e.preventDefault();
                var q = '';

                var date = $("#date").val();


                if (date !== undefined && date.length > 0) {
                    q += 'date:' + date + ',';
                }


                console.log(q)

                $crud.setUrlParam('q', q);
                $crud.reload_data()

                console.log('submitted');
            });

            $("#queue_name").change(function(event){
                event.preventDefault();

                var name = $(this).val();
                
                var filter = '';

                if (name !== undefined && name.length > 0) {
                    filter += 'name:' + name ;
                }
                

                $crud.setUrlParam('filter', filter);
                $crud.reload_data()
            });

            var q = '{{ app('request')->input('q') }}';

            if (q != '') {
                var qs = q.split(',');
                qs.forEach((item) => {
                    console.log(item)
                    qarr = item.split(':')
                    $("#" + qarr[0]).val(qarr[1]);
                })
            }


        })
    </script>
@endpush
