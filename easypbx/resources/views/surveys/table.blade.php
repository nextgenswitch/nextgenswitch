  <div class="row"><div class="col-sm-12">

                <table class="table table-striped ">
                    <thead>
                        <tr>
                            <th><input type="checkbox" name="checkAll" id="checkAll"></th>
                                                        
                            <th class="sortable" sort-by="name">{{ __('Name') }}</th>
                            <th>{{ __('Voice') }}</th>
                            <th>{{ __('Type') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($surveys as $survey)
                        <tr>
                            <td><input type="checkbox" name="ids[]" class="idRow" value="{{ $survey->id }}"></td>
                            
                            <td>{{ $survey->name }}</td>
                            
                            <td voice-id="{{ optional($survey->voice)->id }}" class="voice-preview">
                                <span class="btn btn-outline-primary btn-sm play"><i class="fa fa-play"></i></span>
  
                                <span class="btn btn-outline-primary btn-sm stop d-none"><i
                                        class="fa fa-stop"></i></span>
                            </td>

                            <td>{{ config('enums.survey_type')[$survey->type] }}</td>

                            <td>
                                  
                                <div class="dropdown">
                                 <form method="POST" action="{!! route('surveys.survey.destroy', $survey->id) !!}" accept-charset="UTF-8" class="deleteFrm">
                                   @csrf
                                   @method('DELETE')   
                                    <a href="#" data-toggle="dropdown"> <i data-feather="more-horizontal"> </i> </a>
                                    <ul class="dropdown-menu shadow-dropdown action-dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a title="Edit Survey #{{ $survey->id }}" class="dropdown-item" href="{{ route('surveys.survey.edit', $survey->id ) }}">
                                            <i data-feather="edit"></i> {{ __('Edit') }}
                                        </a>
                                       </li>
                                        <li>
                                        <button type="submit" class="dropdown-item btn btn-link" title="Delete User" onclick="return confirm('{{ __('Click Ok to delete Survey.') }}')">
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
    
{!! $surveys->render() !!}
</div>

