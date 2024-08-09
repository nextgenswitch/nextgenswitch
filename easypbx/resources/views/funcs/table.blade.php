  <div class="row"><div class="col-sm-12">

                <table class="table table-striped ">
                    <thead>
                        <tr>
                            <th><input type="checkbox" name="checkAll" id="checkAll"></th>
                            <th class="sortable" sort-by="id">{{ __('ID') }}</th>
                                                        <th>{{ __('Func') }}</th>
                            <th>{{ __('Func Type') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Organization') }}</th>

                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($funcs as $func)
                        <tr>
                            <td><input type="checkbox" name="ids[]" class="idRow" value="{{ $func->id }}"></td>
                            <td >{{ $func->id }}
                            </td>
                                                        <td>{{ $func->func }}</td>
                            <td>{{ $func->func_type }}</td>
                            <td>{{ $func->name }}</td>
                            <td>{{ optional($func->organization)->id }}</td>

                            <td>
                                  
                                <div class="dropdown">
                                 <form method="POST" action="{!! route('funcs.func.destroy', $func->id) !!}" accept-charset="UTF-8" class="deleteFrm">
                                   @csrf
                                   @method('DELETE')   
                                    <a href="#" data-toggle="dropdown"> <i data-feather="more-horizontal"> </i> </a>
                                    <ul class="dropdown-menu shadow-dropdown action-dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a title="Edit Func #{{ $func->id }}" class="dropdown-item btnForm" href="{{ route('funcs.func.edit', $func->id ) }}">
                                            <i data-feather="edit"></i> {{ __('Edit') }}
                                        </a>
                                       </li>
                                        <li>
                                        <button type="submit" class="dropdown-item btn btn-link" title="Delete User" onclick="return confirm('{{ __('Click Ok to delete Func.') }}')">
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
    
{!! $funcs->render() !!}
</div>

