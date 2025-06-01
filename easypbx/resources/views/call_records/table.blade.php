<div class="row">
    <div class="col-sm-12">

        <table class="table table-striped ">
            <thead>
                <tr>
                    <th>{{ __('Dial No') }}</th>
                    <th>{{ __('Record') }}</th>
                    
                    
                </tr>
            </thead>
            <tbody>
                
                @foreach ($callRecords as $record)
                    
                    <tr>
                        <td>{{ $record->dialCall->destination }}</td>

                        <td class="voice-preview">
                            
                            @if($record->record_path)
                            <span class="btn btn-outline-primary btn-sm play" src="{{ url('storage/'. $record->record_path) }}"><i class="fa fa-play"></i></span>
                            <span class="btn btn-outline-primary btn-sm stop d-none"><i class="fa fa-stop"></i></span>
                            @endif

                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>
<div class="row">
    {!! $callRecords->render() !!}
</div>
