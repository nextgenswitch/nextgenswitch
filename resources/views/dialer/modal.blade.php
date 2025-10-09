<!-- Modal -->


<div id="dialerModal" class="popover popover-x popover-secondary">
    <div class="arrow"></div>
    <div class="popover-header popover-title"><button type="button" class="close" data-dismiss="popover-x">&times;</button>{{ __('LinkToDialer') }}</div>
     
    <div class="popover-body popover-content" id="dialer_ajax_content"></div>
</div>


@include('dialer.script')
