<style>
    .telNo:hover {cursor: pointer;}
  </style>
  <div class="row"><div class="col-sm-12">

                <table class="table table-striped ">
                    <thead>
                        <tr>
                            <th><input type="checkbox" name="checkAll" id="checkAll"></th>
                           
                            <th class="sortable" sort-by="first_name">{{ __('First Name') }}</th>
                            <th class="sortable" sort-by="last_name">{{ __('Last Name') }}</th>
                            {{-- <th class="sortable" sort-by="cc">{{ __('Country Code') }}</th> --}}
                            <th>{{ __('Tel No') }}</th>
                            <th>{{ __('Contact Group') }}</th>

                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($contacts as $contact)
                        <tr>
                            <td><input type="checkbox" name="ids[]" class="idRow" value="{{ $contact->id }}"></td>
                            <td>{{ $contact->first_name }}</td>
                            <td>{{ $contact->last_name }}</td>
                            {{-- <td>{{ !empty($contact->cc) ? config('enums.tel_codes')[$contact->cc] : '' }}</td> --}}
                            <td>{{ $contact->tel_no }} <i tel="{{ $contact->tel_no }}" class="fa fa-phone call-now"></i></td>

                            <td>@foreach($contact->contact_groups as  $group_id)

                                  <a href="{!! route('contacts.contact.index') !!}?filter=contact_group_id:{{ $group_id }}" > <span class="badge badge-secondary">{{ isset($contact_groups[$group_id])?$contact_groups[$group_id]:'' }}</span> </a>

                                @endforeach
                            </td>

                            <td>
                                  
                                <div class="dropdown">
                                 <form method="POST" action="{!! route('contacts.contact.destroy', $contact->id) !!}" accept-charset="UTF-8" class="deleteFrm">
                                   @csrf
                                   @method('DELETE')   
                                    <a href="#" data-toggle="dropdown"> <i data-feather="more-horizontal"> </i> </a>
                                    <ul class="dropdown-menu shadow-dropdown action-dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a title="Edit Contact #{{ $contact->id }}" class="dropdown-item btnForm" href="{{ route('contacts.contact.edit', $contact->id ) }}">
                                            <i data-feather="edit"></i> {{ __('Edit') }}
                                        </a>
                                       </li>
                                        <li>
                                        <button type="submit" class="dropdown-item btn btn-link" title="Delete User" onclick="return confirm('Click Ok to delete Contact.')">
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
    
{!! $contacts->render() !!}
</div>

