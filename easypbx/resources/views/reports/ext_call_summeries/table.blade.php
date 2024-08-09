
<div class="row">
    <div class="col-sm-12">

        <table class="table table-bordered" data-resizable="true">
            <thead>
                <tr>
                    <th rowspan="2">Extension Name</th>
                    <th colspan="3">Incoming</th>
                    <th colspan="3">Outgoing</th>
                </tr>
                <tr>
                    <th>Duration</th>
                    <th>Success</th>
                    <th>Failed</th>
                    <th>Duration</th>
                    <th>Success</th>
                    <th>Failed</th>
                </tr>
                
            </thead>
            <tbody>
                
                @php
                    $records = count($inCalls) > count($outCalls) ? $inCalls : $outCalls;
                @endphp

                @foreach ($records as $key => $record)
                @php
                    
                    $extension = isset($record->sipUser->extension) ? $record->sipUser->extension : null;
                @endphp
                <tr>
                    <td>{{ optional($extension)->name }}</td>
                    
                    <td>{{ isset($inCalls[$key]) ? $inCalls[$key]->human_readable_duration : '' }}</td>
                    <td>{{ isset($inCalls[$key]) ? $inCalls[$key]->success : '' }}</td>
                    <td>{{ isset($inCalls[$key]) ? $inCalls[$key]->failed : '' }}</td>


                    <td>{{ isset($outCalls[$key]) ? $outCalls[$key]->human_readable_duration : '' }}</td>
                    <td>{{ isset($outCalls[$key]) ? $outCalls[$key]->success : '' }}</td>
                    <td>{{ isset($outCalls[$key]) ? $outCalls[$key]->failed : '' }}</td>
                </tr>

                @endforeach
            </tbody>
        </table>

    </div>
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
