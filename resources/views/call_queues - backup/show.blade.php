@extends('layouts.app')

@section('content')

<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <div class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($title) ? $title : __('Call Queue') }}</h4>
        </div>

        <div class="pull-right">
        
            {!! Form::open([
                'method' =>'DELETE',
                'route'  => ['call_queues.call_queue.destroy', $callQueue->id]
            ]) !!}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('call_queues.call_queue.index') }}" class="btn btn-primary" title="{{ __('Show All Call Queue') }}">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('call_queues.call_queue.create') }}" class="btn btn-success" title="{{ __('Create New Call Queue') }}">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('call_queues.call_queue.edit', $callQueue->id ) }}" class="btn btn-primary" title="{{ __('Edit Call Queue') }}">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    {!! Form::button('<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>', 
                        [   
                            'type'    => 'submit',
                            'class'   => 'btn btn-danger',
                            'title'   => 'Delete Call Queue',
                            'onclick' => "return confirm(" .  __('Click Ok to delete Call Queue.') . ")"
                        ])
                    !!}
                </div>
            {!! Form::close() !!}

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Agent Announcemnet</dt>
            <dd>{{ $callQueue->agent_announcemnet }}</dd>
            <dt>Cid Name Prefix</dt>
            <dd>{{ $callQueue->cid_name_prefix }}</dd>
            <dt>Created At</dt>
            <dd>{{ $callQueue->created_at }}</dd>
            <dt>Description</dt>
            <dd>{{ $callQueue->description }}</dd>
            <dt>Join Announcement</dt>
            <dd>{{ $callQueue->join_announcement }}</dd>
            <dt>Join Empty</dt>
            <dd>{{ $callQueue->join_empty }}</dd>
            <dt>Leave When Empty</dt>
            <dd>{{ $callQueue->leave_when_empty }}</dd>
            <dt>Member Timeout</dt>
            <dd>{{ $callQueue->member_timeout }}</dd>
            <dt>Music On Hold</dt>
            <dd>{{ $callQueue->music_on_hold }}</dd>
            <dt>Organization</dt>
            <dd>{{ optional($callQueue->organization)->name }}</dd>
            <dt>Queue Callback</dt>
            <dd>{{ $callQueue->queue_callback }}</dd>
            <dt>Queue Timeout</dt>
            <dd>{{ $callQueue->queue_timeout }}</dd>
            <dt>Record</dt>
            <dd>{{ $callQueue->record }}</dd>
            <dt>Retry</dt>
            <dd>{{ $callQueue->retry }}</dd>
            <dt>Ring Busy Agent</dt>
            <dd>{{ $callQueue->ring_busy_agent }}</dd>
            <dt>Service Level</dt>
            <dd>{{ $callQueue->service_level }}</dd>
            <dt>Strategy</dt>
            <dd>{{ $callQueue->strategy }}</dd>
            <dt>Timeout Priority</dt>
            <dd>{{ $callQueue->timeout_priority }}</dd>
            <dt>Updated At</dt>
            <dd>{{ $callQueue->updated_at }}</dd>
            <dt>Wrap Up Time</dt>
            <dd>{{ $callQueue->wrap_up_time }}</dd>

        </dl>

    </div>
</div>

@endsection
