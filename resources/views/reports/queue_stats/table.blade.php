<div class="row g-6 mb-6 py-3">
    <div class="col-xl-5 col-sm-6 col-12">
        <div class="card shadow border-0">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 text-center">
                            <h6 class="text-muted text-sm">CALL ANALYTICS SUMMARY</h6>
                    </div>
                    <div class="col text-center">
                        <span class="font-bold text-muted text-sm d-block mb-2">ANSWERED</span>
                        <span class="h5 font-semibold mb-0">{{ $stats['total_ans_calls'] }}</span>
                    </div>
                    <div class="col text-center">
                        <span class="font-bold text-muted text-sm d-block mb-2">ABANDONED</span>
                        <span class="h5 font-semibold mb-0">{{ $stats['total_aban_calls'] }}</span>
                    </div>
                    <div class="col text-center">
                        <span class="font-bold text-muted text-sm d-block mb-2">TIMEOUT</span>
                        <span class="h5 font-semibold mb-0">{{ $stats['total_tout_calls'] }}</span>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 col-12">
        <div class="card shadow border-0">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 text-center">
                            <h6 class="text-muted text-sm">RESPONSE TIME</h6>
                    </div>
                    <div class="col text-center">
                        <span class="font-bold text-muted text-sm d-block mb-2">AVG</span>
                        <span class="h5 font-semibold mb-0">{{ duration_format($stats['avg_res_time']) }}</span>
                    </div>
                    <div class="col text-center">
                        <span class="font-bold text-muted text-sm d-block mb-2">MAX</span>
                        <span class="h5 font-semibold mb-0">{{ duration_format($stats['avg_res_time']) }}</span>
                    </div>
                    
                </div>
                
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-sm-6 col-12">
        <div class="card shadow border-0">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 text-center">
                        <h6 class="text-muted text-sm">CALL DURATION</h6>
                    </div>
                    <div class="col text-center">
                        <span class="font-bold text-muted text-sm d-block mb-2">AVG</span>
                        <span class="h5 font-semibold mb-0">{{ duration_format($stats['avg_duration']) }}</span>
                    </div>

                    <div class="col text-center">
                        <span class="font-bold text-muted text-sm d-block mb-2">LONGEST</span>
                        <span class="h5 font-semibold mb-0">{{ duration_format($stats['longest_duration']) }}</span>
                    </div>

                    <div class="col text-center">
                        <span class="font-bold text-muted text-sm d-block mb-2">TOTAL</span>
                        <span class="h5 font-semibold mb-0">{{ duration_format($stats['total_duration']) }}</span>
                    </div>
                    
                </div>
                
            </div>
        </div>
    </div>

</div>

<div class="row">
    <div class="col-sm-12">

        <table class="table table-bordered" data-resizable="true">
            <thead>
    
                <tr>
                    <th>Queue Name</th>
                    <th>Active Agents</th>
                    <th>Pending Calls</th>
                    <th>Answered Calls</th>
                    <th>Abandoned Calls</th>
                    <th>Timeout Calls</th>
                    <th>Total Calls</th>
                </tr>
                
            </thead>
            <tbody>
                @foreach($callQueues as $callQueue)
                <tr>
                    <td>
                        <a href="{{ route('monitoring.queue.call')  }}?filter=call_queue_id:{{optional($callQueue)->id}}">{{ optional($callQueue)->name }}</a>
                        
                    </td>
                    <td>{{ optional($callQueue)->active_agents }}</td>
                    <td>{{ optional($callQueue)->pending_calls }}</td>
                    <td>{{ optional($callQueue)->ans_calls }}</td>
                    <td>{{ optional($callQueue)->abandoned_calls }}</td>
                    <td>{{ optional($callQueue)->timeout_calls }}</td>
                    <td>{{ optional($callQueue)->total_calls }}</td>
                    
                   
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
