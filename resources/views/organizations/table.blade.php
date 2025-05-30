  <div class="row">
      <div class="col-sm-12">

          <table class="table table-striped ">
              <thead>
                  <tr>
                      <th><input type="checkbox" name="checkAll" id="checkAll"></th>

                      <th class="sortable" sort-by="name">{{ __('Name') }}</th>
                      <th class="sortable" sort-by="domain">{{ __('Domain') }}</th>
                      <th class="sortable" sort-by="contact_no">{{ __('Contact No') }}</th>
                      <th class="sortable" sort-by="email">{{ __('Email') }}</th>
                      <th>{{ __('Call Limit') }}</th>
                      <th>{{ __('Max Extensions') }}</th>
                      <th class="sortable" sort-by="expire_date">{{ __('Expire Date') }}</th>
                      <!-- <th>{{ __('Plan') }}</th> -->

                      <th></th>
                  </tr>
              </thead>
              <tbody>
                  @foreach ($organizations as $organization)
                      <tr>
                          <td><input type="checkbox" name="ids[]" class="idRow" value="{{ $organization->id }}"></td>


                          <td>{{ $organization->name }}
                              @if ($organization->is_primary)
                                  <small><span class="badge badge-success">{{ __('Primary') }}</span></small>
                              @endif
                          </td>
                          <td>{{ $organization->domain }}</td>
                          <td>{{ $organization->contact_no }}</td>
                          <td>{{ $organization->email }}</td>
                          <td><span
                                  class="badge badge-primary">{{ $organization->call_limit ? $organization->call_limit : __('Unlimited') }}</span>
                          </td>
                          <td><span
                                  class="badge badge-secondary">{{ $organization->max_extension ? $organization->max_extension : __('Unlimited') }}</span>
                          </td>
                          <!-- <td>{{ optional($organization->plan)->name }}</td> -->

                          <td>

                              @if ($organization->expire_date && $organization->expire_date < now())
                                  <span class="badge badge-danger"> {{ __('Expired') }} </span>
                              @elseif($organization->expire_date && $organization->expire_date < now()->addDays(7))
                                  <span class="badge badge-warning"> {{ __('Expiring Soon') }} </span>
                              @else
                                  <span class="badge badge-success"> {{ __('Active') }} </span>
                              @endif


                          </td>

                          <td>

                              <div class="dropdown">
                                  <form method="POST" action="{!! route('organizations.organization.destroy', $organization->id) !!}" accept-charset="UTF-8"
                                      class="deleteFrm">
                                      @csrf
                                      @method('DELETE')
                                      <a href="#" data-toggle="dropdown"> <i data-feather="more-horizontal"> </i>
                                      </a>
                                      <ul class="dropdown-menu shadow-dropdown action-dropdown-menu"
                                          aria-labelledby="dropdownMenuButton1">
                                          <li><a title="Edit Organization #{{ $organization->id }}"
                                                  class="dropdown-item btnForm"
                                                  href="{{ route('organizations.organization.edit', $organization->id) }}">
                                                  <i data-feather="edit"></i> {{ __('Edit') }}
                                              </a>
                                          </li>
                                          <li>
                                              <button type="submit" class="dropdown-item btn btn-link"
                                                  title="Delete User"
                                                  onclick="return confirm('{{ __('Click Ok to delete Organization.') }}')">
                                                  <i data-feather="trash"></i> {{ __('Delete') }}
                                              </button>
                                          </li>

                                          <li><a title="Login to Organization" class="dropdown-item"
                                                  href="{{ route('organizations.organization.login', $organization->id) }}"
                                                  onclick="return confirm('Are you sure you would like to login to this organization?');">
                                                  <i data-feather="log-in"></i> {{ __('Login') }}
                                              </a>
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

      {!! $organizations->render() !!}
  </div>
