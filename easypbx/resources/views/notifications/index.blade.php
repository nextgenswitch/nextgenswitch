@extends('layouts.app')

@section('title', __('Notifications'))

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
                <h4 class="tile-title">{{ __('Notifications') }}</h4>
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
                      <a class="dropdown-item" href="#" id="csvD">{{ __('Csv') }}</a>
                      <a class="dropdown-item" href="#" id="printTable">{{ __('Print') }}</a>
                    </div>
                    <button type="button" class="btn btn-outline-secondary " data-toggle="modal" data-target="#bulkActionModal">{{ __('Bulk Action') }}</button>
                </div>
                
                
                <div id="sampleTable_filter" class="dataTables_filter btn-group btn-group-sm">
            
                    <button id="btnFilter" type="button" class="btn btn-outline-secondary " data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" ><span data-toggle="tooltip" data-placement="left" title="Filter By"><i class="fa fa-filter"></i></span></button>
             
                    <div class="dropdown-menu shadow-dropdown" aria-labelledby="btnFilter">
                            <a class="dropdown-item" href="{!! route('notifications.notification.index') !!}">{{ __('All') }}</a>
                           <div class="dropdown-divider"></div>
                          <!--<a class="dropdown-item" href="{!! route('notifications.notification.index') !!}?filter=status:0">Pending</a> -->
                          
                          
                    </div>
                </div>

                <button id="btn-refreash" type="button" class="btn btn-outline-secondary " data-toggle="tooltip" data-placement="top" title="{{ __('Reload') }}"><span><i class="fa fa-refresh"></i></span></button>

            </div>

        </div>

    </div>

<div id="crud_contents">
     @include ('notifications.table', ['notifications' => $notifications,])
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
            <form method="POST" action="{!! route('notifications.notification.bulk') !!}" class="editableForm" id="massActionFrm" accept-charset="UTF-8">
             {{ csrf_field() }}    
            <input name="_method" type="hidden" value="PUT"> 
            <div class="form-check">
            <label class="form-check-label">
            <input class="form-check-input" type="checkbox" name="mass_delete" id="mass_delete" value="1">{{ __('Mass Delete') }}
            </label>
            </div>  
            <div id="bulk_fields">
            <hr>      
            <!--<div class="form-group">
                    <label for="user_status">Set status</label>
                    {!! Form::select('status',[],null,['class' => 'form-control selectpicker']) !!}
                    
            </div>  -->
            </div>

            </form>

        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
        <button type="button" class="btn btn-primary" id="mass_submit">{{ __('Save Changes') }}</button>
      </div>
    </div>
  </div>
</div>


@endsection

@push('script')
<script src="{{ asset('js/index.js') }}"></script>

@endpush
