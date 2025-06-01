@extends('layouts.app')

@section('content')

<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <div class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($timeGroup->name) ? $timeGroup->name : __('Time Group') }}</h4>
        </div>

        <div class="pull-right">
        
            {!! Form::open([
                'method' =>'DELETE',
                'route'  => ['time_groups.time_group.destroy', $timeGroup->id]
            ]) !!}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('time_groups.time_group.index') }}" class="btn btn-primary" title="{{ __('Show All Time Group') }}">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('time_groups.time_group.create') }}" class="btn btn-success" title="{{ __('Create New Time Group') }}">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('time_groups.time_group.edit', $timeGroup->id ) }}" class="btn btn-primary" title="{{ __('Edit Time Group') }}">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    {!! Form::button('<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>', 
                        [   
                            'type'    => 'submit',
                            'class'   => 'btn btn-danger',
                            'title'   => 'Delete Time Group',
                            'onclick' => "return confirm(" .  __('Click Ok to delete Time Group.') . ")"
                        ])
                    !!}
                </div>
            {!! Form::close() !!}

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Organization</dt>
            <dd>{{ optional($timeGroup->organization)->name }}</dd>
            <dt>Name</dt>
            <dd>{{ $timeGroup->name }}</dd>
            <dt>Time Zone</dt>
            <dd>{{ $timeGroup->time_zone }}</dd>
            <dt>Schedules</dt>
            <dd>{{ $timeGroup->schedules }}</dd>
            <dt>Created At</dt>
            <dd>{{ $timeGroup->created_at }}</dd>
            <dt>Updated At</dt>
            <dd>{{ $timeGroup->updated_at }}</dd>

        </dl>

    </div>
</div>

@endsection
