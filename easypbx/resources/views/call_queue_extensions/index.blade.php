@extends('layouts.app')



@section('content')

    @include('partials.message')



    <div class="panel panel-default">


          <div class="panel-heading clearfix">

            <div class="pull-left">
                <h4 class="tile-title">{{ __('Call Queue Extensions') }}</h4>
            </div>

            <div class="btn-group btn-group-sm pull-right" role="group">
                <a href="{{ route('call_queue_extensions.call_queue_extension.create', $call_queue->id) }}" class="btn btn-primary btnForm" title="{{ __('Create New Call Queue Extension') }}">
                    <span class="fa fa-plus" aria-hidden="true"></span>{{ __('Create New Call Queue Extension') }}
                </a>
            </div>

        </div>
        
     
        <div class="panel-body panel-body-with-table">
            <div class="table-responsive">

   <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">

    <div class="row">
        <div class="col-sm-12 col-md-4">
            <div class="dataTables_length py-2" id="sampleTable_length">
                <div class="input-group">
                <div class="input-group-prepend"> {!! Form::select('crud_per_page',config('enums.pagination_count'),app('request')->input('per_page'),['id'=>"crud_per_page",'class' => 'form-control form-control-sm ','style'=>'width:50px']) !!} 

                </div>&nbsp;

                {!! Form::select('filter_group',$call_queues, $call_queue->id,['id'=>"filter_group",'data-live-search'=>"true",'class' => 'form-control form-control-sm selectpicker']) !!}
                

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
                    <button type="button" class="btn btn-outline-secondary " data-toggle="modal" data-target="#bulkActionModal">{{ __('Bulk Actions') }}</button>
                </div>
                
                <button id="btn-refreash" type="button" class="btn btn-outline-secondary " data-toggle="tooltip" data-placement="top" title="{{ __('Reload') }}"><span><i class="fa fa-refresh"></i></span></button>

            </div>

        </div>

    </div>

<div id="crud_contents">
     @include ('call_queue_extensions.table', ['callQueueExtensions' => $callQueueExtensions,])
</div>  
  


</div>
 </div>

      
     
    
    </div>
   </div>
   

<!-- Modal for bulk actions-->
<div class="modal fade" id="bulkActionModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{ __('Bulk Action') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            <form method="POST" action="{!! route('call_queue_extensions.call_queue_extension.bulk') !!}" class="editableForm" id="massActionFrm" accept-charset="UTF-8">
             {{ csrf_field() }}    
            <input name="_method" type="hidden" value="PUT"> 
            <div class="form-check">
            <label class="form-check-label">
            <input class="form-check-input" type="checkbox" name="mass_delete" id="mass_delete" value="1">{{ __('Mass Delete') }}
            </label>
            </div>  
            <div id="bulk_fields">
            
            </div>

            </form>

        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
        <button type="button" class="btn btn-primary" id="mass_submit">{{ __('Save changes') }}</button>
      </div>
    </div>
  </div>
</div>


<!-- Modal for add edit forms-->
<div class="modal fade drawer right-align" id="FormModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{ __('Create New Call Queue Extension') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
        <button type="button" class="btn btn-primary btnSave">{{ __('Save changes') }}</button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('script')
<script src="{{ asset('js/index.js') }}"></script>

@endpush