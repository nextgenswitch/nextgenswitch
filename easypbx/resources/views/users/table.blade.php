  <div class="row"><div class="col-sm-12">

                <table class="table table-striped ">
                    <thead>
                        <tr>
                            <th><input type="checkbox" name="checkAll" id="checkAll"></th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td><input type="checkbox" name="ids[]" class="idRow" value="{{ $user->id }}"></td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>

                            <td>
                                {{ ($user->status) ? 'Active' : 'Deactive' }}

                             <form method="POST" action="{!! route('users.user.updateField', [$user->id]) !!}" class="editableForm" accept-charset="UTF-8">
                                <input type="hidden" name="status" type="hidden" value="0">
                                @csrf
                                @method('PUT')
                                 <div class="toggle">
                                  <label>
                                    <input type="checkbox" name="status" value="1"
                                    @if ($user->status) checked="checked" @endif
                                    class="editableField"><span class="button-indecator"></span>
                                  </label>
                                </div>
                                </form>

                            </td>

                            <td>
                                  
                                <div class="dropdown">
                                 <form method="POST" action="{!! route('users.user.destroy', [$user->id] ) !!}" accept-charset="UTF-8" class="deleteFrm">
                                   @csrf
                                   @method('DELETE')   
                                    <a href="#" data-toggle="dropdown"> <i data-feather="more-horizontal"> </i> </a>
                                    <ul class="dropdown-menu shadow-dropdown action-dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a title="Edit User #{{ $user->id }}" class="dropdown-item btnForm" href="{{ route('users.user.edit', [$user->id] ) }}">
                                            <i data-feather="edit"></i> {{ __('Edit') }}
                                        </a>
                                       </li>
                                        <li>
                                        <button type="submit" class="dropdown-item btn btn-link" title="Delete User" onclick="return confirm('{{ __('Click Ok to delete User.') }}')">
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
    
{!! $users->render() !!}
</div>
