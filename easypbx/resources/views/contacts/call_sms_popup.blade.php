<div class="callnow-popup">
    <span class="tel_no">{{ $tel_no }}

        <div class="sms_box">
            <div class="btn-group" role="group" aria-label="Basic outlined example">
                <button tel="{{ $tel_no }}" type="button" class="btn btn-sm btn-outline-primary send-sms-now">
                    <i class="fa fa-envelope-o" aria-hidden="true"></i>
                </button>    
                <button tel="{{ $tel_no }}" type="button" class="btn btn-sm btn-outline-primary call-now">
                    <i class="fa fa-phone" aria-hidden="true"></i>
                </button>
                
            </div>
        </div>
    </span>
</div>