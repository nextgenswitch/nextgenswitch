@extends('layouts.app')

@push('css')
<style>


#aiConversationModal .bg-p {
    background-color: #0e0e23;
}



#aiConversationModal .bg-s {
    background-color: #e2e2e2;
}

#aiConversationModal .bg-i {
    background-color: #f8f9fa!important;
}

#aiConversationModal .text-receive {
    color: #676463;
}

#aiConversationModal .text-small {
  font-size: 1rem;
}

#aiConversationModal .text-muted {
    color: #e2e2e2;
}

#aiConversationModal .btn-outline-primary {
  color: #676463;
  border-color: #676463;
}
#aiConversationModal .messages-box,
#aiConversationModal .chat-box {
  margin: 10px 16px;
  height: 100%;
  padding-bottom: 40px !important;
}

#aiConversationModal .rounded-lg {
  border-radius: 0.5rem;
}



</style>
@endpush

@section('content')

    @if(Session::has('success_message'))
        <div class="alert alert-success">
            <span class="glyphicon glyphicon-ok"></span>
            {!! session('success_message') !!}

            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>

        </div>
    @endif



    <div class="panel panel-default">


          <div class="panel-heading clearfix">

            <div class="pull-left">
                <h4 class="tile-title">{{ __('AI Assistant Calls') }}</h4>
            </div>

        </div>
        
     
        <div class="panel-body panel-body-with-table">
            <div class="table-responsive">

   <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">

    <div class="row">
        <div class="col-sm-12 col-md-4">
            <div class="dataTables_length py-2" id="sampleTable_length">
                <div class="input-group">
                <div class="input-group-prepend"> {!! Form::select('crud_per_page',config('enums.pagination_count'),app('request')->input('per_page'),['id'=>"crud_per_page",'class' => 'form-control form-control-sm ','style'=>'width:70px']) !!} 

                </div>&nbsp;

                <!--  {!! Form::select('filter_group',[],null,['placeholder'=>'Any Contact Group','id'=>"filter_group",'class' => 'form-control form-control-sm ']) !!}   -->
                <input type="search" name="search" id="search" value="{{ app('request')->input('q') }}" class="app-search__input form-control form-control-sm" placeholder="{{ __('Search') }}">
                    

                <div class="input-group-append">
                  <button class="btn btn-sm btn-secondary" type="button" id="btnSearch">
                    <i class="fa fa-search"></i>
                  </button>
                </div>

            </div>
        </div>
     </div>
        

       <div class="col-sm-12 col-md-8 text-md-right table-toolbar-right justify-content-sm-start justify-content-md-end">
            <div class="btn-group btn-group-sm py-2" role="group" aria-label="Button group with nested dropdown">
              
                <div class="btn-group btn-group-sm" role="group">
                    <button id="btnGroupDrop1" type="button" class="btn  dropdown-toggle btn-outline-secondary" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      {{ __('Export') }}
                    </button>
                    <div class="dropdown-menu shadow-dropdown" aria-labelledby="btnGroupDrop1">
                      <a class="dropdown-item" href="#" id="csvD">{{ __('CSV') }}</a>
                      <a class="dropdown-item" href="#" id="printTable">{{ __('Print') }}</a>
                    </div>
                    
                </div>
                
                
                <div id="sampleTable_filter" class="dataTables_filter btn-group btn-group-sm">
            
                    <button id="btnFilter" type="button" class="btn btn-outline-secondary " data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" ><span data-toggle="tooltip" data-placement="left" title="{{ __('Filter By') }}"><i class="fa fa-filter"></i></span></button>
             
                    <div class="dropdown-menu shadow-dropdown" aria-labelledby="btnFilter">
                            <a class="dropdown-item" href="{!! route('ai_bots.ai_bot.ai_assistant_calls') !!}">{{ __('All') }}</a>
                           
                            @foreach($aiList as $k => $v)
                            <div class="dropdown-divider"></div>     
                            <a class="dropdown-item" href="{!! route('ai_bots.ai_bot.ai_assistant_calls') !!}?filter=ai_assistant_id:{{ $k }}">{{ $v }}</a>
                            @endforeach
                    </div>
                </div>

                <button id="btn-refreash" type="button" class="btn btn-outline-secondary " data-toggle="tooltip" data-placement="top" title="{{ __('Reload') }}"><span><i class="fa fa-refresh"></i></span></button>

            </div>

        </div>

    </div>

<div id="crud_contents">
     @include ('ai_bots.ai_assis_call_table', ['aiAssistantCalls' => $aiAssistantCalls])
</div>  
  


</div>
 </div>

      
     
    
    </div>
   </div>
   

<!-- Modal for bulk actions-->
<div class="modal fade" id="aiConversationModal">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="aiConversationModalLabel">{{ __('Caller ID #') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <div class="px-0 py-0 chat-box bg-white" id="conversation_contents">


        
      </div>
      </div>
    </div>
  </div>
</div>



@endsection


@push('script')
    <script src="{{ asset('js/index.js') }}"></script>

    <script>
        $(document).ready(function() {

			   $("#crud_contents").on("click", ".btn-show-ai-conversation", function(e){
          e.preventDefault();
          
          var call_id = $(this).attr('call_id');
          var caller_id = $(this).attr('caller_id');

          $("#aiConversationModalLabel").text("Caller ID # " + caller_id);
          
          $.get("/ai_assistants/ai_conversations/" + call_id, function(res){
              console.log(res);
              $("#conversation_contents").html(res);
              $("#aiConversationModal").modal();
          })
          
         })

        })
    </script>
@endpush