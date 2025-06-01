  <div class="row"><div class="col-sm-12">

                <table class="table table-striped ">
                    <thead>
                        <tr>
                            <th><input type="checkbox" name="checkAll" id="checkAll"></th>
                            <th class="sortable" sort-by="name">{{ __('Name') }}</th>
                            <th class="sortable" sort-by="did_pattern">{{ __('Did Pattern') }}</th>
                            <th class="sortable" sort-by="cid_pattern">{{ __('Cid Pattern') }}</th>
                            <th>{{ __('Inbound Destination') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($inboundRoutes as $inboundRoute)
                        <tr>
                            <td><input type="checkbox" name="ids[]" class="idRow" value="{{ $inboundRoute->id }}"></td>


                            <td>{{ $inboundRoute->name }}</td>
                            <td>{{ $inboundRoute->did_pattern }}</td>
                            <td>{{ $inboundRoute->cid_pattern }}</td>
                            <td>{{ optional($inboundRoute->func)->name }}</td>


                            <td>
                                  
                                <div class="dropdown">
                                 <form method="POST" action="{!! route('inbound_routes.inbound_route.destroy', $inboundRoute->id) !!}" accept-charset="UTF-8" class="deleteFrm">
                                   @csrf
                                   @method('DELETE')   
                                    <a href="#" data-toggle="dropdown"> <i data-feather="more-horizontal"> </i> </a>
                                    <ul class="dropdown-menu shadow-dropdown action-dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a title="Edit Inbound Route #{{ $inboundRoute->id }}" class="dropdown-item btnForm" href="{{ route('inbound_routes.inbound_route.edit', $inboundRoute->id ) }}">
                                            <i data-feather="edit"></i> {{ __('Edit') }}
                                        </a>
                                       </li>
                                        <li>
                                        <button type="submit" class="dropdown-item btn btn-link" title="Delete User" onclick="return confirm('{{ __('Click Ok to delete Inbound Route.') }}')">
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
    
{!! $inboundRoutes->render() !!}
</div>

