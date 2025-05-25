  <div class="row"><div class="col-sm-12">

                <table class="table table-striped ">
                    <thead>
                        <tr>
                            <th><input type="checkbox" name="checkAll" id="checkAll"></th>

                            <th class="sortable" sort-by="name">{{ __('Name') }}</th>
                            <th>{{ __('Username') }}</th>
                            <th>{{ __('Password') }}</th>

                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($hotdesks as $hotdesk)
                        <tr>
                            <td><input type="checkbox" name="ids[]" class="idRow" value="{{ $hotdesk->id }}"></td>

                            <td>{{ $hotdesk->name }}</td>
                             <td>{{ optional($hotdesk->sipUser)->username }}</td>
                            <td> <span class="pass-val"> ****** </span> <span class="show-pass btn btn-sm btn-outline-primary" pass="{{ optional($hotdesk->sipUser)->password }}"> <i class="fa fa-eye"> </i> </span> </td>

                            <td>
                                  
                                <div class="dropdown">
                                 <form method="POST" action="{!! route('hotdesks.hotdesk.destroy', $hotdesk->id) !!}" accept-charset="UTF-8" class="deleteFrm">
                                   @csrf
                                   @method('DELETE')   
                                    <a href="#" data-toggle="dropdown"> <i data-feather="more-horizontal"> </i> </a>
                                    <ul class="dropdown-menu shadow-dropdown action-dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a title="Edit Hotdesk #{{ $hotdesk->id }}" class="dropdown-item btnForm" href="{{ route('hotdesks.hotdesk.edit', $hotdesk->id ) }}">
                                            <i data-feather="edit"></i> {{ __('Edit') }}
                                        </a>
                                       </li>
                                        <li>
                                        <button type="submit" class="dropdown-item btn btn-link" title="Delete User" onclick="return confirm('{{ __('Click Ok to delete Hotdesk.') }}')">
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
    
{!! $hotdesks->render() !!}
</div>

