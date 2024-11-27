  <div class="row"><div class="col-sm-12">

                <table class="table table-striped ">
                    <thead>
                        <tr>
                            <th><input type="checkbox" name="checkAll" id="checkAll"></th>
                        
                            <th class="sortable" sort-by="name">{{ __('Contact Group Name')}}</th>
                            <th>No of contacts</th>

                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($contactGroups as $contactGroup)
                        <tr>
                            <td><input type="checkbox" name="ids[]" class="idRow" value="{{ $contactGroup->id }}"></td>
                           
                           
                            <td>{{ $contactGroup->name }}</td>

                            <td>
                                <a href="{{ route('contacts.contact.index') }}?filter=contact_group_id:{{ $contactGroup->id }}">
                                    <span class="badge badge-secondary">{{ $contactGroup->total }}</span>
                                </a>
                            </td>

                            <td>
                                  
                                <div class="dropdown">
                                 <form method="POST" action="{!! route('contact_groups.contact_group.destroy', $contactGroup->id) !!}" accept-charset="UTF-8" class="deleteFrm">
                                   @csrf
                                   @method('DELETE')   
                                    <a href="#" data-toggle="dropdown"> <i data-feather="more-horizontal"> </i> </a>
                                    <ul class="dropdown-menu shadow-dropdown action-dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a title="Edit Contact Group #{{ $contactGroup->id }}" class="dropdown-item btnForm" href="{{ route('contact_groups.contact_group.edit', $contactGroup->id ) }}">
                                            <i data-feather="edit"></i> {{ __('Edit')}}
                                        </a>
                                       </li>
                                        <li>
                                        <button type="submit" class="dropdown-item btn btn-link" title="Delete Contact group" onclick="return confirm('Click Ok to delete Contact Group.')">
                                        <i data-feather="trash"></i> {{ __('Delete')}}
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
    
{!! $contactGroups->render() !!}
</div>

