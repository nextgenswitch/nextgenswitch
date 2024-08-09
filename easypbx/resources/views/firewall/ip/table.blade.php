  <div class="row">
      <div class="col-sm-12">

          <table class="table table-striped ">
              <thead>
                  <tr>

                      <th width="30%">{{ __('Title') }}</th>
                      <th width="30%">{{ __('Ip') }}</th>
                      <th width="30%">{{ __('Subnet') }}</th>

                      <th width="10%">
                        <a href="{{ route('ip_black_lists.ip_black_list.create') }}" class="btn btn-primary btn-sm btnForm"
                              title="{{ __('Add new IP into blacklist') }}">
                              <span class="fa fa-plus" aria-hidden="true"></span>{{ __('Add IP') }}
                          </a>
                      </th>
                  </tr>
              </thead>
              <tbody>
                  @foreach ($ipBlackLists as $ipBlackList)
                      <tr>

                          <td>{{ optional($ipBlackList)->title }}</td>
                          <td>{{ $ipBlackList->ip }}</td>
                          <td>{{ $ipBlackList->subnet }}</td>

                          <td>

                              <div class="dropdown">
                                  <form method="POST" action="{!! route('ip_black_lists.ip_black_list.destroy', $ipBlackList->id) !!}" accept-charset="UTF-8"
                                      class="deleteFrm">
                                      @csrf
                                      @method('DELETE')
                                      <a href="#" data-toggle="dropdown"> <i data-feather="more-horizontal"> </i>
                                      </a>
                                      <ul class="dropdown-menu shadow-dropdown action-dropdown-menu"
                                          aria-labelledby="dropdownMenuButton1">
                                          <li><a title="Edit Ip Black List #{{ $ipBlackList->id }}"
                                                  class="dropdown-item btnForm"
                                                  href="{{ route('ip_black_lists.ip_black_list.edit', $ipBlackList->id) }}">
                                                  <i data-feather="edit"></i> {{ __('Edit') }}
                                              </a>
                                          </li>
                                          <li>
                                              <button type="submit" class="dropdown-item btn btn-link"
                                                  title="Delete User"
                                                  onclick="return confirm('{{ __('Click Ok to delete Ip Black List.') }}')">
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

      {!! $ipBlackLists->render() !!}
  </div>
