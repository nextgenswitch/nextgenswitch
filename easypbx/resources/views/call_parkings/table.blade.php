  <div class="row"><div class="col-sm-12">

                <table class="table table-striped ">
                    <thead>
                        <tr>
                            <th><input type="checkbox" name="checkAll" id="checkAll"></th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Extension No') }}</th>
                            <th>{{ __('No Of Slot') }}</th>
                            <th>{{ __('Music On Hold') }}</th>
                            <th>{{ __('Timeout') }}</th>
                            
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($callParkings as $callParking)
                        <tr>
                            <td><input type="checkbox" name="ids[]" class="idRow" value="{{ $callParking->id }}"></td>
                            <td>{{ $callParking->name }}</td>
                            <td>{{ $callParking->extension_no }}</td>
                            <td>{{ $callParking->no_of_slot }}</td>
                            <!-- <td>{{ $callParking->music_on_hold }}</td> -->
                            <td  class="voice-preview">
                                <span class="btn btn-outline-primary btn-sm play" voice_file_id="{{ optional($callParking)->music_on_hold }}"><i class="fa fa-play"></i></span>
                                <span class="btn btn-outline-primary btn-sm stop d-none">
                                    <i class="fa fa-stop"></i>
                                </span>
                            </td>

                            <td>{{ $callParking->timeout }}</td>
                            
                            <td>
                                  
                                <div class="dropdown">
                                 <form method="POST" action="{!! route('call_parkings.call_parking.destroy', $callParking->id) !!}" accept-charset="UTF-8" class="deleteFrm">
                                   @csrf
                                   @method('DELETE')   
                                    <a href="#" data-toggle="dropdown"> <i data-feather="more-horizontal"> </i> </a>
                                    <ul class="dropdown-menu shadow-dropdown action-dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a title="Edit Call Parking #{{ $callParking->id }}" class="dropdown-item btnForm" href="{{ route('call_parkings.call_parking.edit', $callParking->id ) }}">
                                            <i data-feather="edit"></i> {{ __('Edit') }}
                                        </a>
                                       </li>
                                        <li>
                                        <button type="submit" class="dropdown-item btn btn-link" title="Delete User" onclick="return confirm('{{ __('Click Ok to delete Call Parking.') }}')">
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
    
{!! $callParkings->render() !!}
</div>

