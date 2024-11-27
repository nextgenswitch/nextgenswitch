<div class="row">
    <div class="col-sm-12">

        <table class="table table-striped ">
            <thead>
                <tr>
                    <th class="sortable" sort-by="created_at">{{ __('Date/Time') }}</th>
                    <th class="sortable" sort-by="caller_id">{{ __('Caller ID') }}</th>
                    <th>{{ __('Feedback') }}</th>
                    <th>{{ __('Record') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($results as $result)
                    <tr>
                        <td>{{ date_time_format($result->created_at) }}</td>
                        <!-- <td>{{ $result->caller_id }}</td> -->
                        <td> @include('contacts.call_sms_popup', ['tel_no' => $result->caller_id]) </td>
                        <td>
                            @if(isset($keys[$result->pressed_key]))
                                {{ $keys[$result->pressed_key] }}
                            @elseif($result->pressed_key)
                                {{ "unknown({$result->pressed_key})" }}

                            @endif

                        </td>

                        @if(optional($result)->record_file)

                            <td class="voice-preview">
                                <span src="/storage/{{optional($result)->record_file }}" class="btn btn-outline-primary btn-sm play"><i class="fa fa-play"></i></span>

                                <span class="btn btn-outline-primary btn-sm stop d-none"><i
                                        class="fa fa-stop"></i></span>
                            </td>
                        @else
                            <td></td>
                        @endif
                 

                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>
<div class="row">
    {!! $results->render() !!}
</div>
