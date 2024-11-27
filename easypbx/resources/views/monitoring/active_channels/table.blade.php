<div class="row">
    <div class="col-sm-12">

        <table class="table table-striped ">
            <thead>
                <tr>

                    <th>{{ __('Sip User') }}</th>
                    <th>{{ __('Type') }}</th>
                    <th class="sortable" sort-by="location">{{ __('Location') }}</th>
                    <th class="sortable" sort-by="expire">{{ __('Expire') }}</th>
                    <th class="sortable" sort-by="ua">{{ __('User Agent') }}</th>
                    <th>{{ __('Date') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($channels as $channel)
                    <tr>
                        <td>{{ $channel->sipUser->username }}</td>
                        <td>{{ ($channel->sipUser->peer)?__("Peer"):__("User") }}</td>
                        <td>{{ $channel->location }}</td>
                        <td>{{ $channel->expire }}</td>
                        <td>{{ $channel->ua }}</td>
                        <td>{{ date_time_format($channel->created_at) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>
<div class="row">
    {!! $channels->render() !!}
</div>
