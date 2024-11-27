@extends('layouts.app')

@push('css')
<link rel="stylesheet" href="{{ asset('css/audioplayer.css') }}">
<style>
    .timeline {
    list-style-type: none;
    margin: 0;
    padding: 0;
    position: relative
}

.timeline:before {
    content: '';
    position: absolute;
    top: 5px;
    bottom: 5px;
    width: 3px;
    background: #2d353c;
    left: 20%;
    margin-left: -2.5px
}

.timeline>li {
    position: relative;
    min-height: 50px;
    padding: 5px 0
}

.timeline .timeline-time {
    position: absolute;
    left: 0;
    width: 18%;
    text-align: right;
    top: 30px
}

.timeline .timeline-time .date,
.timeline .timeline-time .time {
    display: block;
    font-weight: 600
}

.timeline .timeline-time .date {
    line-height: 16px;
    font-size: 12px
}

.timeline .timeline-time .time {
    line-height: 24px;
    font-size: 18px;
    color: #242a30
}

.timeline .timeline-icon {
    left: 14.9%;
    position: absolute;
    width: 10%;
    text-align: center;
    top: 40px
}

.timeline .timeline-icon a {
    text-decoration: none;
    width: 15px;
    height: 15px;
    display: inline-block;
    border-radius: 20px;
    background: #d9e0e7;
    line-height: 10px;
    color: #fff;
    font-size: 14px;
    border: 5px solid #2d353c;
    transition: border-color .2s linear
}

.timeline .timeline-body {
    margin-left: 23%;
    margin-right: 17%;
    background: #fff;
    position: relative;
    padding: 8px 25px;
    border-radius: 6px
}

.timeline .timeline-body:before {
    content: '';
    display: block;
    position: absolute;
    border: 10px solid transparent;
    border-right-color: #fff;
    left: -20px;
    top: 20px
}

.timeline .timeline-body>div+div {
    margin-top: 15px
}

.timeline .timeline-body>div+div:last-child {
    margin-bottom: -20px;
    padding-bottom: 20px;
    border-radius: 0 0 6px 6px
}

.timeline-header {
    padding-bottom: 10px;
    border-bottom: 1px solid #e2e7eb;
    line-height: 30px
}

.timeline-header .userimage {
    float: left;
    width: 34px;
    height: 34px;
    border-radius: 40px;
    overflow: hidden;
    margin: -2px 10px -2px 0
}

.timeline-header .username {
    font-size: 16px;
    font-weight: 600
}

.timeline-header .username,
.timeline-header .username a {
    color: #2d353c
}

.timeline img {
    max-width: 100%;
    display: block
}

.timeline-content {
    letter-spacing: .25px;
    line-height: 18px;
    font-size: 13px
}

.timeline-content:after,
.timeline-content:before {
    content: '';
    display: table;
    clear: both
}

.timeline-title {
    margin-top: 0
}

.timeline-footer {
    background: #fff;
    border-top: 1px solid #e2e7ec;
    padding-top: 15px
}

.timeline-footer a:not(.btn) {
    color: #575d63
}

.timeline-footer a:not(.btn):focus,
.timeline-footer a:not(.btn):hover {
    color: #2d353c
}

.timeline-likes {
    color: #6d767f;
    font-weight: 600;
    font-size: 12px
}

.timeline-likes .stats-right {
    float: right
}

.timeline-likes .stats-total {
    display: inline-block;
    line-height: 20px
}

.timeline-likes .stats-icon {
    float: left;
    margin-right: 5px;
    font-size: 9px
}

.timeline-likes .stats-icon+.stats-icon {
    margin-left: -2px
}

.timeline-likes .stats-text {
    line-height: 20px
}

.timeline-likes .stats-text+.stats-text {
    margin-left: 15px
}

.timeline-comment-box {
    background: #f2f3f4;
    margin-left: -25px;
    margin-right: -25px;
    padding: 20px 25px
}

.timeline-comment-box .user {
    float: left;
    width: 34px;
    height: 34px;
    overflow: hidden;
    border-radius: 30px
}

.timeline-comment-box .user img {
    max-width: 100%;
    max-height: 100%
}

.timeline-comment-box .user+.input {
    margin-left: 44px
}

.lead {
    margin-bottom: 20px;
    font-size: 21px;
    font-weight: 300;
    line-height: 1.4;
}

.text-danger, .text-red {
    color: #ff5b57!important;
}

.accordion .card-header:after {
    font-family: 'FontAwesome';  
    content: "\f068";
    float: right; 
}
.accordion .card-header.collapsed:after {
    /* symbol for "collapsed" panels */
    content: "\f067"; 
}

</style>

@endpush

@section('content')

<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <div class="row">
            <div class="col-md-6 col-lg-3">
                <div class="text-center">
                    <b>Customer Name</b>
                    <p>{{ optional($ticket)->name }}</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="text-center">
                    <b>Phone</b>
                    <p>{{ optional($ticket)->phone }}</p>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="text-center">
                    <b>Issue Date</b>
                    <p>{{ optional($ticket)->created_at }}</p>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="text-center">
                    <b>Assigned User</b>
                    @php 
                        $user = isset($ticket->user_id) ? $ticket->user : null;
                    @endphp  
                    <p>{{ optional($user)->name }}</p>
                </div>
            </div>

        </div>
    </div>

    <div class="container">
        <div id="accordion" class="accordion mb-3">
            <div class="card mb-0">
                <div class="card-header collapsed" data-toggle="collapse" href="#collapseOne">
                    <a class="card-title">
                        {{ $ticket->subject }}
                    </a>
                </div>
                <div id="collapseOne" class="card-body collapse" data-parent="#accordion" >
                    <p>
                    {{ $ticket->description }}
                    </p>
                </div>

                <div class="card-header collapsed d-none" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                    <a class="card-title">
                    Attachments
                    </a>
                </div>
                <div id="collapseTwo" class="card-body collapse" data-parent="#accordion" >
                    <p>
                        
                    </p>
                </div>
            </div>
        </div>
        @if($ticket->record)
        <audio class="d-none" preload="auto" controls>
			<source src="{{ asset('storage/' . $ticket->record ) }}">
		</audio>
        @endif
    </div>

    <div class="panel-body pt-5">
        @if($ticket->followUps)
        <ul class="timeline">
            @foreach($ticket->followUps as $flup)
            <li>
                
                <div class="timeline-time">
                    <span class="date">{{ $flup->created_at->format('d M Y') }}</span>
                    <span class="time">{{ $flup->created_at->format('H:i:s') }}</span>
                </div>
                
                <div class="timeline-icon">
                    <a href="javascript:;">&nbsp;</a>
                </div>
                
                <div class="timeline-body">
                    <div class="timeline-header">
                        <span class="userimage"><img src="{{ asset('images/profile.png') }}" alt=""></span>
                        <span class="username"><a href="javascript:;">{{ $flup->user->name }}</a> <small></small></span>
                        
                    </div>
                    <div class="timeline-content">
                        <p>
                            {{ $flup->comment }}
                        </p>
                    </div>
                </div>
                
            </li>
            @endforeach
        </ul>

        @endif

        @if($ticket->status == 3)
            <span class="badge badge-danger"> <i class="fa fa-check"></i> Closed</span>
        @endif

        @if($ticket->status < 3 && $ticket->user_id == auth()->id())
            <form action="{{ route('tickets.follow_up.store', $ticket->id) }}" class="p-md-3" method="post">
                @csrf
                <div class="form-group">
                    <textarea name="comment" id="comment" placeholder="Write your commnet here..." class="form-control"></textarea>
                </div>
                <button class="btn btn-primary" type="submit">Submit</button>
                
            </form>
        @endif

        <a href="{{ route('tickets.ticket.index') }}" class="btn btn-sm btn-outline-primary ml-3">All Tickets</a>
    </div>
</div>



@endsection

@push('script')
<script src="{{ asset('js/audioplayer.js') }}"></script>
<script>
    $(function() {
        $('audio').audioPlayer();
    });
</script>
@endpush