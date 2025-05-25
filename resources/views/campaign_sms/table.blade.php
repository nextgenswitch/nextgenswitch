  <div class="row">
      <div class="col-sm-12">

          <table class="table table-striped ">
              <thead>
                  <tr>
                      <th class="sortable" sort-by="tel_no">{{ __('From') }}</th>
                      <th class="sortable" sort-by="status">{{ __('To') }}</th>
                      <th class="sortable" sort-by="status">{{ __('Body') }}</th>
                      <th>{{ __('Status') }}</th>
                  </tr>
              </thead>
              <tbody>
                  @foreach ($campaignSms as $sms)
                      <tr>
                          <td>{{ $sms->smsHistroy->from }}</td>
                          <td>{{ \Illuminate\Support\Str::limit($sms->contact, 60) }}</td>
                          <td>{{ $sms->smsHistroy->body }}</td>
                          <td>{{ $sms->status }}</td>
                      </tr>
                  @endforeach
              </tbody>
          </table>

      </div>
  </div>

  <div class="row">

      {!! $campaignSms->render() !!}
  </div>
