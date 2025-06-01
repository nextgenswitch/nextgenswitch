  <div class="row"><div class="col-sm-12">

                <table class="table table-striped ">
                    <thead>
                        <tr>
                            
                            <th>{{ __('AI Assistant') }}</th>
                            <th>{{ __('Caller ID') }}</th>
                            <th>{{ __('Date') }}</th>

                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($aiAssistantCalls as $call)
                        <tr>
                            
                            <td>{{ $call->ai_assistant->name }}</td>
                            
                            <td>{{ $call->caller_id }}</td>
                            
                            <td>{{ $call->created_at }}</td>
                            
                            <td>
                                  <button type="button" caller_id="{{ $call->caller_id }}" call_id="{{ $call->call_id }}" class="btn btn-sm btn-primary btn-show-ai-conversation">
                                  <i class="fa fa-comments-o"></i> Conversations
                                  </button>
                                
                        
                            </td>
                           
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                </div>            
</div>

<div class="row">
    
{!! $aiAssistantCalls->render() !!}
</div>

