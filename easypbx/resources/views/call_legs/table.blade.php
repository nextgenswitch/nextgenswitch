  <div class="row"><div class="col-sm-12">

                <table class="table table-striped ">
                    <thead>
                        <tr>
                            <th><input type="checkbox" name="checkAll" id="checkAll"></th>
                            <th class="sortable" sort-by="id">{{ __('ID') }}</th>
                                                        <th>{{ __('Call') }}</th>
                            <th>{{ __('Channel') }}</th>
                            <th>{{ __('Sip User') }}</th>
                            <th>{{ __('Call Status') }}</th>
                            <th>{{ __('Connect Time') }}</th>
                            <th>{{ __('Ringing Time') }}</th>
                            <th>{{ __('Establish Time') }}</th>
                            <th>{{ __('Disconnect Time') }}</th>
                            <th>{{ __('Duration') }}</th>

                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($callLegs as $callLeg)
                        <tr>
                            <td><input type="checkbox" name="ids[]" class="idRow" value="{{ $callLeg->id }}"></td>
                            <td >{{ $callLeg->id }}
                            </td>
                                                        <td>{{ optional($callLeg->call)->channel }}</td>
                            <td>{{ $callLeg->channel }}</td>
                            <td>{{ optional($callLeg->sipUser)->id }}</td>
                            <td>{{ $callLeg->call_status }}</td>
                            <td>{{ $callLeg->connect_time }}</td>
                            <td>{{ $callLeg->ringing_time }}</td>
                            <td>{{ $callLeg->establish_time }}</td>
                            <td>{{ $callLeg->disconnect_time }}</td>
                            <td>{{ $callLeg->duration }}</td>

                            <td>
                                  
                                <div class="dropdown">
                                 <form method="POST" action="{!! route('call_legs.call_leg.destroy', $callLeg->id) !!}" accept-charset="UTF-8" class="deleteFrm">
                                   @csrf
                                   @method('DELETE')   
                                    <a href="#" data-toggle="dropdown"> <i data-feather="more-horizontal"> </i> </a>
                                    <ul class="dropdown-menu shadow-dropdown action-dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a title="Edit Call Leg #{{ $callLeg->id }}" class="dropdown-item btnForm" href="{{ route('call_legs.call_leg.edit', $callLeg->id ) }}">
                                            <i data-feather="edit"></i> {{ __('Edit') }}
                                        </a>
                                       </li>
                                        <li>
                                        <button type="submit" class="dropdown-item btn btn-link" title="Delete User" onclick="return confirm('{{ __('Click Ok to delete Call Leg.') }}')">
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
    
{!! $callLegs->render() !!}
</div>

