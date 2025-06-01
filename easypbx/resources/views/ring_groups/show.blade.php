@extends('layouts.app')

@section('content')

<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <div class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($title) ? $title : __('Ring Group') }}</h4>
        </div>

        <div class="pull-right">
        
            {!! Form::open([
                'method' =>'DELETE',
                'route'  => ['ring_groups.ring_group.destroy', $ringGroup->id]
            ]) !!}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('ring_groups.ring_group.index') }}" class="btn btn-primary" title="{{ __('Show All Ring Group') }}">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('ring_groups.ring_group.create') }}" class="btn btn-success" title="{{ __('Create New Ring Group') }}">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('ring_groups.ring_group.edit', $ringGroup->id ) }}" class="btn btn-primary" title="{{ __('Edit Ring Group') }}">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    {!! Form::button('<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>', 
                        [   
                            'type'    => 'submit',
                            'class'   => 'btn btn-danger',
                            'title'   => 'Delete Ring Group',
                            'onclick' => "return confirm(" .  __('Click Ok to delete Ring Group.') . ")"
                        ])
                    !!}
                </div>
            {!! Form::close() !!}

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Code</dt>
            <dd>{{ $ringGroup->code }}</dd>
            <dt>Description</dt>
            <dd>{{ $ringGroup->description }}</dd>
            <dt>Ring Strategy</dt>
            <dd>{{ $ringGroup->ring_strategy }}</dd>
            <dt>Ring Time</dt>
            <dd>{{ $ringGroup->ring_time }}</dd>
            <dt>Answer Channel</dt>
            <dd>{{ $ringGroup->answer_channel }}</dd>
            <dt>Skip Busy Extension</dt>
            <dd>{{ $ringGroup->skip_busy_extension }}</dd>
            <dt>Extension Group</dt>
            <dd>{{ optional($ringGroup->extensionGroup)->name }}</dd>
            <dt>Created At</dt>
            <dd>{{ $ringGroup->created_at }}</dd>
            <dt>Updated At</dt>
            <dd>{{ $ringGroup->updated_at }}</dd>

        </dl>

    </div>
</div>

@endsection
