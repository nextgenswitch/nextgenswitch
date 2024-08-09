  <div class="row">
      <div class="col-sm-12">

          <table class="table table-striped ">
              <thead>
                  <tr>
                      <th><input type="checkbox" name="checkAll" id="checkAll"></th>
                      <th>{{ __('Extension') }}</th>
                      <th>{{ __('Member Type') }}</th>
                      <th>{{ __('Priority') }}</th>
                      <th>{{ __('Allow Diversion') }}</th>

                      <th></th>
                  </tr>
              </thead>
              <tbody>
                  @foreach ($callQueueExtensions as $callQueueExtension)
                      <tr>
                          <td><input type="checkbox" name="ids[]" class="idRow" value="{{ $callQueueExtension->id }}">
                          </td>
                          <td>{{ optional($callQueueExtension->extension)->name }}</td>
                          <td>{{ $callQueueExtension->member_type }}</td>
                          <td>{{ $callQueueExtension->priority }}</td>

                          <td>
                              {{ $callQueueExtension->allow_diversion ? 'True' : 'False' }}

                              <form method="POST" action="{!! route('call_queue_extensions.call_queue_extension.updateField', $callQueueExtension->id) !!}" class="editableForm"
                                  accept-charset="UTF-8">
                                  <input type="hidden" name="allow_diversion" type="hidden" value="0">
                                  @csrf
                                  @method('PUT')
                                  <div class="toggle">
                                      <label>
                                          <input type="checkbox" name="join_empty" value="1"
                                              @if ($callQueueExtension->allow_diversion) checked="checked" @endif
                                              class="editableField"><span class="button-indecator"></span>
                                      </label>
                                  </div>
                              </form>

                          </td>


                          <td>

                              <div class="dropdown">
                                  <form method="POST" action="{!! route('call_queue_extensions.call_queue_extension.destroy', $callQueueExtension->id) !!}" accept-charset="UTF-8"
                                      class="deleteFrm">
                                      @csrf
                                      @method('DELETE')
                                      <a href="#" data-toggle="dropdown"> <i data-feather="more-horizontal"> </i>
                                      </a>
                                      <ul class="dropdown-menu shadow-dropdown action-dropdown-menu"
                                          aria-labelledby="dropdownMenuButton1">
                                          <li><a title="Edit Call Queue Extension #{{ $callQueueExtension->id }}"
                                                  class="dropdown-item btnForm"
                                                  href="{{ route('call_queue_extensions.call_queue_extension.edit', $callQueueExtension->id) }}">
                                                  <i data-feather="edit"></i> {{ __('Edit') }}
                                              </a>
                                          </li>
                                          <li>
                                              <button type="submit" class="dropdown-item btn btn-link"
                                                  title="Delete User"
                                                  onclick="return confirm('{{ __('Click Ok to delete Call Queue Extension.') }}')">
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

      {!! $callQueueExtensions->render() !!}
  </div>
