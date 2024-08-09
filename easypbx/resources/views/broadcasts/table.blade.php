  <div class="row"><div class="col-sm-12">

                <table class="table table-striped ">
                    <thead>
                        <tr>
                            <th><input type="checkbox" name="checkAll" id="checkAll"></th>
                            
                                                     
                            <th class="sortable" sort-by="name">{{ __('Name')}}</th>
                            
                            <th>{{ __('Status')}}</th>
                          
                            <th>{{ __('Max try') }}</th>
                            <th>{{ __('Call limit') }}</th>
                            <th>{{ __('Start at')}}</th>
                            <th>{{ __('End at')}}</th>

                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($campaigns as $campaign)
                        <tr>
                            <td><input type="checkbox" name="ids[]" class="idRow" value="{{ $campaign->id }}"></td>
                            
                        
                            <td>
                                <a href="{{ $campaign->is_sms  ?  route('campaign_sms.campaign_sms.index', $campaign->id) :  route('campaign_calls.campaign_call.index', ['id'=>$campaign->id]) }}">
                                           {{ $campaign->name }} 
                                </a>
                            </td>

                        
                            <td>        
                                <span class="badge badge-pill badge-light"> {{ config('enums.campaign_status')[$campaign->status] }} </span>    
                                
                            </td>
                            
                            <td>{{ $campaign->max_retry }}</td>
                            <td>{{ $campaign->call_limit }}</td>
                            <td>{{ $campaign->start_at }}</td>
                            <td>{{ $campaign->end_at }}</td>
                            
                   
  
                            <td>
                         
                                <div class="dropdown">
                                 <form method="POST" action="{!! route('broadcasts.broadcast.destroy', $campaign->id) !!}" accept-charset="UTF-8" class="deleteFrm">
                                   @csrf
                                   @method('DELETE')   
                                    <a href="#" data-toggle="dropdown"> <i data-feather="more-horizontal"> </i> </a>
                                    <ul class="dropdown-menu shadow-dropdown action-dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        @if($campaign->status != 3)
                                        <li><a class="dropdown-item" href="{{ route('broadcasts.broadcast.edit', $campaign->id ) }}">
                                            <i data-feather="edit"></i> {{ __('Edit') }}
                                        </a>
                                       </li>

                                       @endif

                                       @if($campaign->status == 1)
                                       <li><a title="{{ __('Stop Broadcast')}} #{{ $campaign->id }}" class="dropdown-item btnStatus" href="{{ route('broadcasts.broadcast.updateField',$campaign->id) }}" data-toggle="tooltip" data-placement="top" title="{{ __('Stop Broadcast')}}" data-status="2">
                                            <i data-feather="stop-circle"></i> {{ __('Stop') }}
                                        </a>
                                       </li>
                                       @endif

                                       @if($campaign->is_sms)   
                                        <li> 
                                            <a title="{{ __('SMS Histories')}} #{{ $campaign->id }}" class="dropdown-item" href="{{ route('campaign_sms.campaign_sms.index', $campaign->id) }}">
                                                <i data-feather="message-circle"></i> {{ __('History') }}
                                            </a>
                                        </li>
                                       @else
                                        <a title="{{ __('Call Histories')}} #{{ $campaign->id }}" class="dropdown-item" href="{{ route('campaign_calls.campaign_call.index', ['id'=>$campaign->id]) }}">
                                            <i data-feather="phone-call"></i> {{ __('History') }}
                                        </a>
                                       @endif
                                       
                                    
                                       @if($campaign->status != 1 )
                                        <li>
                                        <button type="submit" class="dropdown-item btn btn-link" title="{{ __('Delete Entity') }}" onclick="return confirm('Click Ok to delete Broadcast.')">
                                        <i data-feather="trash"></i> {{ __('Delete') }}
                                        </button>
                                        </li>
                                        @endif 
                                  </ul>
                                </form>
                                </div>
                           

                            </td>
                           
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                </div>            
</div>

<div class="row">
    
{!! $campaigns->render() !!}
</div>

