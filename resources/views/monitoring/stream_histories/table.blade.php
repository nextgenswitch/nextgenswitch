<div class="row">
    <div class="col-sm-12">

        <table class="table table-striped ">
            <thead>
                <tr>
                    <!-- <th>{{ __('Voice Record Profile') }}</th> -->
                    <th class='sortable' sort-by="caller_id">{{ __('Caller ID') }}</th>
                    <th>{{ __('Voice') }}</th>
                    <th class="sortable" sort-by="expire">{{ __('Duration') }}</th>
                    <th>{{ __("Stream") }}</th>
                    <th>{{ __('Date') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($streamHistories as $history)
                    <tr>

                        <td>{{ $history->caller_id }}</td>


                        <td class="voice-preview">
                            @if ($history->record_file)
                                <span class="btn btn-outline-primary btn-sm play"
                                    src="{{ url('storage/' . $history->record_file) }}"><i class="fa fa-play"></i></span>
                                <span class="btn btn-outline-primary btn-sm stop d-none"><i
                                        class="fa fa-stop"></i></span>
                            @endif
                        </td>
                        <td>{{ duration_format( $history->duration ) }}</td>

                        <td>
                            @if (strlen($history->transcript) > 100)
                                {{ Str::limit($history->transcript, 100) }}
                                <a href="#" class="see-more btn btn-sm btn-primary"
                                    data-transcript="{{ e($mail->transcript) }}">See
                                    more</a>
                            @else
                                {{ optional($history->stream)->name }}
                            @endif
                        </td>
                        <td>{{ date_time_format($history->created_at) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>
<div class="row">
    {!! $streamHistories->render() !!}
</div>


