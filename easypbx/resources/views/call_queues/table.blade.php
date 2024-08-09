  <div class="row">
      <div class="col-sm-12">

          <table class="table table-striped ">
              <thead>
                  <tr>
                      <th><input type="checkbox" name="checkAll" id="checkAll"></th>
                      <th class="sortable" sort-by="name">{{ __('Name') }}</th>
                      <th>{{ __('Code') }}</th>
                      <th class="sortable" sort-by="strategy">{{ __('Strategy') }}</th>
                      <th class="sortable" sort-by="join_empty">{{ __('Join Empty') }}</th>
                      <th class="sortable" sort-by="leave_when_empty">{{ __('Leave When Empty') }}</th>
          
                      <th class="sortable" sort-by="queue_callback">{{ __('Ring Busy') }}</th>
                
                      <th>{{ __('Record') }}</th>
                      
                      <th></th>
                  </tr>
              </thead>
              <tbody>
                  @foreach ($callQueues as $callQueue)
                      <tr>
                          <td><input type="checkbox" name="ids[]" class="idRow" value="{{ $callQueue->id }}"></td>
                          
                          <td>{{ $callQueue->name }}</td>
                          <td>{{ optional($callQueue->extension)->code }}</td>
                          <td>{{ config('enums.ring_strategy')[$callQueue->strategy] }}</td>
                          
                          <td>
                            {{ ($callQueue->join_empty) ? 'Yes' : 'No' }}

                         <form method="POST" action="{!! route('call_queues.call_queue.updateField', $callQueue->id) !!}" class="editableForm" accept-charset="UTF-8">
                            <input type="hidden" name="join_empty" type="hidden" value="0">
                            @csrf
                            @method('PUT')
                             <div class="toggle">
                              <label>
                                <input type="checkbox" name="join_empty" value="1"
                                @if ($callQueue->join_empty) checked="checked" @endif
                                class="editableField"><span class="button-indecator"></span>
                              </label>
                            </div>
                            </form>

                        </td>

                        <td>
                            {{ ($callQueue->leave_when_empty) ? 'Yes' : 'No' }}

                         <form method="POST" action="{!! route('call_queues.call_queue.updateField', $callQueue->id) !!}" class="editableForm" accept-charset="UTF-8">
                            <input type="hidden" name="leave_when_empty" type="hidden" value="0">
                            @csrf
                            @method('PUT')
                             <div class="toggle">
                              <label>
                                <input type="checkbox" name="leave_when_empty" value="1"
                                @if ($callQueue->leave_when_empty) checked="checked" @endif
                                class="editableField"><span class="button-indecator"></span>
                              </label>
                            </div>
                            </form>

                        </td>


    
                          
                          
                          <td>
                            {{ ($callQueue->queue_callback) ? 'Active' : 'Disabled' }}

                         <form method="POST" action="{!! route('call_queues.call_queue.updateField', $callQueue->id) !!}" class="editableForm" accept-charset="UTF-8">
                            <input type="hidden" name="ring_busy_agent" type="hidden" value="0">
                            @csrf
                            @method('PUT')
                             <div class="toggle">
                              <label>
                                <input type="checkbox" name="ring_busy_agent" value="1"
                                @if ($callQueue->ring_busy_agent) checked="checked" @endif
                                class="editableField"><span class="button-indecator"></span>
                              </label>
                            </div>
                            </form>

                        </td>

                          
                          

                          <td>
                            {{ ($callQueue->record) ? 'Yes' : 'No' }}

                         <form method="POST" action="{!! route('call_queues.call_queue.updateField', $callQueue->id) !!}" class="editableForm" accept-charset="UTF-8">
                            <input type="hidden" name="record" type="hidden" value="0">
                            @csrf
                            @method('PUT')
                             <div class="toggle">
                              <label>
                                <input type="checkbox" name="record" value="1"
                                @if ($callQueue->record) checked="checked" @endif
                                class="editableField"><span class="button-indecator"></span>
                              </label>
                            </div>
                          </form>

                        </td>
                          
                          
                          

                          <td>

                              <div class="dropdown">
                                  <form method="POST" action="{!! route('call_queues.call_queue.destroy', $callQueue->id) !!}" accept-charset="UTF-8"
                                      class="deleteFrm">
                                      @csrf
                                      @method('DELETE')
                                      <a href="#" data-toggle="dropdown"> <i data-feather="more-horizontal"> </i>
                                      </a>
                                      <ul class="dropdown-menu shadow-dropdown action-dropdown-menu"
                                          aria-labelledby="dropdownMenuButton1">
                                          <li><a title="Edit Call Queue #{{ $callQueue->id }}"
                                                  class="dropdown-item"
                                                  href="{{ route('call_queues.call_queue.edit', $callQueue->id) }}">
                                                  <i data-feather="edit"></i> {{ __('Edit') }}
                                              </a>
                                          </li>

                                      <!--     <li><a 
                                            class="dropdown-item"
                                            href="{{ route('call_queue_extensions.call_queue_extension.index', $callQueue->id) }}">
                                            <i data-feather="link"></i> {{ __('Queue Extension') }}
                                        </a>
                                    </li> -->


                                          <li>
                                              <button type="submit" class="dropdown-item btn btn-link"
                                                  title="Delete User"
                                                  onclick="return confirm('{{ __('Click Ok to delete Call Queue.') }}')">
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

      {!! $callQueues->render() !!}
  </div>
