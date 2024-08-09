  <div class="row"><div class="col-sm-12">

                <table class="table table-striped ">
                    <thead>
                        <tr>
                            <th><input type="checkbox" name="checkAll" id="checkAll"></th>
                            <th class="sortable" sort-by="name">{{ __('Name') }}</th>
                            <th>{{ __('Username') }}</th>
                            <th>{{ __('password') }}</th>
                            <th class="sortable" sort-by="code">{{ __('Code') }}</th>
                            <th class="sortable" sort-by="status">{{ __('Status') }}</th>

                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($extensions as $extension)
                        <tr>
                            <td><input type="checkbox" name="ids[]" class="idRow" value="{{ $extension->id }}"></td>
                            <td class="sortable" sort-by="name">{{ $extension->name }}</td>
                            <td>{{ optional($extension->sipUser)->username }}</td>
                            <td> <span class="pass-val"> ****** </span> <span class="show-pass btn btn-sm btn-outline-primary" pass="{{ optional($extension->sipUser)->password }}"> <i class="fa fa-eye"> </i> </span> </td>
                            <td>{{ $extension->code }}</td>

                            <td>
                                {{ ($extension->status) ? 'Active' : 'Deactive' }}

                             <form method="POST" action="{!! route('extensions.extension.updateField', $extension->id) !!}" class="editableForm" accept-charset="UTF-8">
                                <input type="hidden" name="status" type="hidden" value="0">
                                @csrf
                                @method('PUT')
                                 <div class="toggle">
                                  <label>
                                    <input type="checkbox" name="status" value="1"
                                    @if ($extension->status) checked="checked" @endif
                                    class="editableField"><span class="button-indecator"></span>
                                  </label>
                                </div>
                                </form>

                            </td>


                            <td>
                                  
                                <div class="dropdown">
                                 <form method="POST" action="{!! route('extensions.extension.destroy', $extension->id) !!}" accept-charset="UTF-8" class="deleteFrm">
                                   @csrf
                                   @method('DELETE')   
                                    <a href="#" data-toggle="dropdown"> <i data-feather="more-horizontal"> </i> </a>
                                    <ul class="dropdown-menu shadow-dropdown action-dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a title="Edit Extension #{{ $extension->id }}" class="dropdown-item btnForm" href="{{ route('extensions.extension.edit', $extension->id ) }}">
                                            <i data-feather="edit"></i> {{ __('Edit') }}
                                        </a>
                                       </li>
                                        <li>
                                        <button type="submit" class="dropdown-item btn btn-link" title="Delete User" onclick="return confirm('{{ __('Click Ok to delete Extension.') }}')">
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
    
{!! $extensions->render() !!}
</div>

