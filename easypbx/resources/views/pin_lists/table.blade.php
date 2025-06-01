  <div class="row"><div class="col-sm-12">

                <table class="table table-striped ">
                    <thead>
                        <tr>
                            <th><input type="checkbox" name="checkAll" id="checkAll"></th>
                        
                            <th class="sortable" sort-by="name">{{ __('Name') }}</th>
                            <th class="sortable" sort-by="pin_list">{{ __('Pins') }}</th>

                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($pinLists as $pinList)
                        <tr>
                            <td><input type="checkbox" name="ids[]" class="idRow" value="{{ $pinList->id }}"></td>
                            
                                
                            <td>{{ $pinList->name }}</td>
                            <td >{{ $pinList->pin_list }}</td>

                            <td>  
                                <div class="dropdown">
                                 <form method="POST" action="{!! route('pin_lists.pin_list.destroy', $pinList->id) !!}" accept-charset="UTF-8" class="deleteFrm">
                                   @csrf
                                   @method('DELETE')   
                                    <a href="#" data-toggle="dropdown"> <i data-feather="more-horizontal"> </i> </a>
                                    <ul class="dropdown-menu shadow-dropdown action-dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a title="Edit Pin List #{{ $pinList->id }}" class="dropdown-item btnForm" href="{{ route('pin_lists.pin_list.edit', $pinList->id ) }}">
                                            <i data-feather="edit"></i> {{ __('Edit') }}
                                        </a>
                                       </li>
                                        <li>
                                        <button type="submit" class="dropdown-item btn btn-link" title="Delete User" onclick="return confirm('{{ __('Click Ok to delete Pin List.') }}')">
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
    
{!! $pinLists->render() !!}
</div>

