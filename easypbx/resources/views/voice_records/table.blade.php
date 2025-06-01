  <div class="row"><div class="col-sm-12">

                <table class="table table-striped ">
                    <thead>
                        <tr>
                            <th><input type="checkbox" name="checkAll" id="checkAll"></th>
                            
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Announcement') }}</th>
                            <th>{{ __('Transcript') }}</th>
                            <th>{{ __('Create Ticket') }}</th>

                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($voiceRecords as $voiceRecord)
                        <tr>
                            <td><input type="checkbox" name="ids[]" class="idRow" value="{{ $voiceRecord->id }}"></td>
                            
                            <td>{{ $voiceRecord->name }}</td>
                            <td  class="voice-preview">
                                <span class="btn btn-outline-primary btn-sm play" voice_file_id="{{ optional($voiceRecord)->voice_id }}"><i class="fa fa-play"></i></span>
                                <span class="btn btn-outline-primary btn-sm stop d-none"><i class="fa fa-stop"></i></span>
                            </td>
                            <td>{{ ($voiceRecord->is_transcript) ? 'Yes' : 'No' }}</td>
                            <td>{{ ($voiceRecord->is_create_ticket) ? 'Yes' : 'No' }}</td>
                            
                            <td>
                                  
                                <div class="dropdown">
                                 <form method="POST" action="{!! route('voice_records.voice_record.destroy', $voiceRecord->id) !!}" accept-charset="UTF-8" class="deleteFrm">
                                   @csrf
                                   @method('DELETE')   
                                    <a href="#" data-toggle="dropdown"> <i data-feather="more-horizontal"> </i> </a>
                                    <ul class="dropdown-menu shadow-dropdown action-dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a class="dropdown-item" href="{{ route('monitoring.voice_mails.index') . '?filter=voice_record_id:' . $voiceRecord->id }}">
                                            <i data-feather="activity"></i> {{ __('Logs') }}
                                        </a>

                                        <li><a title="Edit Voice Record #{{ $voiceRecord->id }}" class="dropdown-item btnForm" href="{{ route('voice_records.voice_record.edit', $voiceRecord->id ) }}">
                                            <i data-feather="edit"></i> {{ __('Edit') }}
                                        </a>
                                       </li>
                                        <li>
                                        <button type="submit" class="dropdown-item btn btn-link" title="Delete User" onclick="return confirm('{{ __('Click Ok to delete Voice Record.') }}')">
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
    
{!! $voiceRecords->render() !!}
</div>

