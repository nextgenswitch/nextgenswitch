@extends('layouts.app')

@section('content')

<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <div class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($title) ? $title : __('Call Leg') }}</h4>
        </div>

        <div class="pull-right">
        
            {!! Form::open([
                'method' =>'DELETE',
                'route'  => ['call_legs.call_leg.destroy', $callLeg->id]
            ]) !!}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('call_legs.call_leg.index') }}" class="btn btn-primary" title="{{ __('Show All Call Leg') }}">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('call_legs.call_leg.create') }}" class="btn btn-success" title="{{ __('Create New Call Leg') }}">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('call_legs.call_leg.edit', $callLeg->id ) }}" class="btn btn-primary" title="{{ __('Edit Call Leg') }}">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    {!! Form::button('<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>', 
                        [   
                            'type'    => 'submit',
                            'class'   => 'btn btn-danger',
                            'title'   => 'Delete Call Leg',
                            'onclick' => "return confirm(" .  __('Click Ok to delete Call Leg.') . ")"
                        ])
                    !!}
                </div>
            {!! Form::close() !!}

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Call</dt>
            <dd>{{ optional($callLeg->call)->channel }}</dd>
            <dt>Channel</dt>
            <dd>{{ $callLeg->channel }}</dd>
            <dt>Sip User</dt>
            <dd>{{ optional($callLeg->sipUser)->id }}</dd>
            <dt>Call Status</dt>
            <dd>{{ $callLeg->call_status }}</dd>
            <dt>Connect Time</dt>
            <dd>{{ $callLeg->connect_time }}</dd>
            <dt>Ringing Time</dt>
            <dd>{{ $callLeg->ringing_time }}</dd>
            <dt>Establish Time</dt>
            <dd>{{ $callLeg->establish_time }}</dd>
            <dt>Disconnect Time</dt>
            <dd>{{ $callLeg->disconnect_time }}</dd>
            <dt>Duration</dt>
            <dd>{{ $callLeg->duration }}</dd>
            <dt>Created At</dt>
            <dd>{{ $callLeg->created_at }}</dd>
            <dt>Updated At</dt>
            <dd>{{ $callLeg->updated_at }}</dd>

        </dl>

    </div>
</div>

@endsection
