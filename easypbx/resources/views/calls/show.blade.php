@extends('layouts.app')

@section('content')

<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <div class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($title) ? $title : __('Call') }}</h4>
        </div>

        <div class="pull-right">
        
            {!! Form::open([
                'method' =>'DELETE',
                'route'  => ['calls.call.destroy', $call->id]
            ]) !!}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('calls.call.index') }}" class="btn btn-primary" title="{{ __('Show All Call') }}">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('calls.call.create') }}" class="btn btn-success" title="{{ __('Create New Call') }}">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('calls.call.edit', $call->id ) }}" class="btn btn-primary" title="{{ __('Edit Call') }}">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    {!! Form::button('<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>', 
                        [   
                            'type'    => 'submit',
                            'class'   => 'btn btn-danger',
                            'title'   => 'Delete Call',
                            'onclick' => "return confirm(" .  __('Click Ok to delete Call.') . ")"
                        ])
                    !!}
                </div>
            {!! Form::close() !!}

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Organization</dt>
            <dd>{{ optional($call->organization)->name }}</dd>
            <dt>Channel</dt>
            <dd>{{ $call->channel }}</dd>
            <dt>Sip User</dt>
            <dd>{{ optional($call->sipUser)->id }}</dd>
            <dt>Call Status</dt>
            <dd>{{ $call->call_status }}</dd>
            <dt>Connect Time</dt>
            <dd>{{ $call->connect_time }}</dd>
            <dt>Ringing Time</dt>
            <dd>{{ $call->ringing_time }}</dd>
            <dt>Establish Time</dt>
            <dd>{{ $call->establish_time }}</dd>
            <dt>Disconnect Time</dt>
            <dd>{{ $call->disconnect_time }}</dd>
            <dt>Duration</dt>
            <dd>{{ $call->duration }}</dd>
            <dt>User Agent</dt>
            <dd>{{ $call->user_agent }}</dd>
            <dt>Uas</dt>
            <dd>{{ ($call->uas) ? 'Yes' : 'No' }}</dd>
            <dt>Created At</dt>
            <dd>{{ $call->created_at }}</dd>
            <dt>Updated At</dt>
            <dd>{{ $call->updated_at }}</dd>

        </dl>

    </div>
</div>

@endsection
