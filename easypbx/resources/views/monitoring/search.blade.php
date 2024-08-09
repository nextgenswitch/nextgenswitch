<form action="" id="searchForm">
    <div class="input-group">
        <div class="input-group-prepend">
            {!! Form::select('crud_per_page', config('enums.pagination_count'), app('request')->input('per_page'), [
                'id' => 'crud_per_page',
                'class' => 'form-control form-control-sm ',
                'style' => 'width:90px',
            ]) !!}
        </div>
        @if ($type == 'callQueue')
            <input type="search" name="name" id="name" value="{{ app('request')->input('name') }}"
                class="form-control app-search__input form-control-sm" placeholder="{{ __('Queue name') }}">
        @endif

        @if ($type == 'callHistory' || $type == 'trunkLog')
            <input type="search" name="from" id="from" value="{{ app('request')->input('from') }}"
                class="form-control app-search__input form-control-sm" placeholder="From">

            <input type="search" name="to" id="filterto" value="{{ app('request')->input('to') }}"
                class="form-control app-search__input form-control-sm" placeholder="To">
        @endif

        @if ($type == 'callHistory')
            <input type="search" name="received_by" id="received_by" value="{{ app('request')->input('received_by') }}"
                class="form-control app-search__input form-control-sm" placeholder="Received By">
        @endif

        @if($type == 'trunkLog')

        {!! Form::select('sip_user_id', $trunks, app('request')->input('sip_user_id'), [
                'id' => 'sip_user_id',
                'placeholder' => 'Select Trunk',
                'class' => 'form-control form-control-sm ',
            ]) !!}

        @endif

        @if ($type != 'callHistory' && $type != 'trunkLog')
            <input type="search" name="caller_id" id="caller_id" value="{{ app('request')->input('caller_id') }}"
                class="form-control app-search__input form-control-sm"
                placeholder="{{ $type == 'callLog' ? __('Caller ID / Channel') : __('Caller ID') }}">
        @endif

        @if ($type == 'callQueue' || $type == 'callLog')
            <input type="search" name="destination" id="destination" value="{{ app('request')->input('destination') }}"
                class="form-control app-search__input form-control-sm"
                placeholder="{{ $type == 'callQueue' ? __('Agent') : __('Destination') }}">
        @endif

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
                dateFormat: "Y-m-d",
            });

            $("#searchForm").submit((e) => {
                e.preventDefault();
                var q = '';



                var caller_id = $("#caller_id").val();
                var from = $("#from").val();
                var to = $("#filterto").val();
                var date = $("#date").val();
                var destination = $("#destination").val();
                var received_by = $("#received_by").val();
                var sip_user_id = $("#sip_user_id").val();


                if (from !== undefined && from.length > 0) {
                    q += 'from:' + from + ',';
                }

                if (to !== undefined && to.length > 0) {
                    q += 'to:' + to + ',';
                }

                if (caller_id !== undefined && caller_id.length > 0)
                    q += 'caller_id:' + caller_id + ',';

                if (destination !== undefined && destination.length > 0)
                    q += 'destination:' + destination + ',';

                if (received_by !== undefined && received_by.length > 0)
                    q += 'received_by:' + received_by + ',';
                
                    if (sip_user_id !== undefined && sip_user_id.length > 0)
                    q += 'sip_user_id:' + sip_user_id + ',';

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
