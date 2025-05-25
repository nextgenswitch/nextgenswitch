  <div class="row"><div class="col-sm-12">

                <table class="table table-striped ">
                    <thead>
                        <tr>
                            <th><input type="checkbox" name="checkAll" id="checkAll"></th>
                          
                            <th>{{ __('Type') }}</th>
                            <th>{{ __('Code') }}</th>
                            <th>{{ __('Message') }}</th>
                            <th>{{ __('Time') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($notifications as $notification)
                        <tr>
                            <td><input type="checkbox" name="ids[]" class="idRow" value="{{ $notification->id }}"></td>
                            
                            <td >{{ config('enums.notification')[$notification->data->type] }}</td>
                            <td >{{ $notification->data->code }}</td>
                            <td >{{ $notification->data->msg }}</td>
                            <td >{{ $notification->created_at->diffForHumans() }}</td>
                            
                           
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                </div>            
</div>

<div class="row">
    
{!! $notifications->render() !!}
</div>

