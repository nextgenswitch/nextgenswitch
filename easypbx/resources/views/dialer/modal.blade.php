<!-- Modal -->


<div id="dialerModal" class="popover popover-x popover-secondary">
    <div class="arrow"></div>
    <div class="popover-header popover-title"><button type="button" class="close" data-dismiss="popover-x">&times;</button>WebDialer</div>
    <div class="popover-body popover-content" id="dialer_ajax_content"></div>
</div>

<div id="popoverForward" class="popover popover-x popover-secondary">          
    <div class="popover-content">
    <input type="text" class="form-control mr-1" placeholder="Enter forward number to dial">
    </div>
</div>

@include('dialer.script')
