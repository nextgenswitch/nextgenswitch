  <div class="row">
      <div class="col-sm-12">

          <table class="table table-striped ">
              <thead>
                  <tr>
                      <th><input type="checkbox" name="checkAll" id="checkAll"></th>
                      <th class="sortable" sort-by="name">{{ __('Name') }}</th>
                      <th>{{ __('Preview') }}</th>

                      <th></th>
                  </tr>
              </thead>
              <tbody>
                  @foreach ($voiceFiles as $voiceFile)
                      <tr>
                          <td><input type="checkbox" name="ids[]" class="idRow" value="{{ $voiceFile->id }}"></td>

                          <td>{{ $voiceFile->name }}</td>

                          <td   class="voice-preview">
                              <span class="btn btn-outline-primary btn-sm play" voice_file_id="{{ $voiceFile->id }}" ><i class="fa fa-play"></i></span>

                              <span class="btn btn-outline-primary btn-sm stop d-none"><i
                                      class="fa fa-stop"></i></span>

                              @if ($voiceFile->voice_type == 1)
                                  <span class="btn btn-outline-primary btn-sm btn-refresh" voice_file_id="{{ $voiceFile->id }}"><i
                                          class="fa fa-refresh"></i></span>
                              @endif
							  
                              <a class="btn btn-outline-primary btn-sm" href="{{ route('voice_files.voice.download', $voiceFile->id ) }}"><i class="fa fa-download"></i></a>
                          </td>

                          <td>

                              <div class="dropdown">
                                  <form method="POST" action="{!! route('voice_files.voice_file.destroy', $voiceFile->id) !!}" accept-charset="UTF-8"
                                      class="deleteFrm">
                                      @csrf
                                      @method('DELETE')
                                      <a href="#" data-toggle="dropdown"> <i data-feather="more-horizontal"> </i>
                                      </a>
                                      <ul class="dropdown-menu shadow-dropdown action-dropdown-menu"
                                          aria-labelledby="dropdownMenuButton1">
                                          <li data-src="{{ asset('storage/uploads/' . auth()->id() . '/' . $voiceFile->file_name) }}"
                                              class="preview-audio"><a title="Preview Voice File #{{ $voiceFile->id }}"
                                                  class="dropdown-item" href="#">
                                                  <i data-feather="headphones"></i> {{ __('Preview') }}
                                              </a>
                                          </li>

                                          <li><a title="Edit Voice File #{{ $voiceFile->id }}" class="dropdown-item"
                                                  href="{{ route('voice_files.voice_file.edit', $voiceFile->id) }}">
                                                  <i data-feather="edit"></i> {{ __('Edit') }}
                                              </a>
                                          </li>
                                          <li>
                                              <button type="submit" class="dropdown-item btn btn-link"
                                                  title="Delete User"
                                                  onclick="return confirm('{{ __('Click Ok to delete Voice File.') }}')">
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

      {!! $voiceFiles->render() !!}
  </div>
