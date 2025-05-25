  <div class="row">
      <div class="col-sm-12">

          <table class="table table-striped ">
              <thead>
                  <tr>
                      <th><input type="checkbox" name="checkAll" id="checkAll"></th>
                      <th class="sortable" sort-by="title">{{ __('Title') }}</th>
                      <th>{{ __('No of SMS') }}</th>
                      <th>{{ __('Content') }}</th>
                      
                      <th></th>
                  </tr>
              </thead>
              <tbody>
                  @foreach ($smsObjects as $sms)
                      <tr>
                          <td><input type="checkbox" name="ids[]" class="idRow" value="{{ $sms->id }}"></td>
                          
                          <td>{{ optional($sms)->title }}</td>
                          <td>{{ optional($sms)->sms_count }}</td>
                          <td>{{ optional($sms)->content }}</td>

                          <td>
                              <div class="dropdown">
                                  <form method="POST" action="{!! route('sms.sms.destroy', $sms->id) !!}" accept-charset="UTF-8"
                                      class="deleteFrm">
                                      @csrf
                                      @method('DELETE')
                                      <a href="#" data-toggle="dropdown"> <i data-feather="more-horizontal"> </i>
                                      </a>
                                      <ul class="dropdown-menu shadow-dropdown action-dropdown-menu"
                                          aria-labelledby="dropdownMenuButton1">
                                          <li><a title="Edit Sms #{{ $sms->id }}" class="dropdown-item btnForm"
                                                  href="{{ route('sms.sms.edit', $sms->id) }}">
                                                  <i data-feather="edit"></i> {{ __('Edit') }}
                                              </a>
                                          </li>
                                          <li>
                                              <button type="submit" class="dropdown-item btn btn-link"
                                                  title="Delete User"
                                                  onclick="return confirm('{{ __('Click Ok to delete Sms.') }}')">
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

      {!! $smsObjects->render() !!}
  </div>
