  <div class="row"><div class="col-sm-12">

                <table class="table table-striped ">
                    <thead>
                        <tr>
                            <th><input type="checkbox" name="checkAll" id="checkAll"></th>
                            <th class="sortable" sort-by="name">{{ __('Name') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($customForms as $customForm)
                        <tr>
                            <td><input type="checkbox" name="ids[]" class="idRow" value="{{ $customForm->id }}"></td>
                            <td >{{ $customForm->name }}</td>
                            
                            <td>
                                  
                                <div class="dropdown">
                                 <form method="POST" action="{!! route('custom_forms.custom_form.destroy', $customForm->id) !!}" accept-charset="UTF-8" class="deleteFrm">
                                   @csrf
                                   @method('DELETE')   
                                    <a href="#" data-toggle="dropdown"> <i data-feather="more-horizontal"> </i> </a>
                                    <ul class="dropdown-menu shadow-dropdown action-dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a title="Edit Custom Form #{{ $customForm->id }}" class="dropdown-item" href="{{ route('custom_forms.custom_form.edit', $customForm->id ) }}">
                                            <i data-feather="edit"></i> {{ __('Edit') }}
                                        </a>
                                       </li>
                                        <li>
                                        <button type="submit" class="dropdown-item btn btn-link" title="Delete User" onclick="return confirm('{{ __('Click Ok to delete Custom Form.') }}')">
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
    
{!! $customForms->render() !!}
</div>

