
<div class="row">
    <div class="col-sm-12">

        <table class="table table-striped   " data-resizable="true">
            <thead>
                <tr>
                    <th class="sortable" sort-by="created_at">{{ __('Sent Date') }}</th>
                    <th class="sortable" sort-by="from">{{ __('From') }}</th>
                    <th class="sortable" sort-by="to">{{ __('To') }}</th>
                    <th>{{ __('Body') }}</th>
                    <th class="sortable" sort-by="status">{{ __('Status') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($histories as $sms)
                    <tr> 
                        <td>{{ date_time_format($sms->created_at) }}</td>
                        <td>{{ $sms->from }}</td>
                        <td>{{ $sms->to }}</td>
                        <td>{{ $sms->body }}</td>
                        <td> {{ $sms->status->getText() }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>
<div class="row">
    {!! $histories->render() !!}
</div>
@push('script')
<script>
 $(function(){
  $(".table").resizableColumns({
    store: window.store
  });
});
</script>
@endpush
