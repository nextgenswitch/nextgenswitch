  <div class="row">
      <div class="col-sm-12">

          <table class="table table-striped ">
              <thead>
                  <tr>
                      <th><input type="checkbox" name="checkAll" id="checkAll"></th>
                      <th class="sortable" sort-by="name">{{ __('Name') }}</th>
                      
                      <th>{{ __('Start At') }}</th>
                      <th>{{ __('End At') }}</th>
                      <th>{{ __('End Date') }}</th>
                      <th>{{ __('Status') }}</th>
                      
                      <th></th>
                  </tr>
              </thead>
              <tbody>
                  @foreach ($dialerCampaigns as $dialerCampaign)
                      <tr>
                          <td><input type="checkbox" name="ids[]" class="idRow" value="{{ $dialerCampaign->id }}"></td>
                          <td>{{ $dialerCampaign->name }}</td>
                          
                          <td>{{ $dialerCampaign->start_at }}</td>
                          <td>{{ $dialerCampaign->end_at }}</td>
                          <td>{{ $dialerCampaign->end_date }}</td>
                          <td>

                          <span class="badge badge-pill badge-light"><a href="{{ route('dialer_campaigns.dialer_campaign.run', $dialerCampaign->id ) }}"> {{ config('enums.campaign_status')[$dialerCampaign->status] }} </a></span> 
                          </td>
                          <td>
                              <div class="dropdown">
                                  <form method="POST" action="{!! route('dialer_campaigns.dialer_campaign.destroy', $dialerCampaign->id) !!}" accept-charset="UTF-8"
                                      class="deleteFrm">
                                      @csrf
                                      @method('DELETE')
                                      <a href="#" data-toggle="dropdown"> <i data-feather="more-horizontal"> </i>
                                      </a>
                                      <ul class="dropdown-menu shadow-dropdown action-dropdown-menu"
                                          aria-labelledby="dropdownMenuButton1">
                                          @if($dialerCampaign->status == 0)
                                            <li><a class="dropdown-item"  href="{{ route('dialer_campaigns.dialer_campaign.run', $dialerCampaign->id) }}">
                                                    <i class="fa fa-play"></i> {{ __('Start Dialing') }}
                                                </a>
                                            </li>
                                          @endif
                                          
                                          <li><a class="dropdown-item" href="{{ route('dialer_campaign_calls.dialer_campaign_call.index', ['id' => $dialerCampaign->id]) }}">
                                                  <i class="fa fa-history"></i> {{ __('History') }}
                                              </a>
                                          </li>
                                        
                                          
                                          <li><a title="Edit  Campaign #{{ $dialerCampaign->id }}"
                                                  class="dropdown-item"
                                                  href="{{ route('dialer_campaigns.dialer_campaign.edit', $dialerCampaign->id) }}">
                                                  <i data-feather="edit"></i> {{ __('Edit') }}
                                              </a>
                                          </li>
                                          <li><a title="Clone  Campaign #{{ $dialerCampaign->id }}"
                                                  class="dropdown-item"
                                                  href="{{ route('dialer_campaigns.dialer_campaign.clone', $dialerCampaign->id) }}">
                                                  <i data-feather="copy"></i> {{ __('Clone') }}
                                              </a>
                                          </li>
                                          @if($dialerCampaign->status < 2)
                                          <li>
                                              <button type="submit" class="dropdown-item btn btn-link"
                                                  onclick="return confirm('{{ __('Click Ok to delete Dialer Campaign.') }}')">
                                                  <i data-feather="trash"></i> {{ __('Delete') }}
                                              </button>
                                          </li>
                                          @endif

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
      {!! $dialerCampaigns->render() !!}
  </div>
