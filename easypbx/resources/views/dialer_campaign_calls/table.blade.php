  <div class="row"><div class="col-sm-12">

                <table class="table table-striped ">
                    <thead>
                        <tr>
                            <th>{{ __('date') }}</th>              
                            <th class="sortable" sort-by="tel_no">{{ __('Tel No') }}</th>
                            <th>{{ __('Retry') }}</th>
                            <th class="sortable" sort-by="status">{{ __('Status') }}</th>
                            <th>{{ __('Duration') }}</th>
                            
                            <th>{{ __('Form Data') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($campaignCalls as $campaignCall)
                        <tr>
                            <td>{{ $campaignCall->updated_at }}</td>
                            <td>{{ $campaignCall->tel }}</td>
                            <td>{{ $campaignCall->retry }}</td>
                            <td>{{ \App\Enums\CallStatusEnum::fromKey($campaignCall->status)->getText() }}</td>
                            <td>{{ $campaignCall->duration }}</td>
                            <td>
                                @if($campaignCall->form_data)
                                <button form-data="{{ $campaignCall->form_data }}" class="badge badge-primary btn-show-form-data"> <i class="fa fa-eye"></i> show</button>
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

