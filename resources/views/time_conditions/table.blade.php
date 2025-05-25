  <div class="row"><div class="col-sm-12">

                <table class="table table-striped ">
                    <thead>
                        <tr>
                            <th><input type="checkbox" name="checkAll" id="checkAll"></th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Time Group') }}</th>

                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($timeConditions as $timeCondition)
                        <tr>
                            <td><input type="checkbox" name="ids[]" class="idRow" value="{{ $timeCondition->id }}"></td>
                                                        
                            <td>{{ $timeCondition->name }}</td>
                            <td>{{ optional($timeCondition->timeGroup)->name }}</td>
                            <td>
                                  
                                <div class="dropdown">
                                 <form method="POST" action="{!! route('time_conditions.time_condition.destroy', $timeCondition->id) !!}" accept-charset="UTF-8" class="deleteFrm">
                                   @csrf
                                   @method('DELETE')   
                                    <a href="#" data-toggle="dropdown"> <i data-feather="more-horizontal"> </i> </a>
                                    <ul class="dropdown-menu shadow-dropdown action-dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a title="Edit Time Condition #{{ $timeCondition->id }}" class="dropdown-item" href="{{ route('time_conditions.time_condition.edit', $timeCondition->id ) }}">
                                            <i data-feather="edit"></i> {{ __('Edit') }}
                                        </a>
                                       </li>
                                        <li>
                                        <button type="submit" class="dropdown-item btn btn-link" title="Delete User" onclick="return confirm('{{ __('Click Ok to delete Time Condition.') }}')">
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
    
{!! $timeConditions->render() !!}
</div>

