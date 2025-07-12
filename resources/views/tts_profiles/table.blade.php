  <div class="row">
      <div class="col-sm-12">

          <table class="table table-striped ">
              <thead>
                  <tr>
                      <th><input type="checkbox" name="checkAll" id="checkAll"></th>
                      <th class="sortable" sort-by="name">{{ __('Name') }}</th>

                      <th>{{ __('Provider') }}</th>
                      <th>{{ __('Type') }}</th>
                      <th>{{ __('Default') }}</th>
                      <th class="sortable" sort-by="language">{{ __('Language') }}</th>
                      <th class="sortable" sort-by="model">{{ __('Model') }}</th>

                      <th></th>
                  </tr>
              </thead>
              <tbody>
                  @foreach ($ttsProfiles as $ttsProfile)
                      @php
                          if ($ttsProfile->type == 0) {
                              $providers = config('enums.tts_providers');
                          }
                          if ($ttsProfile->type == 1) {
                              $providers = config('enums.stt_providers');
                          }
                          if ($ttsProfile->type == 2) {
                              $providers = config('enums.llm_providers');
                          }

                          // $providers = $ttsProfile->type ? config('enums.stt_providers') : config('enums.tts_providers');

                      @endphp
                      <tr>
                          <td><input type="checkbox" name="ids[]" class="idRow" value="{{ $ttsProfile->id }}"></td>
                          <td>{{ $ttsProfile->name }}</td>

                          <td>{{ $providers[$ttsProfile->provider] }}</td>
                          <td>
                              @if ($ttsProfile->type == 0)
                                  {{ __('TTS') }}
                              @endif
                              @if ($ttsProfile->type == 1)
                                  {{ __('STT') }}
                              @endif
                              @if ($ttsProfile->type == 2)
                                  {{ __('LLM') }}
                              @endif

                          </td>
                          <td>

                              {{ $ttsProfile->is_default ? 'Yes' : 'No' }}

                              <form method="POST" action="{!! route('tts_profiles.tts_profile.updateField', $ttsProfile->id) !!}" class="editableForm"
                                  accept-charset="UTF-8">
                                  <input type="hidden" name="is_default" type="hidden" value="0">
                                  @csrf
                                  @method('PUT')
                                  <div class="toggle">
                                      <label>
                                          <input type="checkbox" name="is_default" value="1"
                                              @if ($ttsProfile->is_default) checked="checked" @endif
                                              class="editableField"><span class="button-indecator"></span>
                                      </label>
                                  </div>
                              </form>

                          </td>
                          <td>{{ isset(config('enums.tts_languages')[$ttsProfile->language]) ? config('enums.tts_languages')[$ttsProfile->language] : '' }}
                          </td>
                          <td>{{ $ttsProfile->model }}</td>

                          <td>

                              <div class="dropdown">
                                  <form method="POST" action="{!! route('tts_profiles.tts_profile.destroy', $ttsProfile->id) !!}" accept-charset="UTF-8"
                                      class="deleteFrm">
                                      @csrf
                                      @method('DELETE')
                                      <a href="#" data-toggle="dropdown"> <i data-feather="more-horizontal"> </i>
                                      </a>
                                      <ul class="dropdown-menu shadow-dropdown action-dropdown-menu"
                                          aria-labelledby="dropdownMenuButton1">
                                          <li><a title="Edit AI Provider #{{ $ttsProfile->id }}"
                                                  class="dropdown-item btnForm"
                                                  href="{{ route('tts_profiles.tts_profile.edit', $ttsProfile->id) }}">
                                                  <i data-feather="edit"></i> {{ __('Edit') }}
                                              </a>
                                          </li>

                                          <li><a class="dropdown-item"
                                                  href="{{ route('tts_profiles.tts_profile.histories', $ttsProfile->id) }}">
                                                  <i data-feather="activity"></i> {{ __('History') }}
                                              </a>
                                          </li>

                                          <li>
                                              <button type="submit" class="dropdown-item btn btn-link"
                                                  title="Delete User"
                                                  onclick="return confirm('{{ __('Click Ok to delete Tts Profile.') }}')">
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

      {!! $ttsProfiles->render() !!}
  </div>
