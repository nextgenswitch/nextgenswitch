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
                                <a href="{{ route('broadcasts.broadcast.edit', $campaign->id ) }}">
                                           {{ $campaign->name }} 
                                </a>
                            </td>

                        
                            <td>        
                                <span class="badge badge-pill badge-light"><a href="{{ route('broadcasts.broadcast.stats', $campaign->id ) }}"> {{ config('enums.campaign_status')[$campaign->status] }} </a></span>    
                                
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
                                       
                                        <li><a class="dropdown-item" href="{{ route('broadcasts.broadcast.edit', $campaign->id ) }}">
                                            <i data-feather="edit"></i> {{ __('Edit/View') }}
                                        </a>
                                       </li>

                                     

                                  
                                       <a title="{{ __('Start/Stop/Stats')}} " class="dropdown-item" href="{{ route('broadcasts.broadcast.stats', $campaign->id ) }}">
                                            <i data-feather="activity"></i> {{ __('Start/Stop/Stats') }}
                                        </a>
                                        <a title="{{ __('Call Histories')}} #{{ $campaign->id }}" class="dropdown-item" href="{{ route('broadcast_calls.broadcast_call.index', ['id'=>$campaign->id]) }}">
                                            <i data-feather="phone-call"></i> {{ __('History') }}
                                        </a>
                                       
                                    
                                       @if($campaign->status != 1 )
                                        <li>
                                        <button type="submit" class="dropdown-item btn btn-link" title="{{ __('Delete Entity') }}" onclick="return confirm('Click Ok to delete Broadcast.')">
                                        <i data-feather="trash"></i> {{ __('Delete') }}
                                        </button>
                                        </li>
                                        @endif 
                                        <li><a class="dropdown-item" href="{{ route('broadcasts.broadcast.clone', $campaign->id ) }}">
                                            <i data-feather="copy"></i> {{ __('Clone') }}
                                        </a>
                                       </li>

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

