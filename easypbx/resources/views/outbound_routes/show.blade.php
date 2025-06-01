@extends('layouts.app')

@section('content')

<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <div class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($outboundRoute->name) ? $outboundRoute->name : __('Outbound Route') }}</h4>
        </div>

        <div class="pull-right">
        
            {!! Form::open([
                'method' =>'DELETE',
                'route'  => ['outbound_routes.outbound_route.destroy', $outboundRoute->id]
            ]) !!}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('outbound_routes.outbound_route.index') }}" class="btn btn-primary" title="{{ __('Show All Outbound Route') }}">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('outbound_routes.outbound_route.create') }}" class="btn btn-success" title="{{ __('Create New Outbound Route') }}">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('outbound_routes.outbound_route.edit', $outboundRoute->id ) }}" class="btn btn-primary" title="{{ __('Edit Outbound Route') }}">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    {!! Form::button('<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>', 
                        [   
                            'type'    => 'submit',
                            'class'   => 'btn btn-danger',
                            'title'   => 'Delete Outbound Route',
                            'onclick' => "return confirm(" .  __('Click Ok to delete Outbound Route.') . ")"
                        ])
                    !!}
                </div>
            {!! Form::close() !!}

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Balance Share</dt>
            <dd>{{ $outboundRoute->balance_share }}</dd>
            <dt>Created At</dt>
            <dd>{{ $outboundRoute->created_at }}</dd>
            <dt>Is Active</dt>
            <dd>{{ $outboundRoute->is_active }}</dd>
            <dt>Name</dt>
            <dd>{{ $outboundRoute->name }}</dd>
            <dt>Organization</dt>
            <dd>{{ optional($outboundRoute->organization)->id }}</dd>
            <dt>Pattern</dt>
            <dd>{{ $outboundRoute->pattern }}</dd>
            <dt>Prefix Append</dt>
            <dd>{{ $outboundRoute->prefix_append }}</dd>
            <dt>Prefix Remove</dt>
            <dd>{{ $outboundRoute->prefix_remove }}</dd>
            <dt>Trunk</dt>
            <dd>{{ optional($outboundRoute->trunk)->name }}</dd>
            <dt>Updated At</dt>
            <dd>{{ $outboundRoute->updated_at }}</dd>

        </dl>

    </div>
</div>

@endsection
