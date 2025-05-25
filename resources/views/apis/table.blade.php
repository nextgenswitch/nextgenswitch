  <div class="row"><div class="col-sm-12">

                <table class="table table-striped " id="api_table" secret="{{ session()->has('secret') ? session('secret') : '' }}">
                    <thead>
                        <tr>
                            <th><input type="checkbox" name="checkAll" id="checkAll"></th>

                            <th class="sortable" sort-by="title">{{ __('Title') }}</th>
                            <th>{{ __('Key') }}</th>
                            
                            <th>{{ __('Status') }}</th>

                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($apis as $api)
                        <tr>
                            <td><input type="checkbox" name="ids[]" class="idRow" value="{{ $api->id }}"></td>

                            <td>{{ $api->title }}</td>
                            
                            <td>{{ $api->key }}</td>


                            <td>

                             <form method="POST" action="{!! route('apis.api.updateField', $api->id) !!}" class="editableForm" accept-charset="UTF-8">
                                <input type="hidden" name="status" type="hidden" value="0">
                                @csrf
                                @method('PUT')
                                 <div class="toggle">
                                  <label>
                                    <input type="checkbox" name="status" value="1"
                                    @if ($api->status) checked="checked" @endif
                                    class="editableField"><span class="button-indecator"></span>
                                  </label>
                                </div>
                                </form>

                            </td>


                            <td>
                                  
                                <div class="dropdown">
                                 <form method="POST" action="{!! route('apis.api.destroy', $api->id) !!}" accept-charset="UTF-8" class="deleteFrm">
                                   @csrf
                                   @method('DELETE')   
                                    <a href="#" data-toggle="dropdown"> <i data-feather="more-horizontal"> </i> </a>
                                    <ul class="dropdown-menu shadow-dropdown action-dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a title="Edit Api #{{ $api->id }}" class="dropdown-item btnForm" href="{{ route('apis.api.edit', $api->id ) }}">
                                            <i data-feather="edit"></i> {{ __('Edit') }}
                                        </a>
                                       </li>

                                       <li><a title="Regenerate Api Secret #{{ $api->id }}" class="dropdown-item" href="{{ route('apis.api.regenerate', $api->id ) }}">
                                            <i data-feather="refresh-cw"></i> {{ __('Regenerate secret') }}
                                        </a>
                                    </li>

                                    <li><a title="Api Logs #{{ $api->id }}" class="dropdown-item" href="{{ route('apis.api.logs', $api->id ) }}">
                                        <i data-feather="activity"></i> {{ __('API Logs') }}
                                    </a>
                                </li>
                                       
                                        <li>
                                        <button type="submit" class="dropdown-item btn btn-link" title="Delete User" onclick="return confirm('{{ __('Click Ok to delete Api.') }}')">
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
    
{!! $apis->render() !!}
</div>

