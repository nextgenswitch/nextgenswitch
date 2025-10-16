
<div class="notice @if (!config('licence.uid') != '') d-none @endif">
    <div class="modal-body">
        <div class="alert alert-success" role="alert">
            <h4 class="alert-heading">Your NextGenswitch license has been activated!</h4>
        </div>
        @if (config('licence.email'))
            <p class="card-title px-md-5">Email Address: {{ config('licence.email') }} <br />
            Call Limit: {{ config('licence.call_limit') }} <br />
            Multi Tenancy: {{ (config('licence.multi_tenant') == 1)?"Yes":"No"  }}</p>
        @endif
           
      

    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-primary reactive-licence licence-toggle-form" >Change License Key</button>
        
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>
</div>
