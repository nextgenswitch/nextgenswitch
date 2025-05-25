  <div class="row"><div class="col-sm-12">

                <table class="table table-striped ">
                    <thead>
                        <tr>
                            <th><input type="checkbox" name="checkAll" id="checkAll"></th>
                            
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Provider') }}</th>
                            <th>{{ __('Resource') }}</th>

                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($aiBots as $aiBot)
                        <tr>
                            <td><input type="checkbox" name="ids[]" class="idRow" value="{{ $aiBot->id }}"></td>
                            <td>{{ $aiBot->name }}</td>
                            <td>{{ optional($aiBot->llm_provider)->name }}</td>
                            <td>{{ Str::limit($aiBot->resource, 50) }}</td>
                            
                            <td>
                                  
                                <div class="dropdown">
                                 <form method="POST" action="{!! route('ai_bots.ai_bot.destroy', $aiBot->id) !!}" accept-charset="UTF-8" class="deleteFrm">
                                   @csrf
                                   @method('DELETE')   
                                    <a href="#" data-toggle="dropdown"> <i data-feather="more-horizontal"> </i> </a>
                                    <ul class="dropdown-menu shadow-dropdown action-dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a title="Edit AI Bot #{{ $aiBot->id }}" class="dropdown-item" href="{{ route('ai_bots.ai_bot.edit', $aiBot->id ) }}">
                                            <i data-feather="edit"></i> {{ __('Edit') }}
                                        </a>
                                       </li>
                                       
                                       <li><a class="dropdown-item" href="{{ route('ai_bots.ai_bot.ai_assistant_calls' ) }}">
                                            <i data-feather="phone-incoming"></i> {{ __('Ai Assistant Calls') }}
                                        </a>
                                       </li>


                                        <li>
                                        <button type="submit" class="dropdown-item btn btn-link" title="Delete User" onclick="return confirm('{{ __('Click Ok to delete AI Assistant.') }}')">
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
    
{!! $aiBots->render() !!}
</div>

