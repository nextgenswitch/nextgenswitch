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

            $("#date").flatpickr({
                mode: 'range',
                maxDate: new Date(),
                dateFormat: "Y-m-d H:i",
                enableTime: true,
                time_24hr: true,
                defaultDate: ['today', 'today'],
            });

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
            })

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
