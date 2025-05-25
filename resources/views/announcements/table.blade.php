  <div class="row"><div class="col-sm-12">

                <table class="table table-striped ">
                    <thead>
                        <tr>
                            <th><input type="checkbox" name="checkAll" id="checkAll"></th>
                          
                                                        
                            <th class="sortable" sort-by="name">{{ __('Name') }}</th>
                            <th>{{ __('Voice') }}</th>
                           
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($announcements as $announcement)
                        <tr>
                            <td><input type="checkbox" name="ids[]" class="idRow" value="{{ $announcement->id }}"></td>
                           
                                                        
                            <td>{{ $announcement->name }}</td>
                            

                            <td  class="voice-preview">

                                <span class="btn btn-outline-primary btn-sm play" voice_file_id="{{ optional($announcement)->voice_id }}"><i class="fa fa-play"></i></span>
  
                                <span class="btn btn-outline-primary btn-sm stop d-none"><i
                                        class="fa fa-stop"></i></span>
                            </td>

                            

                            <td>
                                  
                                <div class="dropdown">
                                 <form method="POST" action="{!! route('announcements.announcement.destroy', $announcement->id) !!}" accept-charset="UTF-8" class="deleteFrm">
                                   @csrf
                                   @method('DELETE')   
                                    <a href="#" data-toggle="dropdown"> <i data-feather="more-horizontal"> </i> </a>
                                    <ul class="dropdown-menu shadow-dropdown action-dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a title="Edit Announcement #{{ $announcement->id }}" class="dropdown-item btnForm" href="{{ route('announcements.announcement.edit', $announcement->id ) }}">
                                            <i data-feather="edit"></i> {{ __('Edit') }}
                                        </a>
                                       </li>
                                        <li>
                                        <button type="submit" class="dropdown-item btn btn-link" title="Delete User" onclick="return confirm('{{ __('Click Ok to delete Announcement.') }}')">
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
    
{!! $announcements->render() !!}
</div>

