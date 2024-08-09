  <div class="row"><div class="col-sm-12">

                <table class="table table-striped ">
                    <thead>
                        <tr>
                            <th><input type="checkbox" name="checkAll" id="checkAll"></th>
                            
                            <th class="sortable" sort-by="name">{{ __('Name') }}</th>
                            <th>{{ __('Duration') }}</th>
                            <th>{{ __('Price') }}</th>
                        
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($plans as $plan)
                        <tr>
                            <td><input type="checkbox" name="ids[]" class="idRow" value="{{ $plan->id }}"></td>
                            <td>{{ $plan->name }}</td>
                            <td>{{ $plan->duration }}</td>
                            <td>{{ $plan->price }}</td>
                            

                            <td>
                                  
                                <div class="dropdown">
                                 <form method="POST" action="{!! route('plans.plan.destroy', $plan->id) !!}" accept-charset="UTF-8" class="deleteFrm">
                                   @csrf
                                   @method('DELETE')   
                                    <a href="#" data-toggle="dropdown"> <i data-feather="more-horizontal"> </i> </a>
                                    <ul class="dropdown-menu shadow-dropdown action-dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a title="Edit Plan #{{ $plan->id }}" class="dropdown-item btnForm" href="{{ route('plans.plan.edit', $plan->id ) }}">
                                            <i data-feather="edit"></i> {{ __('Edit') }}
                                        </a>
                                       </li>
                                        <li>
                                        <button type="submit" class="dropdown-item btn btn-link" title="Delete User" onclick="return confirm('{{ __('Click Ok to delete Plan.') }}')">
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
    
{!! $plans->render() !!}
</div>

