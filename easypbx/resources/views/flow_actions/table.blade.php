  <div class="row"><div class="col-sm-12">

                <table class="table table-striped ">
                    <thead>
                        <tr>
                            <th><input type="checkbox" name="checkAll" id="checkAll"></th>
                            <th>{{ __('Title') }}</th>
                            <th class="sortable" sort-by="action_type">{{ __('Action Type') }}</th>
                            <th>{{ __('Action Value') }}</th>

                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($flowActions as $flowAction)
                        <tr>
                            <td><input type="checkbox" name="ids[]" class="idRow" value="{{ $flowAction->id }}"></td>
                            
                            <td>{{ $flowAction->title }}</td>
                            <td>{{ config('enums.flow_action_type')[$flowAction->action_type] }}</td>
                            <td>{{ $flowAction->action_value }}</td>
                            

                            <td>
                                  
                                <div class="dropdown">
                                 <form method="POST" action="{!! route('flow_actions.flow_action.destroy', $flowAction->id) !!}" accept-charset="UTF-8" class="deleteFrm">
                                   @csrf
                                   @method('DELETE')   
                                    <a href="#" data-toggle="dropdown"> <i data-feather="more-horizontal"> </i> </a>
                                    <ul class="dropdown-menu shadow-dropdown action-dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a title="Edit Flow Action #{{ $flowAction->id }}" class="dropdown-item btnForm" href="{{ route('flow_actions.flow_action.edit', $flowAction->id ) }}">
                                            <i data-feather="edit"></i> {{ __('Edit') }}
                                        </a>
                                       </li>
                                        <li>
                                        <button type="submit" class="dropdown-item btn btn-link" title="Delete User" onclick="return confirm('{{ __('Click Ok to delete Flow Action.') }}')">
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
    
{!! $flowActions->render() !!}
</div>

