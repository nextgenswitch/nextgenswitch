<div class="row"><div class="col-sm-12">

    <table class="table table-striped ">
        <thead>
            <tr>
                <th><input type="checkbox" name="checkAll" id="checkAll"></th>
                <th class="sortable" sort-by="type">{{ __('Profile Type') }}</th>
                <th >{{ __('Input') }}</th>
                <th>{{ __('Output') }}</th>
                
            </tr>
        </thead>
        <tbody>
        @foreach($histories as $history)
          
            <tr>
                <td><input type="checkbox" name="ids[]" class="idRow" value="{{ $history->id }}"></td>
                <td>{{ $history->type ? 'STT' : 'TTS' }}</td>

                <td class="voice-preview">
                    @if($history->type )
						@php $src = Illuminate\Support\Facades\Storage::url(str_replace(storage_path('app/public/'), '', $history->input) ); @endphp
					
                        <span src="{{ $src }}" class="btn btn-outline-primary btn-sm play"><i class="fa fa-play"></i></span>
                        <span class="btn btn-outline-primary btn-sm stop d-none"><i class="fa fa-stop"></i></span>
                    @else
                        {{ $history->input }}
                    @endif
                </td>

                <td class="voice-preview">
                    @if($history->type )
                        {{ $history->output }}
                    @else
						@php $src = Illuminate\Support\Facades\Storage::url(str_replace(storage_path('app/public/'), '', $history->output) ); @endphp
					
                        <span src="{{ $src }}" class="btn btn-outline-primary btn-sm play"><i class="fa fa-play"></i></span>
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

{!! $histories->render() !!}
</div>

