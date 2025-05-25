  <div class="row"><div class="col-sm-12">

                <table class="table table-striped ">
                    <thead>
                        <tr>
                            <th><input type="checkbox" name="checkAll" id="checkAll"></th>
                            <th class="sortable" sort-by="name">{{ __('Name') }}</th>
                            <th>{{ __('Extensions') }}</th>

                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($extensionGroups as $extensionGroup)
                        <tr>
                            <td><input type="checkbox" name="ids[]" class="idRow" value="{{ $extensionGroup->id }}"></td>


                            <td>{{ $extensionGroup->name }}</td>
                            <td>
                                @foreach($extensionGroup->extension_id as $extId)
                                    <span class="badge badge-primary">{{ isset($extensions[$extId]) ? $extensions[$extId] : '' }}</span>

                                @endforeach
                            </td>

                            <td>
                                  
                                <div class="dropdown">
                                 <form method="POST" action="{!! route('extension_groups.extension_group.destroy', $extensionGroup->id) !!}" accept-charset="UTF-8" class="deleteFrm">
                                   @csrf
                                   @method('DELETE')   
                                    <a href="#" data-toggle="dropdown"> <i data-feather="more-horizontal"> </i> </a>
                                    <ul class="dropdown-menu shadow-dropdown action-dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a title="Edit Extension Group #{{ $extensionGroup->id }}" class="dropdown-item btnForm" href="{{ route('extension_groups.extension_group.edit', $extensionGroup->id ) }}">
                                            <i data-feather="edit"></i> {{ __('Edit') }}
                                        </a>
                                       </li>
                                        <li>
                                        <button type="submit" class="dropdown-item btn btn-link" title="Delete User" onclick="return confirm('{{ __('Click Ok to delete Extension Group.') }}')">
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
    
{!! $extensionGroups->render() !!}
</div>

