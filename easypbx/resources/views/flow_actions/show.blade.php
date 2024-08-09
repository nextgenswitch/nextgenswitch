@extends('layouts.app')

@section('content')

<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <div class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($title) ? $title : __('Flow Action') }}</h4>
        </div>

        <div class="pull-right">
        
            {!! Form::open([
                'method' =>'DELETE',
                'route'  => ['flow_actions.flow_action.destroy', $flowAction->id]
            ]) !!}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('flow_actions.flow_action.index') }}" class="btn btn-primary" title="{{ __('Show All Flow Action') }}">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('flow_actions.flow_action.create') }}" class="btn btn-success" title="{{ __('Create New Flow Action') }}">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('flow_actions.flow_action.edit', $flowAction->id ) }}" class="btn btn-primary" title="{{ __('Edit Flow Action') }}">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    {!! Form::button('<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>', 
                        [   
                            'type'    => 'submit',
                            'class'   => 'btn btn-danger',
                            'title'   => 'Delete Flow Action',
                            'onclick' => "return confirm(" .  __('Click Ok to delete Flow Action.') . ")"
                        ])
                    !!}
                </div>
            {!! Form::close() !!}

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Action Type</dt>
            <dd>{{ $flowAction->action_type }}</dd>
            <dt>Action Value</dt>
            <dd>{{ $flowAction->action_value }}</dd>
            <dt>Created At</dt>
            <dd>{{ $flowAction->created_at }}</dd>
            <dt>Organization</dt>
            <dd>{{ optional($flowAction->organization)->name }}</dd>
            <dt>Updated At</dt>
            <dd>{{ $flowAction->updated_at }}</dd>

        </dl>

    </div>
</div>

@endsection
