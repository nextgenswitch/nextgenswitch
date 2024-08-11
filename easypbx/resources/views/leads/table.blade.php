  <div class="row"><div class="col-sm-12">

                <table class="table table-striped ">
                    <thead>
                        <tr>
                            <th><input type="checkbox" name="checkAll" id="checkAll"></th>                            
                            <th class="sortable" sort-by="name">{{ __('Name') }}</th>
                            <th>{{ __('Designation') }}</th>
                            <th class="sortable" sort-by="phone">{{ __('Phone') }}</th>
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('Website') }}</th>
                            <th>{{ __('Company') }}</th>
                            <th>{{ __('Status') }}</th>

                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($leads as $lead)
                        <tr>
                            <td><input type="checkbox" name="ids[]" class="idRow" value="{{ $lead->id }}"></td>
                            
                            <td>{{ $lead->name }}</td>
                            <td>{{ $lead->designation }}</td>
                            <td>{{ $lead->phone }}</td>
                            <td>{{ $lead->email }}</td>
                            <td>{{ $lead->website }}</td>
                            <td>{{ $lead->company }}</td>
                            <td> @if(isset($lead->status)) {{ config('enums.lead_status')[$lead->status] }} @endif </td>

                            <td>
                                  
                                <div class="dropdown">
                                 <form method="POST" action="{!! route('leads.lead.destroy', $lead->id) !!}" accept-charset="UTF-8" class="deleteFrm">
                                   @csrf
                                   @method('DELETE')   
                                    <a href="#" data-toggle="dropdown"> <i data-feather="more-horizontal"> </i> </a>
                                    <ul class="dropdown-menu shadow-dropdown action-dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a title="Edit Lead #{{ $lead->id }}" class="dropdown-item btnForm" href="{{ route('leads.lead.edit', $lead->id ) }}">
                                            <i data-feather="edit"></i> {{ __('Edit') }}
                                        </a>
                                       </li>
                                        <li>
                                        <button type="submit" class="dropdown-item btn btn-link" title="Delete User" onclick="return confirm('{{ __('Click Ok to delete Lead.') }}')">
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
    
{!! $leads->render() !!}
</div>
