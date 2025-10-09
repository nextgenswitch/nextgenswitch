<form action="" id="searchForm">
    <div class="input-group">
        <div class="input-group-prepend">
            {!! Form::select('crud_per_page', config('enums.pagination_count'), app('request')->input('per_page'), [
                'id' => 'crud_per_page',
                'class' => 'form-control form-control-sm ',
                'style' => 'width:90px',
            ]) !!}
        </div>
        @if ($type == 'voiceRecord')
            {!! Form::select('filter_group',$voiceRecordProfiles, null, ['placeholder'=>'Select any voice record profile','id'=>"filter_group",'class' => 'form-control form-control-sm ']) !!}
        @endif

        @if ($type == 'streamHistory')
            {!! Form::select('filter_group',$streamList, null, ['placeholder'=>'Select any AI stream','id'=>"filter_group",'class' => 'form-control form-control-sm ']) !!}
        @endif

        @if ($type == 'callQueue')
            <!-- <input type="search" name="name" id="que_name" value="{{ app('request')->input('name') }}"
                class="form-control app-search__input form-control-sm" placeholder="{{ __('Queue name') }}"> -->
                
                {!! Form::select('call_queue_id', $queueList, app('request')->input('call_queue_id'), [
                    'id' => 'call_queue_id',
                    'class' => 'form-control form-control-sm ',
                ]) !!}

        @endif

        @if ($type == 'callHistory' || $type == 'trunkLog')
            <input type="search" name="from" id="from" value="{{ app('request')->input('from') }}"
                class="form-control app-search__input form-control-sm" placeholder="{{ __('From') }}">

            <input type="search" name="to" id="filterto" value="{{ app('request')->input('to') }}"
                class="form-control app-search__input form-control-sm" placeholder="{{ __('To') }}">
        @endif

        @if ($type == 'callHistory')
            <input type="search" name="received_by" id="received_by" value="{{ app('request')->input('received_by') }}"
                class="form-control app-search__input form-control-sm" placeholder="{{ __('Received By') }}">
        @endif

        @if($type == 'trunkLog')

        {!! Form::select('sip_user_id', $trunks, app('request')->input('sip_user_id'), [
                'id' => 'sip_user_id',
                'placeholder' => __('Select Trunk'),
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

            <select  id="callStatus"  class="form-control app-search__input form-control-sm" >
                <option value="">{{ __("Call Status") }}</option>
                @foreach ($statuses as $k=>$status)
                <option value="{{ $k }}">{{ $status }}</option>
                @endforeach
            </select>

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



                var name = $("#que_name").val();
                var caller_id = $("#caller_id").val();
                var from = $("#from").val();
                var to = $("#filterto").val();
                var date = $("#date").val();
                var destination = $("#destination").val();
                var received_by = $("#received_by").val();
                var sip_user_id = $("#sip_user_id").val();

                var callStatus = $("#callStatus").val();


                if (name !== undefined && name.length > 0) {
                    q += 'name:' + name + ',';
                }

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

                if (callStatus !== undefined && callStatus.length > 0) {
                    $crud.setUrlParam('filter', 'status:' + callStatus);
                }


                $crud.reload_data()

                console.log('submitted');
            })


            $("#call_queue_id").change(function(e){
                e.preventDefault();
                var call_queue_id = $(this).val();
                
                if(call_queue_id.length > 0)
                    $crud.setUrlParam('filter', 'call_queue_id:' + call_queue_id);
                

                $crud.reload_data()
            })
            
            var filter = '{{ app('request')->input('filter') }}';

            if(filter != ''){
                filterArr = filter.split(':')
                $("#" + filterArr[0]).val(filterArr[1]);    
            }

            var q = '{{ app('request')->input('q') }}';

            if (q != '') {
                var qs = q.split(',');
                qs.forEach((item) => {
                    console.log(item)
                    qarr = item.split(':')
                    if(qarr[0] == 'name'){
                        $("#que_name").val(qarr[1]);
                    }
                    else{
                        $("#" + qarr[0]).val(qarr[1]);
                    }
                    
                })
            }
        })
    </script>
@endpush
