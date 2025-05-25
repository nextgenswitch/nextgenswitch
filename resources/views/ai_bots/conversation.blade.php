    @if(count($conversations))
        @foreach($conversations as $conversation)
            <!-- Sender Message-->
            @if($conversation->ai_msg)
            <div class="media w-75">
            <div class="media-body">
                <div class="bg-s rounded py-3 px-3">
                <p class="text-small mb-0 text-receive">{{ $conversation->message }}</p>
                </div>
                <p class="small mb-0 text-muted">Ai Assistant <small> {{ $conversation->created_at }} </small></p>
            </div>
            </div>
            @else
            <!-- Reciever Message-->
            <div class="media my-3 w-75 ml-auto">
            <div class="media-body">
                <div class="bg-p rounded py-3 px-3 mb-0">
                <p class="text-small mb-0 text-receive text-muted">{{ $conversation->message }}</p>
                </div>
                <p class="small mb-0 text-muted">Customer <small> {{ $conversation->created_at }} </small></p>
            </div>
            </div>
            @endif
        @endforeach

    @else
    <div class="alert alert-danger" role="alert">
    {{ __(" No conversation available!") }}
</div>    
    
    @endif
    <div class="pt-3"></div>