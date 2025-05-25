@extends('layouts.app')

@section('content')

<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <div class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($trunk->name) ? $trunk->name : __('Trunk') }}</h4>
        </div>

        <div class="pull-right">
        
            {!! Form::open([
                'method' =>'DELETE',
                'route'  => ['trunks.trunk.destroy', $trunk->id]
            ]) !!}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('trunks.trunk.index') }}" class="btn btn-primary" title="{{ __('Show All Trunk') }}">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('trunks.trunk.create') }}" class="btn btn-success" title="{{ __('Create New Trunk') }}">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('trunks.trunk.edit', $trunk->id ) }}" class="btn btn-primary" title="{{ __('Edit Trunk') }}">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    {!! Form::button('<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>', 
                        [   
                            'type'    => 'submit',
                            'class'   => 'btn btn-danger',
                            'title'   => 'Delete Trunk',
                            'onclick' => "return confirm(" .  __('Click Ok to delete Trunk.') . ")"
                        ])
                    !!}
                </div>
            {!! Form::close() !!}

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Created At</dt>
            <dd>{{ $trunk->created_at }}</dd>
            <dt>Name</dt>
            <dd>{{ $trunk->name }}</dd>
            <dt>Organization</dt>
            <dd>{{ optional($trunk->organization)->id }}</dd>
            <dt>Sip User</dt>
            <dd>{{ optional($trunk->sipUser)->id }}</dd>
            <dt>Updated At</dt>
            <dd>{{ $trunk->updated_at }}</dd>

        </dl>

    </div>
</div>

@endsection
