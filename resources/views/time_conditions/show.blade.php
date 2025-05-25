@extends('layouts.app')

@section('content')

<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <div class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($timeCondition->name) ? $timeCondition->name : __('Time Condition') }}</h4>
        </div>

        <div class="pull-right">
        
            {!! Form::open([
                'method' =>'DELETE',
                'route'  => ['time_conditions.time_condition.destroy', $timeCondition->id]
            ]) !!}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('time_conditions.time_condition.index') }}" class="btn btn-primary" title="{{ __('Show All Time Condition') }}">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('time_conditions.time_condition.create') }}" class="btn btn-success" title="{{ __('Create New Time Condition') }}">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('time_conditions.time_condition.edit', $timeCondition->id ) }}" class="btn btn-primary" title="{{ __('Edit Time Condition') }}">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    {!! Form::button('<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>', 
                        [   
                            'type'    => 'submit',
                            'class'   => 'btn btn-danger',
                            'title'   => 'Delete Time Condition',
                            'onclick' => "return confirm(" .  __('Click Ok to delete Time Condition.') . ")"
                        ])
                    !!}
                </div>
            {!! Form::close() !!}

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Organization</dt>
            <dd>{{ optional($timeCondition->organization)->name }}</dd>
            <dt>Name</dt>
            <dd>{{ $timeCondition->name }}</dd>
            <dt>Time Group</dt>
            <dd>{{ optional($timeCondition->timeGroup)->name }}</dd>
            <dt>Matched Function</dt>
            <dd>{{ optional($timeCondition->matchedFunction)->id }}</dd>
            <dt>Matched Destination</dt>
            <dd>{{ optional($timeCondition->matchedDestination)->id }}</dd>
            <dt>Function</dt>
            <dd>{{ optional($timeCondition->function)->id }}</dd>
            <dt>Destination</dt>
            <dd>{{ optional($timeCondition->destination)->id }}</dd>
            <dt>Created At</dt>
            <dd>{{ $timeCondition->created_at }}</dd>
            <dt>Updated At</dt>
            <dd>{{ $timeCondition->updated_at }}</dd>

        </dl>

    </div>
</div>

@endsection
