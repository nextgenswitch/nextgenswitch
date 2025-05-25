  <div class="row">
      <div class="col-sm-12">

          <table class="table table-striped ">
              <thead>
                  <tr>
                      <th><input type="checkbox" name="checkAll" id="checkAll"></th>

                      <th class="sortable" sort-by="name">{{ __('Name') }}</th>
                      <th>{{ __('Username') }}</th>
                        <th>{{ __('Peer') }}</th>
                        <th>{{ __('Record') }}</th>
                      <th></th>
                  </tr>
              </thead>
              <tbody>
                  @foreach ($trunks as $trunk)
                      <tr>
                          <td><input type="checkbox" name="ids[]" class="idRow" value="{{ $trunk->id }}"></td>

                          <td>{{ $trunk->name }}</td>

                          <td>{{ optional($trunk->sipUser)->username }}</td>
                          <td>{{ optional($trunk->sipUser)->peer ? __('Yes') : 'No' }}</td>
                          <td>{{ optional($trunk->sipUser)->record ? __('Yes') : 'No' }}</td>
                          <td>

                              <div class="dropdown">
                                  <form method="POST" action="{!! route('trunks.trunk.destroy', $trunk->id) !!}" accept-charset="UTF-8"
                                      class="deleteFrm">
                                      @csrf
                                      @method('DELETE')
                                      <a href="#" data-toggle="dropdown"> <i data-feather="more-horizontal"> </i>
                                      </a>
                                      <ul class="dropdown-menu shadow-dropdown action-dropdown-menu"
                                          aria-labelledby="dropdownMenuButton1">
                                          <li><a title="Edit Trunk #{{ $trunk->id }}" class="dropdown-item btnForm"
                                                  href="{{ route('trunks.trunk.edit', $trunk->id) }}">
                                                  <i data-feather="edit"></i> {{ __('Edit') }}
                                              </a>
                                          </li>
                                          <li>
                                              <button type="submit" class="dropdown-item btn btn-link"
                                                  title="Delete User"
                                                  onclick="return confirm('{{ __('Click Ok to delete Trunk.') }}')">
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

      {!! $trunks->render() !!}
  </div>
