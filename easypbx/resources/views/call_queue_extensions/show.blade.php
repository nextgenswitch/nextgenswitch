@extends('layouts.app')

@section('content')

<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <div class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($title) ? $title : __('Call Queue Extension') }}</h4>
        </div>

        <div class="pull-right">
        
            {!! Form::open([
                'method' =>'DELETE',
                'route'  => ['call_queue_extensions.call_queue_extension.destroy', $callQueueExtension->id]
            ]) !!}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('call_queue_extensions.call_queue_extension.index') }}" class="btn btn-primary" title="{{ __('Show All Call Queue Extension') }}">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('call_queue_extensions.call_queue_extension.create') }}" class="btn btn-success" title="{{ __('Create New Call Queue Extension') }}">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('call_queue_extensions.call_queue_extension.edit', $callQueueExtension->id ) }}" class="btn btn-primary" title="{{ __('Edit Call Queue Extension') }}">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    {!! Form::button('<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>', 
                        [   
                            'type'    => 'submit',
                            'class'   => 'btn btn-danger',
                            'title'   => 'Delete Call Queue Extension',
                            'onclick' => "return confirm(" .  __('Click Ok to delete Call Queue Extension.') . ")"
                        ])
                    !!}
                </div>
            {!! Form::close() !!}

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Allow Diversion</dt>
            <dd>{{ $callQueueExtension->allow_diversion }}</dd>
            <dt>Call Queue</dt>
            <dd>{{ optional($callQueueExtension->callQueue)->agent_announcemnet }}</dd>
            <dt>Created At</dt>
            <dd>{{ $callQueueExtension->created_at }}</dd>
            <dt>Extension</dt>
            <dd>{{ optional($callQueueExtension->extension)->name }}</dd>
            <dt>Member Type</dt>
            <dd>{{ $callQueueExtension->member_type }}</dd>
            <dt>Priority</dt>
            <dd>{{ $callQueueExtension->priority }}</dd>
            <dt>Updated At</dt>
            <dd>{{ $callQueueExtension->updated_at }}</dd>

        </dl>

    </div>
</div>

@endsection
