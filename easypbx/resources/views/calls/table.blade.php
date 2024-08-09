  <div class="row"><div class="col-sm-12">

                <table class="table table-striped ">
                    <thead>
                        <tr>
                            <th><input type="checkbox" name="checkAll" id="checkAll"></th>
                            <th class="sortable" sort-by="id">{{ __('ID') }}</th>
                                                        <th>{{ __('Organization') }}</th>
                            <th>{{ __('Channel') }}</th>
                            <th>{{ __('Sip User') }}</th>
                            <th>{{ __('Call Status') }}</th>
                            <th>{{ __('Connect Time') }}</th>
                            <th>{{ __('Ringing Time') }}</th>
                            <th>{{ __('Establish Time') }}</th>
                            <th>{{ __('Disconnect Time') }}</th>
                            <th>{{ __('Duration') }}</th>
                            <th>{{ __('User Agent') }}</th>
                            <th>{{ __('Uas') }}</th>

                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($calls as $call)
                        <tr>
                            <td><input type="checkbox" name="ids[]" class="idRow" value="{{ $call->id }}"></td>
                            <td >{{ $call->id }}
                            </td>
                                                        <td>{{ optional($call->organization)->name }}</td>
                            <td>{{ $call->channel }}</td>
                            <td>{{ optional($call->sipUser)->id }}</td>
                            <td>{{ $call->call_status }}</td>
                            <td>{{ $call->connect_time }}</td>
                            <td>{{ $call->ringing_time }}</td>
                            <td>{{ $call->establish_time }}</td>
                            <td>{{ $call->disconnect_time }}</td>
                            <td>{{ $call->duration }}</td>
                            <td>{{ $call->user_agent }}</td>
                            <td>{{ ($call->uas) ? 'Yes' : 'No' }}</td>

                            <td>
                                  
                                <div class="dropdown">
                                 <form method="POST" action="{!! route('calls.call.destroy', $call->id) !!}" accept-charset="UTF-8" class="deleteFrm">
                                   @csrf
                                   @method('DELETE')   
                                    <a href="#" data-toggle="dropdown"> <i data-feather="more-horizontal"> </i> </a>
                                    <ul class="dropdown-menu shadow-dropdown action-dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a title="Edit Call #{{ $call->id }}" class="dropdown-item btnForm" href="{{ route('calls.call.edit', $call->id ) }}">
                                            <i data-feather="edit"></i> {{ __('Edit') }}
                                        </a>
                                       </li>
                                        <li>
                                        <button type="submit" class="dropdown-item btn btn-link" title="Delete User" onclick="return confirm('{{ __('Click Ok to delete Call.') }}')">
                                        <i data-feather="trash"></i> {{ __('Delete') }}
                                        </button>
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
    
{!! $calls->render() !!}
</div>

