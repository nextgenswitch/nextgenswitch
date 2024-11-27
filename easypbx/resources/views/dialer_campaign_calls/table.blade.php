  <div class="row"><div class="col-sm-12">

                <table class="table table-striped ">
                    <thead>
                        <tr>
                                     
                            <th class="sortable" sort-by="tel_no">{{ __('Tel No') }}</th>
                            <th class="sortable">{{ __('Agent') }}</th>
                            <th class="sortable" sort-by="status">{{ __('Status') }}</th>
                            <th>{{ __('Duration') }}</th>
                            <th>{{ __('date') }}</th>    
                            <th>{{ __('Form Data') }}</th>
                            <th>{{ __('Record') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($campaignCalls as $campaignCall)
                        <tr>
                            
                            <td>{{ $campaignCall->tel }}</td>
                            <td>{{ optional($campaignCall->call)->caller_id }}</td>
                            <td>{{ \App\Enums\CallStatusEnum::fromKey($campaignCall->status)->getText() }}</td>
                            <td>{{ duration_format($campaignCall->duration) }}</td>
                            <td>{{ date_time_format($campaignCall->created_at) }}</td>
                            <td>
                                @if($campaignCall->form_data)
                                <button form-data="{{ $campaignCall->form_data }}" class="badge badge-primary btn-show-form-data"> <i class="fa fa-eye"></i> show</button>
                                @endif
                            </td>
                            <td class="voice-preview">
                            @if(!empty($campaignCall->record_file))
                            <span class="btn btn-outline-primary btn-sm play" src="{{ url('storage/' . $campaignCall->record_file ) }}"><i class="fa fa-play"></i></span>
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
    
{!! $campaignCalls->render() !!}
</div>

