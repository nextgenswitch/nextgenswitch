  <div class="row"><div class="col-sm-12">

                <table class="table table-striped ">
                    <thead>
                        <tr>
                            <th><input type="checkbox" name="checkAll" id="checkAll"></th>
                            <th  class="sortable" sort-by="name">{{ __('Name') }}</th>
                            <th  class="sortable" sort-by="provider">{{ __('Provider') }}</th>
                            <th>{{ __('Default') }}</th>
                            <th>{{ __('Active') }}</th>

                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($smsProfiles as $smsProfile)
                        <tr>
                            <td><input type="checkbox" name="ids[]" class="idRow" value="{{ $smsProfile->id }}"></td>
                            <td>{{ $smsProfile->name }}</td>
                            <td>{{ $smsProfile->provider }}</td>

                            <td>
                                {{ ($smsProfile->default) ? 'Active' : 'Deactive' }}

                             <form method="POST" action="{!! route('sms_profiles.sms_profile.updateField', $smsProfile->id) !!}" class="editableForm" accept-charset="UTF-8">
                                <input type="hidden" name="default" type="hidden" value="0">
                                @csrf
                                @method('PUT')
                                 <div class="toggle">
                                  <label>
                                    <input type="checkbox" name="default" value="1"
                                    @if ($smsProfile->default) checked="checked" @endif
                                    class="editableField"><span class="button-indecator"></span>
                                  </label>
                                </div>
                                </form>

                            </td>

                            <td>
                                {{ ($smsProfile->status) ? 'Active' : 'Deactive' }}

                             <form method="POST" action="{!! route('sms_profiles.sms_profile.updateField', $smsProfile->id) !!}" class="editableForm" accept-charset="UTF-8">
                                <input type="hidden" name="status" type="hidden" value="0">
                                @csrf
                                @method('PUT')
                                 <div class="toggle">
                                  <label>
                                    <input type="checkbox" name="status" value="1"
                                    @if ($smsProfile->status) checked="checked" @endif
                                    class="editableField"><span class="button-indecator"></span>
                                  </label>
                                </div>
                                </form>

                            </td>


                            <td>
                                  
                                <div class="dropdown">
                                 <form method="POST" action="{!! route('sms_profiles.sms_profile.destroy', $smsProfile->id) !!}" accept-charset="UTF-8" class="deleteFrm">
                                   @csrf
                                   @method('DELETE')   
                                    <a href="#" data-toggle="dropdown"> <i data-feather="more-horizontal"> </i> </a>
                                    <ul class="dropdown-menu shadow-dropdown action-dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a title="Edit Sms Profile #{{ $smsProfile->id }}" class="dropdown-item" href="{{ route('sms_profiles.sms_profile.edit', $smsProfile->id ) }}">
                                            <i data-feather="edit"></i> {{ __('Edit') }}
                                        </a>
                                       </li>
                                        <li>
                                        <button type="submit" class="dropdown-item btn btn-link" title="Delete User" onclick="return confirm('{{ __('Click Ok to delete Sms Profile.') }}')">
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
    
{!! $smsProfiles->render() !!}
</div>

