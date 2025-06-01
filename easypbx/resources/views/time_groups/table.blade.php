  <div class="row"><div class="col-sm-12">

                <table class="table table-striped ">
                    <thead>
                        <tr>
                            <th><input type="checkbox" name="checkAll" id="checkAll"></th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('TimeZone') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($timeGroups as $timeGroup)
                        <tr>
                            <td><input type="checkbox" name="ids[]" class="idRow" value="{{ $timeGroup->id }}"></td>
                            
                            <td>{{ $timeGroup->name }}</td>
                            <td>{{ $timeGroup->time_zone }}</td>

                            <td>
                                  
                                <div class="dropdown">
                                 <form method="POST" action="{!! route('time_groups.time_group.destroy', $timeGroup->id) !!}" accept-charset="UTF-8" class="deleteFrm">
                                   @csrf
                                   @method('DELETE')   
                                    <a href="#" data-toggle="dropdown"> <i data-feather="more-horizontal"> </i> </a>
                                    <ul class="dropdown-menu shadow-dropdown action-dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a title="Edit Time Group #{{ $timeGroup->id }}" class="dropdown-item" href="{{ route('time_groups.time_group.edit', $timeGroup->id ) }}">
                                            <i data-feather="edit"></i> {{ __('Edit') }}
                                        </a>
                                       </li>
                                        <li>
                                        <button type="submit" class="dropdown-item btn btn-link" title="Delete User" onclick="return confirm('{{ __('Click Ok to delete Time Group.') }}')">
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
    
{!! $timeGroups->render() !!}
</div>

