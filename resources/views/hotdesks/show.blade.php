@extends('layouts.app')

@section('content')

<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <div class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($hotdesk->name) ? $hotdesk->name : __('Hotdesk') }}</h4>
        </div>

        <div class="pull-right">
        
            {!! Form::open([
                'method' =>'DELETE',
                'route'  => ['hotdesks.hotdesk.destroy', $hotdesk->id]
            ]) !!}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('hotdesks.hotdesk.index') }}" class="btn btn-primary" title="{{ __('Show All Hotdesk') }}">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('hotdesks.hotdesk.create') }}" class="btn btn-success" title="{{ __('Create New Hotdesk') }}">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('hotdesks.hotdesk.edit', $hotdesk->id ) }}" class="btn btn-primary" title="{{ __('Edit Hotdesk') }}">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    {!! Form::button('<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>', 
                        [   
                            'type'    => 'submit',
                            'class'   => 'btn btn-danger',
                            'title'   => 'Delete Hotdesk',
                            'onclick' => "return confirm(" .  __('Click Ok to delete Hotdesk.') . ")"
                        ])
                    !!}
                </div>
            {!! Form::close() !!}

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Name</dt>
            <dd>{{ $hotdesk->name }}</dd>
            <dt>Sip User</dt>
            <dd>{{ optional($hotdesk->sipUser)->id }}</dd>
            <dt>Created At</dt>
            <dd>{{ $hotdesk->created_at }}</dd>
            <dt>Updated At</dt>
            <dd>{{ $hotdesk->updated_at }}</dd>

        </dl>

    </div>
</div>

@endsection
