  <div class="row"><div class="col-sm-12">

                <table class="table table-striped ">
                    <thead>
                        <tr>
                            <th><input type="checkbox" name="checkAll" id="checkAll"></th>
                            <th>{{ __('Title') }}</th>
                            <th>{{ __('Voice File') }}</th>
                            <th>{{ __('Match Type') }}</th>
                            <th>{{ __('Matched Value') }}</th>
                            <th>{{ __('Match Action') }}</th>
                            <th>{{ __('unmatch Action') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($flows as $flow)
                        <tr>
                            <td><input type="checkbox" name="ids[]" class="idRow" value="{{ $flow->id }}"></td>
                            <td>{{ $flow->title }}</td>
                            <td>{{ $flow->voiceFile->name }}</td>
                            <td>{{ config('enums.flow_match_type')[$flow->match_type] }}</td>
                            <td>{{ $flow->matched_value }}</td>
                            <td>{{ $flow->matched_action }}</td>
                            <td>{{ $flow->unmatched_action }}</td>
                            

                            <td>
                                  
                                <div class="dropdown">
                                 <form method="POST" action="{!! route('flows.flow.destroy', $flow->id) !!}" accept-charset="UTF-8" class="deleteFrm">
                                   @csrf
                                   @method('DELETE')   
                                    <a href="#" data-toggle="dropdown"> <i data-feather="more-horizontal"> </i> </a>
                                    <ul class="dropdown-menu shadow-dropdown action-dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a title="Edit Flow #{{ $flow->id }}" class="dropdown-item btnForm" href="{{ route('flows.flow.edit', $flow->id ) }}">
                                            <i data-feather="edit"></i> {{ __('Edit') }}
                                        </a>
                                       </li>
                                        <li>
                                        <button type="submit" class="dropdown-item btn btn-link" title="Delete User" onclick="return confirm('{{ __('Click Ok to delete Flow.') }}')">
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
    
{!! $flows->render() !!}
</div>

