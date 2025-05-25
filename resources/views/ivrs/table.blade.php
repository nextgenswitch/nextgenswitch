  <div class="row"><div class="col-sm-12">

                <table class="table table-striped ">
                    <thead>
                        <tr>
                            <th><input type="checkbox" name="checkAll" id="checkAll"></th>
                            <th class="sortable" sort-by="name">{{ __('Name') }}</th>
                            <th>{{ __('Timeout(sec)') }}</th>
                            <th>{{ __('No of actions') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($ivrs as $ivr)
                        <tr>
                            <td><input type="checkbox" name="ids[]" class="idRow" value="{{ $ivr->id }}"></td>

                            <td><a href="{{ route('ivrs.ivr.edit', $ivr->id ) }}">{{ $ivr->name }}</a></td>
                            <td>{{ $ivr->timeout }}</td>
                            <td>{{ $ivr->actions->count() }}</td>

                            <td>
                                  
                                <div class="dropdown">
                                 <form method="POST" action="{!! route('ivrs.ivr.destroy', $ivr->id) !!}" accept-charset="UTF-8" class="deleteFrm">
                                   @csrf
                                   @method('DELETE')   
                                    <a href="#" data-toggle="dropdown"> <i data-feather="more-horizontal"> </i> </a>
                                    <ul class="dropdown-menu shadow-dropdown action-dropdown-menu" aria-labelledby="dropdownMenuButton1">

                                        <li><a title="Edit Ivr #{{ $ivr->id }}" class="dropdown-item" href="{{ route('ivrs.ivr.edit', $ivr->id ) }}">
                                            <i data-feather="edit"></i> {{ __('Edit') }}
                                        </a>
                                       </li>
                                        <li>
                                        <button type="submit" class="dropdown-item btn btn-link" title="Delete User" onclick="return confirm('{{ __('Click Ok to delete Ivr.') }}')">
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
    
{!! $ivrs->render() !!}
</div>

