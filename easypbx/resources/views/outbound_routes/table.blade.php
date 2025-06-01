  <div class="row"><div class="col-sm-12">

                <table class="table table-striped ">
                    <thead>
                        <tr>
                            <th><input type="checkbox" name="checkAll" id="checkAll"></th>

                            <th class="sortable" sort-by="name">{{ __('Name') }}</th>

                            <th>{{ __('Trunk') }}</th>
                            <!-- <th class="sortable" sort-by="type">{{ __('API') }}</th> -->
                            <th>{{ __('Status') }}</th>
                            
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($outboundRoutes as $outboundRoute)
                        <tr>
                            <td><input type="checkbox" name="ids[]" class="idRow" value="{{ $outboundRoute->id }}"></td>


                            <td>{{ $outboundRoute->name }}</td>

                            
                            <td>@php
                                $trunks = [];
                                foreach($outboundRoute->trunks as $trunk)
                                    $trunks[] =  $trunk->name;                                
                                @endphp
                                {{ implode(",",$trunks) }}

                            </td>

                           <!--  <td> {{ $outboundRoute->type == 1 ? 'Yes' : 'No'}}</td> -->
                            
                            <td>


                             <form method="POST" action="{!! route('outbound_routes.outbound_route.updateField', $outboundRoute->id) !!}" class="editableForm" accept-charset="UTF-8">
                                <input type="hidden" name="is_active" type="hidden" value="0">
                                @csrf
                                @method('PUT')
                                 <div class="toggle">
                                  <label>
                                    <input type="checkbox" name="is_active" value="1"
                                    @if ($outboundRoute->is_active) checked="checked" @endif
                                    class="editableField"><span class="button-indecator"></span>
                                  </label>
                                </div>
                                </form>

                            </td>


                            


                            <td>
                                  
                                <div class="dropdown">
                                 <form method="POST" action="{!! route('outbound_routes.outbound_route.destroy', $outboundRoute->id) !!}" accept-charset="UTF-8" class="deleteFrm">
                                   @csrf
                                   @method('DELETE')   
                                    <a href="#" data-toggle="dropdown"> <i data-feather="more-horizontal"> </i> </a>
                                    <ul class="dropdown-menu shadow-dropdown action-dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a title="Edit Outbound Route #{{ $outboundRoute->id }}" class="dropdown-item" href="{{ route('outbound_routes.outbound_route.edit', $outboundRoute->id ) }}">
                                            <i data-feather="edit"></i> {{ __('Edit') }}
                                        </a>
                                       </li>
                                        <li>
                                        <button type="submit" class="dropdown-item btn btn-link" title="Delete User" onclick="return confirm('{{ __('Click Ok to delete Outbound Route.') }}')">
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
    
{!! $outboundRoutes->render() !!}
</div>

