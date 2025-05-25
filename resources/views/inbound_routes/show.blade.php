@extends('layouts.app')

@section('content')

<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <div class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($title) ? $title : __('Inbound Route') }}</h4>
        </div>

        <div class="pull-right">
        
            {!! Form::open([
                'method' =>'DELETE',
                'route'  => ['inbound_routes.inbound_route.destroy', $inboundRoute->id]
            ]) !!}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('inbound_routes.inbound_route.index') }}" class="btn btn-primary" title="{{ __('Show All Inbound Route') }}">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('inbound_routes.inbound_route.create') }}" class="btn btn-success" title="{{ __('Create New Inbound Route') }}">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('inbound_routes.inbound_route.edit', $inboundRoute->id ) }}" class="btn btn-primary" title="{{ __('Edit Inbound Route') }}">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    {!! Form::button('<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>', 
                        [   
                            'type'    => 'submit',
                            'class'   => 'btn btn-danger',
                            'title'   => 'Delete Inbound Route',
                            'onclick' => "return confirm(" .  __('Click Ok to delete Inbound Route.') . ")"
                        ])
                    !!}
                </div>
            {!! Form::close() !!}

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Organization</dt>
            <dd>{{ optional($inboundRoute->organization)->id }}</dd>
            <dt>Did Pattern</dt>
            <dd>{{ $inboundRoute->did_pattern }}</dd>
            <dt>Cid Pattern</dt>
            <dd>{{ $inboundRoute->cid_pattern }}</dd>
            <dt>Function</dt>
            <dd>{{ optional($inboundRoute->function)->id }}</dd>
            <dt>Destination</dt>
            <dd>{{ optional($inboundRoute->destination)->id }}</dd>
            <dt>Created At</dt>
            <dd>{{ $inboundRoute->created_at }}</dd>
            <dt>Updated At</dt>
            <dd>{{ $inboundRoute->updated_at }}</dd>

        </dl>

    </div>
</div>

@endsection
