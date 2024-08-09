  <div class="row"><div class="col-sm-12">

                <table class="table table-striped ">
                    <thead>
                        <tr>
                            
                           
                                                   
                            <th class="sortable" sort-by="tel_no">{{ __('Tel No') }}</th>
                            <th class="sortable" sort-by="status">{{ __('Status') }}</th>
                            <th>{{ __('Duration') }}</th>
                            <th>{{ __('Last Try') }}</th>
                            <th>{{ __('No of try') }}</th>
                            <th>{{ __('Error') }}</th>
                            

                            
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($campaignCalls as $campaignCall)
                        <tr>
                            
                            
                            <td>{{ $campaignCall->tel }}</td>
                            <td>{{ ($campaignCall->call)?$campaignCall->call->status->getText():"Failed" }}</td>
                            <td>{{ ($campaignCall->call)?$campaignCall->call->duration:"" }}</td>
                            <td>{{ $campaignCall->updated_at }}</td>
                            <td>{{ $campaignCall->retry }}</td>
                            <td>{{ isset(config('enums.error_codes')[$campaignCall->error_code]) ? config('enums.error_codes')[$campaignCall->error_code] : $campaignCall->error_code }}</td>
                            
                           
                           
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                </div>            
</div>

<div class="row">
    
{!! $campaignCalls->render() !!}
</div>

