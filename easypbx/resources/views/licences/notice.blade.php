
<div class="notice @if (!config('licence.uid') != '') d-none @endif">
    <div class="modal-body">
        <div class="alert alert-success" role="alert">
            <h4 class="alert-heading">Your license has been activated!</h4>
          
        </div>
        <div class="row">
           
            <div class="col-lg-3">License Email</div><div class="col-lg-9">{{ config('licence.email') }}</div>
            <div class="col-lg-3">Call limit</div><div class="col-lg-9">{{ config('licence.call_limit') }}</div>
            <div class="col-lg-3">Multitenancy</div><div class="col-lg-9">{{ (config('licence.multi_tenant') == 1)?"Yes":"No"  }}</div>
            <div class="col-lg-12"><a href="javascript:void(0);"
            class="reactive-licence licence-toggle-form"> click here</a> to change your license information.</div>
        </div>

    </div>
    <div class="modal-footer">
      
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>
</div>
