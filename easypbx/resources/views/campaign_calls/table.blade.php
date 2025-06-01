  <div class="row"><div class="col-sm-12">

                <table class="table table-striped ">
                    <thead>
                        <tr>
                            
                           
                                                   
                            <th class="sortable" sort-by="tel">{{ __('Tel No') }}</th>
                            <th class="sortable" sort-by="status">{{ __('Status') }}</th>
                            <th>{{ __('Duration') }}</th>
                            <th class="sortable" sort-by="updated_at">{{ __('Last Try') }}</th>
                            <th class="sortable" sort-by="retry">{{ __('No of try') }}</th>
                            <th>{{ __('Error') }}</th>
                            

                            
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($campaignCalls as $campaignCall)
                        <tr>
                            <!-- <td>{{ $campaignCall->tel }}</td> -->
                            <td> @include('contacts.call_sms_popup', ['tel_no' => $campaignCall->tel]) </td>

                            <td>{{ ($campaignCall->status->value == 3)?"Successfull":$campaignCall->status->getText() }}</td>
                            <td>{{ duration_format($campaignCall->duration) }}</td>
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

