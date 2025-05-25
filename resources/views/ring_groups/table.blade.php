  <div class="row">
      <div class="col-sm-12">

          <table class="table table-striped ">
              <thead>
                  <tr>
                      <th><input type="checkbox" name="checkAll" id="checkAll"></th>

                      <th>{{ __('Code') }}</th>
                      <th class='sortable' sort-by="ring_strategy">{{ __('Ring Strategy') }}</th>
                      <th class='sortable' sort-by="ring_time">{{ __('Ring Time') }}</th>
                      <th class='sortable' sort-by="answer_channel">{{ __('Answer Channel') }}</th>
                      <th class='sortable' sort-by="skip_busy_extension">{{ __('Skip Busy Extension') }}</th>
                      <th class='sortable' sort-by="allow_diversions">{{ __('Allow Diversions') }}</th>
                      <th class='sortable' sort-by="ringback_tone">{{ __('Ringback Tone') }}</th>

                      <th></th>
                  </tr>
              </thead>
              <tbody>
                  @foreach ($ringGroups as $ringGroup)
                      <tr>
                          <td><input type="checkbox" name="ids[]" class="idRow" value="{{ $ringGroup->id }}"></td>

                          <td>{{ $ringGroup->extension->code }}</td>

                          <td>{{ config('enums.ring_strategy')[$ringGroup->ring_strategy] }}</td>
                          <td>{{ $ringGroup->ring_time }}</td>
                          <td>
                              <form method="POST" action="{!! route('ring_groups.ring_group.updateField', $ringGroup->id) !!}" class="editableForm"
                                  accept-charset="UTF-8">
                                  <input type="hidden" name="answer_channel" type="hidden" value="0">
                                  @csrf
                                  @method('PUT')
                                  <div class="toggle">
                                      <label>
                                          <input type="checkbox" name="answer_channel" value="1"
                                              @if ($ringGroup->answer_channel) checked="checked" @endif
                                              class="editableField"><span class="button-indecator"></span>
                                      </label>
                                  </div>
                              </form>

                          </td>
                          <td>
                              <form method="POST" action="{!! route('ring_groups.ring_group.updateField', $ringGroup->id) !!}" class="editableForm"
                                  accept-charset="UTF-8">
                                  <input type="hidden" name="skip_busy_extension" type="hidden" value="0">
                                  @csrf
                                  @method('PUT')
                                  <div class="toggle">
                                      <label>
                                          <input type="checkbox" name="skip_busy_extension" value="1"
                                              @if ($ringGroup->skip_busy_extension) checked="checked" @endif
                                              class="editableField"><span class="button-indecator"></span>
                                      </label>
                                  </div>
                              </form>
                          </td>

                          <td>
                              <form method="POST" action="{!! route('ring_groups.ring_group.updateField', $ringGroup->id) !!}" class="editableForm"
                                  accept-charset="UTF-8">
                                  <input type="hidden" name="allow_diversions" type="hidden" value="0">
                                  @csrf
                                  @method('PUT')
                                  <div class="toggle">
                                      <label>
                                          <input type="checkbox" name="allow_diversions" value="1"
                                              @if ($ringGroup->allow_diversions) checked="checked" @endif
                                              class="editableField"><span class="button-indecator"></span>
                                      </label>
                                  </div>
                              </form>
                          </td>

                          <td>
                              <form method="POST" action="{!! route('ring_groups.ring_group.updateField', $ringGroup->id) !!}" class="editableForm"
                                  accept-charset="UTF-8">
                                  <input type="hidden" name="ringback_tone" type="hidden" value="0">
                                  @csrf
                                  @method('PUT')
                                  <div class="toggle">
                                      <label>
                                          <input type="checkbox" name="ringback_tone" value="1"
                                              @if ($ringGroup->ringback_tone) checked="checked" @endif
                                              class="editableField"><span class="button-indecator"></span>
                                      </label>
                                  </div>
                              </form>
                          </td>

                          <td>

                              <div class="dropdown">
                                  <form method="POST" action="{!! route('ring_groups.ring_group.destroy', $ringGroup->id) !!}" accept-charset="UTF-8"
                                      class="deleteFrm">
                                      @csrf
                                      @method('DELETE')
                                      <a href="#" data-toggle="dropdown"> <i data-feather="more-horizontal"> </i>
                                      </a>
                                      <ul class="dropdown-menu shadow-dropdown action-dropdown-menu"
                                          aria-labelledby="dropdownMenuButton1">
                                          <li><a title="Edit Ring Group #{{ $ringGroup->id }}" class="dropdown-item"
                                                  href="{{ route('ring_groups.ring_group.edit', $ringGroup->id) }}">
                                                  <i data-feather="edit"></i> {{ __('Edit') }}
                                              </a>
                                          </li>
                                          <li>
                                              <button type="submit" class="dropdown-item btn btn-link"
                                                  title="Delete User"
                                                  onclick="return confirm('{{ __('Click Ok to delete Ring Group.') }}')">
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

      {!! $ringGroups->render() !!}
  </div>
