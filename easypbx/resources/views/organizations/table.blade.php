  <div class="row"><div class="col-sm-12">

                <table class="table table-striped ">
                    <thead>
                        <tr>
                            <th><input type="checkbox" name="checkAll" id="checkAll"></th>

                            <th class="sortable" sort-by="name">{{ __('Name') }}</th>
                            <th class="sortable" sort-by="domain">{{ __('Domain') }}</th>
                            <th class="sortable" sort-by="contact_no">{{ __('Contact No') }}</th>
                            <th class="sortable" sort-by="email">{{ __('Email') }}</th>
                            <th>{{ __('Plan') }}</th>

                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($organizations as $organization)
                        <tr>
                            <td><input type="checkbox" name="ids[]" class="idRow" value="{{ $organization->id }}"></td>

                            
                            <td>{{ $organization->name }}</td>
                            <td>{{ $organization->domain }}</td>
                            <td>{{ $organization->contact_no }}</td>
                            <td>{{ $organization->email }}</td>
                            <td>{{ optional($organization->plan)->name }}</td>

                            <td>
                                  
                                <div class="dropdown">
                                 <form method="POST" action="{!! route('organizations.organization.destroy', $organization->id) !!}" accept-charset="UTF-8" class="deleteFrm">
                                   @csrf
                                   @method('DELETE')   
                                    <a href="#" data-toggle="dropdown"> <i data-feather="more-horizontal"> </i> </a>
                                    <ul class="dropdown-menu shadow-dropdown action-dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a title="Edit Organization #{{ $organization->id }}" class="dropdown-item btnForm" href="{{ route('organizations.organization.edit', $organization->id ) }}">
                                            <i data-feather="edit"></i> {{ __('Edit') }}
                                        </a>
                                       </li>
                                        <li>
                                        <button type="submit" class="dropdown-item btn btn-link" title="Delete User" onclick="return confirm('{{ __('Click Ok to delete Organization.') }}')">
                                        <i data-feather="trash"></i> {{ __('Delete') }}
                                        </button>
                                        </li>
        
                                        <li><a title="Login to Organization" class="dropdown-item" href="{{ route('organizations.organization.login', $organization->id ) }}"
                                        onclick="return confirm('Are you sure you would like to login to this organization?');"
                                        >
                                            <i data-feather="log-in"></i> {{ __('Login') }}
                                        </a>
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
    
{!! $organizations->render() !!}
</div>

